<?php

/**
 * 数据库工厂
 *
 * @author JiangJian <silverd@sohu.com>
 * $Id: DB.php 172 2012-11-20 03:37:20Z silverd30@gmail.com $
 */

class Com_DB
{
    private static $_dbs = array();

    /**
     * 取得DB连接
     *
     * @param string $dbName
     * @param string $hashKey
     * @param bool $forceMaster 强制主库
     * @return void
     */
    public static function get($dbName, $hashKey = '', $forceMaster = false)
    {
        // 连接配置
        $dbConf = Core_Config::loadEnv('db');

        if (! $dbConf) {
            throw new Com_DB_Exception('Empty dbconf, plz check: db.conf.php');
        }

        // 数据库连接配置信息
        $dbServers = isset($dbConf[$dbName]) ? $dbConf[$dbName] : array();

        // 如果分库，那某个分库可指定独立的连接配置，不指定则仍用主配置
        $dbName = Com_DB_Hash::dbName($dbName, $hashKey);
        if (isset($dbConf[$dbName])) {
            $dbServers = $dbConf[$dbName];
        }
        if (! $dbServers || ! is_array($dbServers)) {
            throw new Com_DB_Exception('Invalid DB configuration [' . $dbName . '], plz check: db.conf.php');
        }

        // 已创建的实例
        $dbKey = $dbName . '_' . intval($forceMaster);
        if (! isset(self::$_dbs[$dbKey])) {

            ! isset($dbServers['master']) && $dbServers['master'] = array();
            ! isset($dbServers['slave'])  && $dbServers['slave']  = array();

            // 创建数据库连接实例
            self::$_dbs[$dbKey] = new Com_DB_PDO(
                $dbServers['master'],
                $dbServers['slave'],
                $forceMaster,
                $dbConf['persistent'],
                $dbConf['emulate_prepare']
            );
        }

        return self::$_dbs[$dbKey];
    }

    /**
     * 释放连接
     */
    public static function disconnect()
    {
        if (self::$_dbs && is_array(self::$_dbs)) {
            foreach (self::$_dbs as $db) {
                $db->disconnect();
            }
            self::$_dbs = null;
        }
    }

    /**
     * 根据条件数组生成 SQL
     *
     * @param mixed $whereArr
     * @return string
     */
    public static function getWhereSql($whereArr)
    {
        $where = $comma = '';

        if (empty($whereArr)) {

            $where = '1';

        } elseif (is_array($whereArr)) {

            foreach ($whereArr as $field => $value) {

                $operator = '=';
                if (is_array($value)) {
                    $operator = $value[0];
                    $value    = $value[1];
                }

                // addslashes
                $value = self::escape($value);

                // 原生SQL条件
                if ($operator == 'SQL') {

                    $where .= $comma . '`' . $field . '` ' . $value;

                } else {

                    $where .= $comma . '`' . $field . '` ' . $operator;

                    switch ($operator) {
                        case 'IN':
                        case 'NOT IN':
                            $where .= ' (' . (is_array($value) ? ximplode($value) : $value) . ')';
                            break;
                        default:
                            $where .= ' \'' . $value . '\'';
                    }
                }

                $comma = ' AND ';
            }

        } else {

            $where = $whereArr;
        }

        return $where;
    }

    /**
     * 转义引号防止SQL注入
     *
     * @param mixed $string
     * @return mixed
     */
    public static function escape($string)
    {
        if (is_array($string)) {
            return array_map(__METHOD__, $string);
        } else {
            return is_string($string) ? addslashes($string) : $string;
        }
    }

    /**
     * 记录 SQL 执行日志（作用：供开发环境下打印SQL语句）
     *
     * @param array $sqlInfo
     * @param int $sqlCostTime
     * @param array $explainResult
     * @return void
     */
    public static function sqlLog($sqlInfo, $sqlCostTime, $explainResult = array())
    {
        $sqlInfo['time']    = $sqlCostTime;
        $sqlInfo['explain'] = $explainResult;
        $GLOBALS['_SQLs'][] = $sqlInfo;
    }

    /**
     * 将 SQL 语句中的 ? 替换为实际值
     *
     * @param string $sql
     * @param array $params
     * @return string
     */
    public static function getRealSql($sql, $params = array())
    {
        if ($params && is_array($params)) {
            while (strpos($sql, '?') > 0) {
                $sql = preg_replace('/\?/', "'" . array_shift($params) . "'", $sql, 1);
            }
        }

        return $sql;
    }

    /**
     * 对 MYSQL LIKE 的内容进行转义
     * @thanks ZhangYanJiong
     *
     * @param string string
     * @return string
     */
    public static function likeQuote($str)
    {
        return strtr($str, array("\\\\" => "\\\\\\\\", '_' => '\_', '%' => '\%', "\'" => "\\\\\'"));
    }

    /**
     * 高级DB查询
     *
     * @param array $data
     * @return mixed
     */
    public static function advQuery($data)
    {
        $dbName = $data['db'];
        $sql = stripslashes($data['query']);
        $fetchMethod = $data['fetchMethod'];
        $forceMaster = isset($data['forceMaster']) && $data['forceMaster'] ? true : false;

        if (! $dbName || ! $sql || ! $fetchMethod) {
            exit('Invalid advQuery Params');
        }

        $limit = isset($data['limit']) ? ($data['limit']) : 0;
        $limitSql = $limit > 0 ? ' LIMIT ' . $limit : ' LIMIT 1';
        $limitSql = $limit == -99 ? '' : $limitSql;

        $db = Com_DB::get($dbName);
        $result = $db->$fetchMethod($sql . $limitSql, array(), $forceMaster);

        // 打印结果
        $output = (isset($data['output']) && $data['output']) ? $data['output'] : 'print_r';
        if ($output) {
            echo '<pre>';
            $output($result);
            echo '<br />----------------------------<br />';
            $output($GLOBALS['__SQLs']);
            exit();
        }

        return $result;
    }
}