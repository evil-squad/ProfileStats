<?php

    $global_sql=null;

    function get_sql()
    {
        global $global_sql;

        if($global_sql)
            return $global_sql;

        $global_sql=new mysqli($_SESSION["DB_ADDRESS"],$_SESSION["DB_USERNAME"],$_SESSION["DB_PASSWORD"],$_SESSION["DB_NAME"]);

        if($global_sql->connect_error)
        {
            echo "Connection Database error: ".$global_sql->connect_error;
            return null;
        }

        $global_sql->query("SET NAMES 'utf8'");
        $global_sql->query("USE ".$_SESSION["DB_NAME"]);

        return $global_sql;
    }

//     class SQLSelect
//     {
//         private $field_array=null;
//
//         private $where=null;
//         private $limit_start=0;
//         private $limit_count=0;
//
//         public
//     };//class SQLSelect
//
//     class SQLTable
//     {
//         private $sql=null;
//         private $table_name=null;
//         private $insert_id=0;
//
//         private $field_array=null;
//
//         public function __construct($s,$tn)
//         {
//             $this->sql=$s;
//             $this->table_name=$tn;
//         }
//
//         public function get_field_list()
//         {
//             if($field_array)
//                 return $field_array;
//
//             $sql_result=$this->sql->query("DESC ".$this->table_name);
//
//             if(!$sql_result)
//                 return null;
//
//             $this->field_list=array();
//             while ($row = $sql_result->fetch_object())
//                 $this->field_list[] = $row->Field;
//
//             return $this->field_list;
//         }
//     };//class SQLTable
//
//     class SQLConnect
//     {
//         private $sql=null;
//
//         public function __construct()
//         {
//             $sql=get_sql();
//         }
//
//         public function OpenTable($table_name)
//         {
//             if(!$sql)
//                 return(null);
//
//             return(new SQLTable($sql,$table));
//         }
//     };//class SQLConnect

    function get_field_list($sql,$table_name)
    {
    	if($sql==null)return null;

    	$sql_result=$sql->query("DESC ".$table_name);

    	if(!$sql_result)
    		return null;

    	$field_array=array();
    	while ($row = $sql_result->fetch_object())
    		$field_array[] = $row->Field;

    	return $field_array;
    }

    function select_table()//$sql,$table_name,$field_array,$where,$order,$start,$count)
    {
        $sql        =func_get_arg(0);
        $table_name =func_get_arg(1);
        $field_array =func_get_arg(2);

        if(func_num_args()>3)$where=func_get_arg(3);else $where=null;
        if(func_num_args()>4)$order=func_get_arg(4);else $order=null;
        if(func_num_args()>5)
        {
            $start=func_get_arg(5);
            $count=func_get_arg(6);
        }
        else
        {
            $start=0;
            $count=0;
        }

        if($sql==null)return;

        $sql_string="SELECT";

        if($field_array==null)
        {
        	$sql_string=$sql_string." * FROM ".$table_name;
        }
        else
        {
	        for($i=0;$i<count($field_array);$i++)
	            if($i==0)
	                $sql_string=$sql_string.' '.$field_array[$i];
	            else
	                $sql_string=$sql_string.','.$field_array[$i];

        	$sql_string=$sql_string." FROM ".$table_name;
        }

        if($where!=null&&strlen($where)>3)
        	$sql_string=$sql_string." WHERE ".$where;

        if($order!=null&&strlen($order)>3)
        	$sql_string=$sql_string." ORDER BY ".$order;

        if($count!=0)$sql_string=$sql_string." LIMIT ".$start.",".$count;

        $sql_result=$sql->query($sql_string);

    	if($sql_result)
            return $sql_result;

        echo 'SQL Query error,SQLString: '.$sql_string.'<br/>';
        echo 'SQL Error: '.$sql->error;
        return null;
    }

    function select_table_to_array()//$sql,$table_name,$field_array,$where,$start,$count)
    {
        $sql        =func_get_arg(0);
        $table_name =func_get_arg(1);
        $field_array =func_get_arg(2);

        if(func_num_args()>3)$where=func_get_arg(3);
        if(func_num_args()>4)
        {
            $start=func_get_arg(4);
            $count=func_get_arg(5);
        }
        else
        {
            $start=0;
            $count=0;
        }

        $sql_result=select_table($sql,$table_name,$field_array,$where,$start,$count);

        if(!$sql_result)return(null);

        $result=array();
        $index=0;

        while($row=$sql_result->fetch_row())
        {
            $result[$index]=$row;
            $index++;
        }

        $sql_result->close();

        return $result;
    }

    function select_field($sql,$table_name,$field,$where)
    {
        if($sql==null)return;

        $sql_string="SELECT ".$field." FROM ".$table_name." WHERE ".$where;

        $sql_result=$sql->query($sql_string);

        if(!$sql_result)
        	return null;

        $row=$sql_result->fetch_object();

        $sql_result->close();
        return $row;
    }

    /**
    * 向一个表中插入数据，使用array的kv模式表示字段和数据
    */
    function sql_insert()//$sql,$table_name,$data_array)
    {
        $sql=func_get_arg(0);

    	if($sql==null)return null;

    	$table_name=func_get_arg(1);
    	$data_array=func_get_arg(2);

    	if(func_num_args()==4)
            $resultmode=func_get_arg(3);
        else
            $resultmode=MYSQLI_STORE_RESULT;

    	$sql_string="INSERT INTO ".$table_name." SET";

    	$count=0;

    	foreach($data_array as $field=>$value)
    	{
    		if($count==0)
    			$sql_string=$sql_string.' '.$field.'="'.$value.'"';
    		else
    			$sql_string=$sql_string.','.$field.'="'.$value.'"';

    		++$count;
    	}

    	$sql_result=$sql->query($sql_string);

    	if($sql_result)
    	return $sql_result;

        echo 'SQL Insert error,SQLString: '.$sql_string.'<br/>';
        echo 'SQL Error: '.$sql->error;
        return null;
    }

    /**
    * 向一个表中插入数据，字段数据来自于post
    */
    function sql_insert_by_post()//$sql,$table_name,$field_array [,$resultmode=MYSQLI_STORE_RESULT])
    {
        $sql=func_get_arg(0);

        if($sql==null)return null;

        $data_array=array();

    	$table_name=func_get_arg(1);
    	$field_array=func_get_arg(2);

    	if(func_num_args()==4)
            $resultmode=func_get_arg(3);
        else
            $resultmode=MYSQLI_STORE_RESULT;

        foreach($field_array as $field)
        {
            if(isset($_POST[$field])&&strlen($_POST[$field])>0)
                $data_array[$field]=$_POST[$field];
        }

        return sql_insert($sql,$table_name,$data_array,$resultmode);
    }

    function sql_update($sql,$table_name,$where,$field_array)
    {
        if(!$sql)return null;
        if(!$where||strlen($where)<3)return null;
        if(!$field_array||!is_array($field_array)||count($field_array)<=0)return null;

        $sql_string='UPDATE '.$table_name.' SET ';

        $first_field=true;

        foreach($field_array as $field=>$value)
        {
            if(!$first_field)
                $sql_string.=',';
            else
                $first_field=false;

            $sql_string.=$field.'="'.$value.'"';
        }

        $sql_string.=" WHERE ".$where;

//        echo 'SQL Update SQLString: '.$sql_string.'<br/>';
    	$sql_result=$sql->query($sql_string);

    	if($sql_result)
    	return $sql_result;

        echo 'SQL Update error,SQLString: '.$sql_string.'<br/>';
        echo 'SQL Error: '.$sql->error;
        return null;
    }

    function sql_update_by_post($sql,$table_name,$where,$field_array)
    {
        if(!$sql)return null;
        if(!$where||strlen($where)<3)return null;
        if(!$field_array||!is_array($field_array)||count($field_array)<=0)return null;

        $data_array=array();

        foreach($field_array as $field)
        {
            if(isset($_POST[$field])&&strlen($_POST[$field])>0)
                $data_array[$field]=$_POST[$field];
        }

        return sql_update($sql,$table_name,$where,$data_array);
    }

    function sql_get_record_count($sql,$table_name,$where)
    {
        if(!$sql)return null;

        $sql_string="select count(*) from ".$table_name;

        if($where)
            $sql_string.=" where ".$where;

        $sql_result=$sql->query($sql_string);

        if(!$sql_result)
        {
            echo 'Query Error,SQLString: '.$sql_string.'<br/>';
            return null;
        }

        $result=$sql_result->fetch_row();

        $sql_result->close();

        return $result[0];
    }

    function sql_get_field_term($sql,$table_name,$field,$term,$where)
    {
        if(!$sql)return null;

        if($where)
            $sql_string="select ".$term."(".$field.") from ".$table_name." where ".$where;
        else
            $sql_string="select ".$term."(".$field.") from ".$table_name;

        $sql_result=$sql->query($sql_string);

        if(!$sql_result)
        {
            echo 'Query Error,SQLString: '.$sql_string.'<br/>';
            return null;
        }

        $result=$sql_result->fetch_row();

        $sql_result->close();

        return $result[0];
    }

    function sql_get_field_min($sql,$table_name,$field,$where)
    {
        return sql_get_field_term($sql,$table_name,$field,"MIN",$where);
    }

    function sql_get_field_max($sql,$table_name,$field,$where)
    {
        return sql_get_field_term($sql,$table_name,$field,"MAX",$where);
    }

    function sql_get_field_count($sql,$table_name,$field,$where)
    {
        return sql_get_field_term($sql,$table_name,$field,"COUNT",$where);
    }

    function sql_get_field_sum($sql,$table_name,$field,$where)
    {
        return sql_get_field_term($sql,$table_name,$field,"SUM",$where);
    }

    function sql_get_field_avg($sql,$table_name,$field,$where)
    {
        return sql_get_field_term($sql,$table_name,$field,"AVG",$where);
    }

    function sql_get_field_distinct($sql,$table_name,$field,$where)
    {
        if(!$sql)return null;

        if($where)
            $sql_string="select DISTINCT(".$field.") from ".$table_name.' WHERE '.$where.' ORDER BY '.$field;
        else
            $sql_string="select DISTINCT(".$field.") from ".$table_name.' ORDER BY '.$field;

        $sql_result=$sql->query($sql_string);

        if(!$sql_result)
        {
            echo 'Error,SQLString: '.$sql_string.'<br/';
            return null;
        }

        $result=array();

        while($obj=$sql_result->fetch_row())
            array_push($result,$obj[0]);

        $sql_result->close();

        return $result;
    }


    function sql_get_field_count_distinct($sql,$table_name,$field,$where)
    {
        if(!$sql)return null;

        if($where)
            $sql_result=$sql->query("select count(distinct(".$field.")) from ".$table_name." where ".$where);
        else
            $sql_result=$sql->query("select count(distinct(".$field.")) from ".$table_name);

        $result=$sql_result->fetch_row();

        $sql_result->close();

        return $result[0];
    }
?>
