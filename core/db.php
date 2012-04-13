<?php
class db{

    private $con;

    function __construct(){
        $this->open();
    }

    function open(){
        $dbuser="w155173470";
        $dbpass="KrGxwX.PlPfa";
        $thisdb="nibhor";
        $con = mysql_connect("localhost",$dbuser,$dbpass);
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }// some code
        mysql_select_db($thisdb, $con);

        //mysql_set_charset('utf8',$con);
        //$charset = mysql_client_encoding($con);
        //echo "The current character set is: $charset\n";
    }

    function close(){
        mysql_close($this->con);
    }
	
    function insert($table,$data){
        $columns="";
        $values="";
        foreach ($data as $column => $value){
            $columns.="" . $column . ",";
            $values.="'" . mysql_real_escape_string($value) . "',";
        }
        $columns=substr($columns,0,strlen($columns)-1);
        $values=substr($values,0,strlen($values)-1);

        $sql="insert into $table (" . $columns . ") values(";
        $sql.=$values . ");";
        //echo $sql;
         if($this->debug){
             //mysql_query("insert into sqllog(sql)values('" . mysql_real_escape_string($sql) . "')");
             echo $sql;
             }
        $rs=mysql_query($sql);
       
        return mysql_insert_id();
    }

    function update($table,$data,$where){
        $setStr="";
        foreach ($data as $column => $value){
            $setStr.="$column='" . mysql_real_escape_string($value) . "',";
        }
        $setStr=substr($setStr,0,strlen($setStr)-1);

        $sql="update $table set $setStr  where $where";
        if($this->debug){
           // mysql_query("insert into sqllog(sql)values('" . mysql_real_escape_string($sql) . "')");
            echo $sql;

            }
        $rs=mysql_query($sql);
    }

    function selectRows($sql){
          if($this->debug){
             // mysql_query("insert into sqllog(sql)values('" . mysql_real_escape_string($sql) . "')");
              echo $sql;
              }
        $rs=mysql_query($sql);
        return $rs;
    }

    function selectRow($sql){
          if($this->debug){
            //  mysql_query("insert into sqllog(sql)values('" . mysql_real_escape_string($sql) . "')");
              echo $sql;
              }

        $rs=mysql_query($sql);

        $row=mysql_fetch_array($rs);
        return $row;
    }


}
?>
