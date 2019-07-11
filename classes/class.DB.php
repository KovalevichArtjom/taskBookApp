<?php


class DB
{
    private $dbhost;
    private $dbusername;
    private $dbpassword;
    private $dbname;
    public static $ObjMySqli;

    public function __construct()
    {
        $this->dbhost           = "localhost";
        $this->dbusername       = "root";
        $this->dbpassword       = "";
        $this->dbname           = "tasksBookApp";
        self::$ObjMySqli        = new mysqli($this->dbhost,$this->dbusername,$this->dbpassword,$this->dbname);
        $error = self::$ObjMySqli->connect_errno;
        if(!empty($error)){
            var_dump($error);
        }
    }

    public function query($query){
        $result = self::$ObjMySqli->query($query);
        if(!$result){
            return self::$ObjMySqli->error;
        }
        return $result;
    }
}