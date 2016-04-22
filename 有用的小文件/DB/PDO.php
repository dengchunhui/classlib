<?php

/**
 * PDO 数据库操作基类
 *
 * @author JiangJian <silverd@sohu.com>
 * $Id: PDO.php 172 2012-11-20 03:37:20Z silverd30@gmail.com $
 */

class Com_DB_PDO
{
    /**
     * 存放主库的连接
     *
     * @var object
     */
    protected $_writeDbConn = null;

    /**
     * 存放从库的连接
     *
     * @var object
     */
    protected $_readDbConn = null;

    /**
     * 存放当前DB连接
     *
     * @var object
     */
    protected $_db = null;

    /**
     * 主库连接配置信息
     *
     * @var array
     */
    protected $_writeConf = array();

    /**
     * 从库连接配置信息
     *
     * @var object
     */
    protected $_readConf = array();

    /**
     * 是否强制连接主库
     *
     * @var bool
     */
    protected $_forceMaster = false;

    /**
     * 是否写SQL日志
     *
     * @var bool
     */
    protected $_enableLogging = false;

    /**
     * 是否进行长连接
     *
     * @var bool
     */
    protected $_persistent = false;

    /**
     * 是否启用仿真 (emulate) 预备义语句 (true)，否则使用原生 (native) 的预备义语句 (false)
     * 原生更加安全，确保语句在发送给 MySQL 服务器执行前被分析，如有语法错误会在 prepare 阶段就报错
     * 但仿真性能会更快，但有 SQL 注入风险 (例如表名为 ? 的情况)
     * 缺省设为 null 表示不改变 PDO::ATTR_EMULATE_PREPARES
     *
     * @var bool/null
     */
    protected $_emulatePrepare = null;

