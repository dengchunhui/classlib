<!--图书类文件: class_book.php-->
<?php

class book {

    private $id;
    private $name;
    private $price;
    private $author;

    function __construct() { //__construct：构造函数，建立连接
        //$this->name=$name;
        //$this->price=$price;
        //$this->author=$author;
    }

    function __set($property_name, $value) {
        return $this->$property_name = $value;
    }

    function __get($property_name) {
        if (isset($this->$property_name)) {
            return $this->$property_name;
        } else {
            return null;
        }
    }

    function add() {   //添加书目
        $db = new database();
        $query = "INSERT INTO Computer (name,price,author) ";
        $query.="VALUES ('$this->name',$this->price,'$this->author')";
        $db->execute($query);
        $db = null;
    }

    function update() {  //修改书目
        $db = new database();
        $query = "UPDATE Computer SET ";
        $query.="name='$this->name',price=$this->price,author='$this->author' ";
        $query.="WHERE id=$this->id";
        $db->execute($query);
        $db = null;
    }

    function delete() {  //删除书目
        $db = new database();
        $query = "DELETE FROM Computer WHERE id=$this->id";
        $db->execute($query);
        $db = null;
    }

    static function query($condition) {    //查询书目
        if ($condition == "" || $condition == null)
            $condition = "";
        else
            $condition = "WHERE " . $condition;
        $db = new database();
        $query = "SELECT * FROM Computer " . $condition;
        $arr = $db->query($query);
        return $arr;
        $db = null;
    }

}

/*
  $b=new book("C语言",15.20,"吴强");
  $b->add();
  //$b->__set(new_author,"3");
  //$b->update();
  //$b->delete();
  // */
?>
