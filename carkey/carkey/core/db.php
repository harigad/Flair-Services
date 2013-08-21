<?php
class db{

    private $con;

    function __construct(){
        $this->open();
    }

    function open(){
        $dbuser="694952_victory";
        $dbpass="KrGxwX.PlPfa1";
        $thisdb="694952_carkeys";
       
        $thisHost="mysql51-004.wc1.ord1.stabletransit.com";
        //$thisHost="localhost";
        
        $con = mysql_connect($thisHost,$dbuser,$dbpass);
        
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
    
  //  $this->debug=true;
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