    /**
     * 构造函数，初始化配置
     *
     * @param array $writeConf
     * @param array $readConf
     * @param bool $forceMaster
     * @param bool $persistent
     * @param bool $emulatePrepare
     */
    public function __construct($writeConf, $readConf, $forceMaster = false, $persistent = false, $emulatePrepare = false)
    {
        $this->_writeConf       = $writeConf;
        $this->_readConf        = $readConf;
        $this->_forceMaster     = $forceMaster;
        $this->_persistent      = $persistent;
        $this->_emulatePrepare  = $emulatePrepare;

        // 是否写SQL日志
        if (isDebug() && !(defined('FORBID_SQL_LOG') && FORBID_SQL_LOG)) {
            $this->_enableLogging = true;
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * 获取主库的“写”数据连接
     *
     * @return PDO Object
     */
    protected function _getDbWriteConn()
    {
        // 判断是否已经连接
        if ($this->_writeDbConn && is_object($this->_writeDbConn)) {
            return $this->_writeDbConn;
        }

        $db = $this->_connect(parse_url($this->_writeConf));
        if (! $db || ! is_object($db)) {
            return false;
        }

        $this->_writeDbConn = $db;
        return $this->_writeDbConn;
    }

    /**
     * 获取从库的“读”数据连接
     *
     * @return PDO Object
     */
    protected function _getDbReadConn()
    {
        // 判断是否已经连接
        if ($this->_readDbConn && is_object($this->_readDbConn)) {
            return $this->_readDbConn;
        }

        // 没有从库配置则直接连主库
        if (! $this->_readConf){
            return $this->_getDbWriteConn();
        }

        // 乱序随机选择从库
        shuffle($this->_readConf);
        foreach ($this->_readConf as $slave) {
            $db = $this->_connect(parse_url($slave));
            if ($db && is_object($db)){
                $this->_readDbConn = $db;
                return $this->_readDbConn;
            }
        }
    }

    /**
     * 连接数据库
     *
     * @param array $conf
     * @return PDO Object
     */
    protected function _connect(array $conf)
    {
        try {

            $conf['path'] = trim($conf['path'], '/');
            ! isset($conf['port']) && $conf['port'] = '3306';

            $dsn = 'mysql:dbname=' . $conf['path'] . ';host=' . $conf['host'] . ';port=' . $conf['port'];

            $params = array();

            // 持久连接
            if ($this->_persistent) {
                $params[PDO::ATTR_PERSISTENT] = true;
            }

            $db = new PDO($dsn, $conf['user'], $conf['pass'], $params);

            // 仿真预备义语句（实际PDO默认为true）
            if ($this->_emulatePrepare != null) {
                $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, $this->_emulatePrepare);
            }

            // 设置编码
            $db->exec("SET NAMES UTF8");

            $db->dsn = $conf;

        } catch (PDOException $e) {

            Com_DB_Exception::process($e, '[Connection Failed] ' . $dsn);
            return false;
        }

        return $db;
    }

    /**
     * 释放数据库连接（释放写连接、读连接、临时连接）
     */
    public function disconnect()
    {
        $this->_writeDbConn = $this->_readDbConn = $this->_db = null;
    }

    /**
     * 选择数据库连接
     *
     * @param bool $forceMaster 是否强制连接主库
     * @return void
     */
    protected function _getChoiceDbConnect($forceMaster = false)
    {
        $forceMaster = ($forceMaster || $this->_forceMaster);
        $this->_db = $forceMaster ? $this->_getDbWriteConn() : $this->_getDbReadConn();
    }

    /**
     * 执行操作的底层接口
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster 是否强制连接主库
     * @return PDO Statement
     */
    protected function _autoExecute($sql, $params = array(), $forceMaster = false)
    {
        try {

            $this->_getChoiceDbConnect($forceMaster);
            if (! $this->_db) {
                throw new Com_DB_Exception('DB connection lost.');
            }

            // 调试模式打印SQL信息
            $explain = array();
            if ($this->_enableLogging && DEBUG_EXPLAIN_SQL) {
                $explain = $this->_explain($sql, $params);
            }

            $sqlStartTime = microtime(true);

            // 预编译 SQL
            $stmt = $this->_db->prepare($sql);
            if (! $stmt) {
                throw new Com_DB_Exception(implode(',', $this->_db->errorInfo()));
            }

            // 绑定参数
            $params = $params ? (array) $params: array();

            // 执行 SQL
            if (! $stmt->execute($params)) {
                throw new Com_DB_Exception(implode(',', $stmt->errorInfo()));
            }

            $sqlCostTime = microtime(true) - $sqlStartTime;

            // 调试模式打印SQL信息
            if ($this->_enableLogging) {
                Com_DB::sqlLog($this->_formatLogSql($sql, $params), $sqlCostTime, $explain);
            }

            return $stmt;

        } catch (Exception $e) {
            Com_DB_Exception::process($e, '[SQL Failed]', $this->_formatLogSql($sql, $params));
            return false;
        }
    }

    /**
     * 返回 Explain SQL 信息
     *
     * @param string $sql
     * @param array $params
     * @return array
     */
    protected function _explain($sql, $params)
    {
        if ('select' != strtolower(substr($sql, 0, 6))) {
            return array();
        }

        $sql = Com_DB::getRealSql($sql, $params);

        $explain = array();
        $stmt = $this->_db->query("EXPLAIN " . $sql);
        if ($stmt instanceof PDOStatement) {
            $explain = $stmt->fetch(PDO::FETCH_ASSOC);
            $explain['sql'] = $sql;
            $stmt->closeCursor();
        }

        return $explain;
    }

    /**
     * 把带参数的 SQL 的转换为可记录的 Log
     *
     * @param string $sql
     * @param array $params
     * @return string
     */
    protected function _formatLogSql($sql, $params)
    {
        return array(
            'sql'     => $sql,
            'params'  => $params,
            'realSql' => Com_DB::getRealSql($sql, $params),
            'host'    => isset($this->_db->dsn['host']) ? $this->_db->dsn['host'] : '',
            'dbName'  => isset($this->_db->dsn['path']) ? $this->_db->dsn['path'] : '',
        );
    }

    /**
     * 执行一条 SQL （一般针对写操作，如 update/delete）
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster 是否强制连接主库
     * @return int 影响行数
     */
    public function query($sql, $params = array(), $forceMaster = true)
    {
        $stmt = $this->_autoExecute($sql, $params, $forceMaster);
        if (! $stmt) {
            return false;
        }

        $rows = $stmt->rowCount();
        return $rows > 0 ? $rows : true;
    }

    /**
     * 获取所有记录
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster 是否强制连接主库
     * @return array
     */
    public function fetchAll($sql, $params = array(), $forceMaster = false)
    {
        $stmt = $this->_autoExecute($sql, $params, $forceMaster);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return array();
    }

    /**
     * 获取第一列
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster 是否强制连接主库
     * @return array
     */
    public function fetchCol($sql, $params = array(), $forceMaster = false)
    {
        $stmt = $this->_autoExecute($sql, $params, $forceMaster);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        return array();
    }

    /**
     * 获取键值对
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster 是否强制连接主库
     * @return array
     */
    public function fetchPairs($sql, $params = array(), $forceMaster = false)
    {
        $stmt = $this->_autoExecute($sql, $params, $forceMaster);
        if ($stmt) {
            $data = array();
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $data[$row[0]] = $row[1];
            }
            return $data;
        }
        return array();
    }

