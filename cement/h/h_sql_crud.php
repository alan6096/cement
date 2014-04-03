<?php
$id = $_REQUEST['id'];
$table=$_REQUEST['table'];
$all_array=get_table_colname($table);   //get all table col name split wih ,

$tb_field = explode(",",$all_array);    //convert them into array name $tb_field

if(!is_numeric($id)) /* use this if primary key is string */
    $id="'$id'";

//echo $all_array; /* don't echo when you want json to return value */
include 'conf.php';

if($_REQUEST['mode']=="create")
    sql_insert($table,$all_array);

if($id!="" && $_REQUEST['mode']=="update")
{
    sql_update($table,$all_array,$id);
}   

if($id!="" && $_REQUEST['mode']=="delete")
{
    sql_delete($table,$all_array,$id);
}

function get_table_colname($table)
{
    include 'conf.php';
    $res = mysql_query("select * from $table");
    
    $i=0;
    while ($i < mysql_num_fields($res)) 
    {
        //$fieldname[$i]=mysql_field_name($res, $i);
        $fieldname = $fieldname. ($i>0 ? ",":"").mysql_field_name($res, $i);
        $i++;
    }
    return $fieldname;
    //echo mysql_field_name($res, 0) . "\n";
    //return mysql_field_name($res, $index);
}

function sql_insert($table,$all_array)
{
    $tb_field = $all_array;//echo "<br>".$tb_field."<br>";
    $all_array_values = explode(",", $all_array);
    
    foreach ($all_array_values as $value)   //extract $tb_field as value
    {
        $gethisvalue = $_REQUEST[$value];
        
        if($value=="password")$gethisvalue = md5($_REQUEST[$value]);
        
        $all_values[]="'" .$gethisvalue. "'";
    }
    
    
    if($_REQUEST['hidden_value']!="") //do this to prevent explode array error(code below) i.e NaN value in hidden_value
    {
        $hidden_value=explode(",",$_REQUEST['hidden_value']);
        
        for($x=0;$x<count($hidden_value);$x++)  //extract $tb_field as value
        {
            $data = explode(":",$hidden_value[$x]);
            //$rs->$data[0] = $data[1]; //get the value of html tags form
            $rightvalue[] = $data[0];
            $leftvalue[] = $data[1];
            //echo "aa".$rightvalue[$x];
            for($z=0;$z<count($all_array_values);$z++)
            {
                if($all_array_values[$z]==$rightvalue[$x])
                {
                    $all_values[$z]="'" .$leftvalue[$x]. "'";
                }
            }
        }
    }
    
    $all_values_final = implode(",", $all_values);
    
    if(count($leftvalue>0))
    {
        //$tb_field = $tb_field."," . implode(",", $rightvalue);
    //$all_values_final = $all_values_final.",". implode(",", $leftvalue);
    }
    //echo "<br>".$all_values_final."<br>";
    include 'conf.php';
    //echo "<br>" . "INSERT INTO $table ($tb_field) VALUES ($all_values_final)";
    $result = mysql_query("INSERT INTO $table ($tb_field) VALUES ($all_values_final)")or die(mysql_error()); ;

    //mysql_free_result($result);
    //mysql_close($link);
    
    $json = array();
    $json['content']=$all_values_final;
    $json['result']=$result;
    $encoded = json_encode($json);
    die($encoded);
    
    //echo "<br>" . "INSERT INTO $table ($tb_field) VALUES ($all_values_final)";
}

function sql_update($table,$all_array,$id)
{
    $tb_field = $all_array;
    $all_array_values = explode(",", $all_array);
    
if($_REQUEST['update_one']!="yes")
{    
    foreach ($all_array_values as $value)   //extract $tb_field as value
    {
        //$all_values[]=$value . "='" .$_REQUEST[$value]. "'"; //become name='abc'
        $all_values[]=$value . '="' .mysql_real_escape_string($_REQUEST[$value]). '"'; //become name='abc'
        //$all_values[]=$value . "='" .urlencode($_REQUEST[$value]). "'"; //become name='abc'
        /*
        $key = '1234';
		$string = $_REQUEST[$value];

		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        $all_values[]=$value . "='" .($encrypted). "'"; //become name='abc'
		 */
    }
}

if($_REQUEST['update_one']=="yes")
{    
    foreach ($all_array_values as $value)   //extract $tb_field as value
    {
		if($_REQUEST[$value]!="")
		{
		 	$all_values[]=$value . '="' .mysql_real_escape_string($_REQUEST[$value]). '"'; //become name='abc'
		}
    }
}
    
    if($_REQUEST['hidden_value']!="") //do this to prevent explode array error(code below) i.e NaN value in hidden_value
    {
        $hidden_value=explode(",",$_REQUEST['hidden_value']);
        
        for($x=0;$x<count($hidden_value);$x++)  //extract $tb_field as value
        {
            $data = explode(":",$hidden_value[$x]);

            $rightvalue[] = $data[0];
            $leftvalue[] = $data[1];

            for($z=0;$z<count($all_array_values);$z++)
            {
                if($all_array_values[$z]==$rightvalue[$x])
                {
                    $all_values[$z]=$rightvalue[$x] . "='" .$leftvalue[$x]. "'"; //"'" .$leftvalue[$x]. "'";
                }
            }
        }
    }
    
    $all_values_final = implode(",", $all_values);
    
    include 'conf.php';
	
	$query = "UPDATE $table SET $all_values_final where id=$id";//echo $query; /* use single quote for id to avoid string param error */
	if($_REQUEST['update_one']=="yes")$query = "UPDATE $table SET $all_values_final where id=$id"; /* use single quote for id to avoid string param error */
    $result = mysql_query($query) 
    or die(mysql_error());
	
	$json = array();
    $json['content']=$query;
    $json['result']=$result;
    $encoded = json_encode($json);
    die($encoded);
}

function sql_delete($table,$all_array,$id)
{
    include 'conf.php';
    $result = mysql_query("DELETE FROM $table WHERE id=$id") /* use single quote for id to avoid string param error */
    or die(mysql_error()); 
    
    $json = array();
    $json['id']="11";
    $encoded = json_encode($json);
    die($encoded);
}
?>