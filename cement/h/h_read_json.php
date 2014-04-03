<?php
include 'conf.php';
include '../skdb/skdb.php';

$id=$_GET['id'];
$table=$_GET['table'];
$join = $_REQUEST['join'];
$join_array = explode(",", $join);

$tb_field=$all_array=get_table_colname($table);//$_GET['tb_field'];

$tb_field = explode(",",$tb_field);

if(!is_numeric($id)) /* use this if primary key is string */
    $id="'$id'";


$sql_param = "where id = $id"; /* use single quote for id to avoid string param error */
$result = mysql_query("SELECT * FROM $table $sql_param") 
or die(mysql_error()); 

($row = mysql_fetch_array($result));
{
    foreach ($tb_field as $value)
    {
        $json[$value]=$row["$value"];
    }
}

for ($i=0; $i < count($join); $i++) 
{
	$param = jointable($join[$i],$table,$id);//echo $param[0];
	$getlink = joinlink($param);
    $json[$param[0]]=$getlink;
    unset($param);
    
}

//if(isset($join))
    //$json['joinlink']=joinlink();

$encoded = json_encode($json);
die($encoded);

//jointable($join,$table,$id);

function jointable($myarray,$table,$id) //join:tbl_employee.dept_id=tbl_department.id&tbl_department.sbu_id=tbl_sbu.id
{
    $content1="";$content2="";
	$fullarray = $myarray;//explode(",", $myarray);
	  
	if(isset($fullarray))
	{
	$content1 = $fullarray;
	    $content1_array = explode("=", $content1);
	    $content_value_left = $content1_array[0];
	        //$content_value_left_array = explode(".", $content_value_left);
	        //$table1 = $content_value_left_array[0];
	        $table1_field = $content_value_left;//$content_value_left_array[1];
	    
	    $content_value_right = $content1_array[1];
	        $content_value_right_array = explode(".", $content_value_right);
	        $table2 = $content_value_right_array[0];
	        $table2_field = $content_value_right_array[1];
	        
	}
    //echo $myarray;
	$param[] = $table1_field;
	$param[] = $table2;
	$param[] = $table2_field;
	
	return $param;
	//$json['joinlink']="$table1_field,$table2,$table2_field";
	//$encoded = json_encode($json);
	//die($encoded);
}

function get_table_colname($table)
{
    $fieldname="";
    include 'conf.php';
    $res = mysql_query("select * from $table")or die(mysql_error()); 
    
    $i=0;
    while ($i < mysql_num_fields($res)) 
    {
        $fieldname = $fieldname. ($i>0 ? ",":"").mysql_field_name($res, $i);
        $i++;
    }
    return $fieldname;
}

function joinlink($param)
{
    if(isset($param))
    {
	
	$id=$_GET['id'];
	$table=$_GET['table'];
	$join = $_REQUEST['join'];
	
    if(!is_numeric($id)) /* use this if primary key is string */
    $id="'$id'";
	
	$rs= new sksql($table);
	$rs->whereadd("id=$id");
	$rs->find();
	
	$row = $rs->fetch();
	$rs->id=$row->id;
	$rs_link = $rs->getlink($param[0],$param[1],$param[2]);
	return $rs_link->name;
    }
	//$json['joinlink']=$rs_link->name;
	//$encoded = json_encode($json);
	//die($encoded);
}

function joinlink2()
{
    include '../skdb/skdb.php';
    $id=$_GET['id'];
    $table=$_GET['table'];
    $join = $_REQUEST['join'];
    
    if(!is_numeric($id)) /* use this if primary key is string */
    $id="'$id'";
    
    $param = jointable($join,$table,$id);
    
    $rs= new sksql($table);
    $rs->whereadd("id=$id");
    $rs->find();
    
    $row = $rs->fetch();
    $rs->id=$row->id;
    $rs_link = $rs->getlink($param[0],$param[1],$param[2]);
    return $rs_link->name;
    
    //$json['joinlink']=$rs_link->name;
    //$encoded = json_encode($json);
    //die($encoded);
}
?>