    /**
     * 获取关联数组
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster 是否强制连接主库
     * @return array
     */
    public function fetchAssoc($sql, $params = array(), $forceMaster = false)
    {
        $stmt = $this->_autoExecute($sql, $params, $forceMaster);
        if ($stmt) {
            $data = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $key = current($row);
                $data[$key] = $row;
            }
            return $data;
        }
        return array();
    }

    /**
     * 获取一个单元格
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster 是否强制连接主库
     * @return array
     */
    public function fetchOne($sql, $params = array(), $forceMaster = false)
    {
        $stmt = $this->_autoExecute($sql, $params, $forceMaster);
        if ($stmt) {
            return $stmt->fetchColumn();
        }
        return null;
    }

    /**
     * 获取单条记录
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster 是否强制连接主库
     * @return array
     */
    public function fetchRow($sql, $params = array(), $forceMaster = false)
    {
        $stmt = $this->_autoExecute($sql, $params, $forceMaster);
        if ($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return array();
    }

    /**
     * PDO fetch method
     *
     * @param PDO Statement $stmt
     * @return arary
     */
    public function fetchArray($stmt)
    {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 执行 SQL 并返回 PDO Statement
     *
     * @param string $sql
     * @param array $params
     * @param bool $forceMaster
     * @return PDO Statement
     */
    public function execute($sql, $params = array(), $forceMaster = true)
    {
        $stmt = $this->_autoExecute($sql, $params, $forceMaster);
        return $stmt ? $stmt : false;
    }

    /**
     * 删除
     *
     * @param string $table
     * @param array/string $whereArr
     * @param int $limit -1表示不限制
     * @param array $params
     * @return int
     */
    public function delete($table, $whereArr, $limit = -1, $params = array())
    {
        $whereSql = Com_DB::getWhereSql($whereArr);
        $sql = 'DELETE FROM `' . $table . '`' . ' WHERE '.$whereSql . ($limit > 0 ? (' LIMIT ' . $limit) : '');
        return $this->query($sql, $params);
    }

    /**
     * 插入记录
     *
     * @param string $table
     * @param array $setArr
     * @param bool $replace
     * @return int
     */
    public function insert($table, $setArr, $replace = false)
    {
        $insertkeysql = $insertvaluesql = $comma = '';
        foreach ($setArr as $key => $value) {
            if (false === $value) {
                unset($setArr[$key]);
                continue;
            }
            $insertkeysql .= $comma . '`' . $key . '`';
            $insertvaluesql .= $comma . '?';
            $comma = ', ';
        }

        $method = $replace ? 'REPLACE' : 'INSERT';
        $sql = $method . ' INTO `' . $table . '`' . '(' . $insertkeysql . ') ' . 'VALUES (' . $insertvaluesql . ')';

        $stmt = $this->_autoExecute($sql, array_values($setArr), true);
        if (! $stmt) {
            return 0;
        }

        $insertId = $this->lastInsertId();
        return $insertId ? $insertId : true;
    }

    /**
     * 替换
     *
     * @param string $table
     * @param array $setArr
     * @return int
     */
    public function replace($table, $setArr)
    {
        return $this->insert($table, $setArr, true);
    }

    /**
     * 更新记录
     *
     * @param string $table
     * @param array $setArr
     * @param array/string $whereArr
     * @param int $limit -1表示不限制
     * @return int
     */
    public function update($table, $setArr, $whereArr, $limit = -1)
    {
        $setSql = $comma = '';

        foreach ($setArr as $key => $value) {
            if (false === $value) { // 值为 false 表示不更新该字段
                unset($setArr[$key]);
                continue;
            } elseif (! is_array($value)) {
                $setSql .= $comma . "`{$key}` = ?";
            } else {
                if (isset($value[2])) {
                    if ('-' == $value[0]) { // 自减后保证不低于X
                        $setSql .= $comma . "`{$key}` = (CASE WHEN `{$key}` - {$value[1]} > {$value[2]} THEN `{$key}` - {$value[1]} ELSE {$value[2]} END)";
                    } elseif ('+' == $value[0]) {   // 自增后保证不大于X
                        $setSql .= $comma . "`{$key}` = (CASE WHEN `{$key}` + {$value[1]} < {$value[2]} THEN `{$key}` + {$value[1]} ELSE {$value[2]} END)";
                    }
                    unset($setArr[$key]);
                } else {
                    $setSql .= $comma . "`{$key}` = `{$key}` {$value[0]} ?";
                    $setArr[$key] = $value[1];
                }
            }
            $comma = ', ';
        }

        $whereSql = Com_DB::getWhereSql($whereArr);
        $sql = 'UPDATE `'.$table.'`' . ' SET ' . $setSql . ' WHERE ' . $whereSql . ($limit > 0 ? (' LIMIT ' . $limit) : '');

        return $this->query($sql, array_values($setArr));
    }

    /**
     * 批量插入记录
     *
     * @param string $table
     * @param array $setArrs
     * @param bool $replace
     * @return int
     */
    public function batchInsert($table, $setArrs, $replace = false)
    {
        $params = array();
        $insertkeysqlGot = false;
        if (! $setArrs || ! is_array($setArrs)) {
            return false;
        }

        $insertkeysql = $insertvaluesql = $comma = '';
        foreach ($setArrs as $setArr) {

            $insertvaluesqlNode = $commaNode = '';

            foreach ($setArr as $key => $value) {
                if (false === $value) {
                    unset($setArr[$key]);
                    continue;
                }

                if (! $insertkeysqlGot) {
                    $insertkeysql .= $commaNode . '`' . $key . '`';
                }

                $insertvaluesqlNode .= $commaNode . '?';
                $params[] = $value;

                $commaNode = ', ';
            }

            $insertvaluesql .= $comma . '(' . $insertvaluesqlNode . ')';
            $insertkeysqlGot = true;
            $comma = ', ';
        }

        $method = $replace ? 'REPLACE' : 'INSERT';
        $sql = $method . ' INTO `' . $table . '` (' . $insertkeysql . ') VALUES ' . $insertvaluesql;

        return $this->query($sql, $params);
    }

    /**
     * 分页封装 fetchAll
     *
     * @param string $sql
     * @param int $start
     * @param int $count
     * @param mixed $params
     * @param bool $forceMaster
     * @return array
     */
    public function limitQuery($sql, $start, $pageSize, $fetchMethod = 'fetchAll', $params = array(), $forceMaster = false)
    {
        $start = intval($start);
        if ($start < 0) {
            return array();
        }

        $pageSize = intval($pageSize);
        if ($pageSize > 0) { // pageSize 为0时表示取所有数据
            $sql .= ' LIMIT ' . $pageSize;
            if ($start > 0) {
                $sql .= ' OFFSET ' . $start;
            }
        }

        return $this->$fetchMethod($sql, $params, $forceMaster);
    }

    /**
     * 获取自增ID
     *
     * @return lastInsertId
     */
    public function lastInsertId()
    {
        return $this->_db->lastInsertId();
    }

    /**
     * 事务开始
     */
    public function beginTransaction()
    {
        $this->_getChoiceDbConnect(true);
        $this->_db->beginTransaction();
    }

    /**
     * 事务提交
     */
    public function commit()
    {
        $this->_getChoiceDbConnect(true);
        $this->_db->commit();
    }

    /**
     * 事务回滚
     */
    public function rollBack()
    {
        $this->_getChoiceDbConnect(true);
        $this->_db->rollBack();
    }

    /**
     * 字段自增
     *
     * @param string $table
     * @param string $field
     * @param int $value
     * @param array/string $whereArr
     * @param int $limit
     * @return int
     */
    public function increment($table, $field, $value, $whereArr, $limit = -1)
    {
        $whereSql = Com_DB::getWhereSql($whereArr);
        $limit = $limit > 0 ? 'LIMIT ' . $limit : '';
        $value = $value > 0 ? ' + ' . $value : $value;
        return $this->query("UPDATE `{$table}` SET `{$field}` = `{$field}` {$value} WHERE {$whereSql} {$limit}");
    }

    /**
     * 字段自减
     *
     * @param string $table
     * @param string $field
     * @param int $value
     * @param array/string $whereArr
     * @param int $limit
     * @return int
     */
    public function decrement($table, $field, $value, $whereArr, $limit = -1)
    {
        return $this->increment($table, $field, -$value, $whereArr, $limit);
    }

    /**
     * 字段自减（保证不小于0）
     *
     * @param string $table
     * @param string $field
     * @param int $value
     * @param array/string $whereArr
     * @param int $limit
     * @return int
     */
    public function decrementx($table, $field, $value, $whereArr, $limit = -1)
    {
        $whereSql = Com_DB::getWhereSql($whereArr);
        $limit = $limit > 0 ? 'LIMIT ' . $limit : '';

        return $this->query("UPDATE `{$table}` SET `{$field}` = (CASE WHEN `{$field}` > {$value} THEN `{$field}` - {$value} ELSE 0 END)
            WHERE {$whereSql} {$limit}");
    }

    /**
     * 检查一个表是否存在
     *
     * @param string $table
     * @return bool
     */
    public function checkTableExists($table)
    {
        return (bool) $this->fetchRow("SHOW TABLES LIKE '{$table}'");
    }
}