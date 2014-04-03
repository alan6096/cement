<?php
$start=$_POST['start']; 
$end=$_POST['end']; 
$table=$_POST['table']; 
$tb_field=$_POST['tb_column'];
$tr_class=$_POST['tr_class'];
$tb_th=$_POST['tb_th'];
$displayby = $_POST['displayby'];
$filterby_join =$_POST['filterby_join'];
$initval = $_POST['initval'];
$orderby =$_POST['orderby'];

$extra =$_POST['extra'];

if(!$initval)$initval=1;
$total_count = mysql_count($table,$displayby);

$temp_tb_field = $tb_field;
$tb_field = explode(",",$tb_field);
$tb_th_array= explode(",",$tb_th);
$displayby_array = explode(",",$displayby);

$x=0;
$found=0;
$sql_param="";

include '../conf.php';



if(count($displayby_array)>0 && $displayby_array[0]!="" && $filterby_join=="") /* check if filterby is exist */
{//echo $filterby_join;
    $x=0;
    
    foreach ($displayby_array as $value) 
    {
        if($x==0)
            $sql_param = $sql_param . "where $value ";
        else {
            $sql_param = $sql_param . " and $value ";
        }
        $x++;
    }
    $found=1;
}//echo "SELECT * FROM $table $sql_param";

if($filterby_join!="") /* check if filterby is exist */
{
	$sql_param .= $filterby_join;
	
	if(count($displayby_array)>0 && $displayby_array[0]!="")
	{
		$x=0;
	    foreach ($displayby_array as $value) 
	    {
	        if($x==0)
	            $sql_param = $sql_param . " and $value ";
	        else {
	            $sql_param = $sql_param . " and $value ";
	        }
	        $x++;
	    }
	    $found=1;
	}
}
$backup_query1 = $sql_param;
if($filterby_join!="")
	$backup_query1=substr_replace($sql_param, "select count(*) as num ", 0,stripos($sql_param, "from"));

if($orderby=="")
    $orderby = " order by id asc";
else
    $orderby = $orderby;

if($found==0 && $total_count>0) /* use total_count to check table empty. This avoid mysql syntax error */
    $sql_param = $sql_param . " $orderby LIMIT $start, $end";

if($found==1 && $total_count>0)
    $sql_param = $sql_param . " $orderby LIMIT $start, $end";

$hide="";
if(isset($_REQUEST['mode']) && $_REQUEST['mode']=="onlyedit")$hide="display:none";

$sql_query = "SELECT * FROM $table $sql_param";
if($filterby_join!="") $sql_query = $sql_param;
//echo $sql_query;
//echo $total_count;

$result = mysql_query($sql_query) or die(mysql_error()); /* might be error here because of static id not php variables. */

echo '<div class="CSSTableGenerator" style="font-size:9px;font-family:Verdana, Arial, Helvetica, sans-serif;">
<div class="subindex22" style="padding:0px 10px 0px 10px">
<a href="#" title="Create"><IMG id="mycreate'.$table.$tr_class.'" SRC="css/images/page_add.png" ALT="" height="20" width="20" style="border-style: none;'.$hide.'"></img></a>
<a href="#" title="Edit"><IMG id="myupdate'.$table.$tr_class.'" SRC="css/images/page_edit.png" ALT="" height="20" width="20" style="border-style: none;"></img></a>
<a href="#" title="Delete"><IMG id="mydelete'.$table.$tr_class.'" SRC="css/images/page_delete.png" ALT="" height="20" width="20" style="border-style: none;'.$hide.'"></img></a>
</div>
<table class="subindex">';

echo "<tr>";
foreach ($tb_th_array as $key) 
{
	if(strtolower($key)!="password")
    	echo "<th>$key</th>";
    $x++;
}
if($extra=="checkbox") echo "<th>All<input type='checkbox' id='checkall' /></th>";
echo "</tr>";

$counter = 0;
while($row = mysql_fetch_array($result))
{
    $id=$row['id'];
    echo "<tr class='$tr_class' id='$id' counter='$counter'>"; //include id so we can select the value with jquery later
    
    if(!is_numeric($id)) /* use this if primary key is string. Purposely put it after <tr> so that I can select the <tr id> later on with no quotes error */
    $id="'$id'";
	
    foreach ($tb_field as $value)
    {
        $child = explode("=",$value); //if : found make it to array $child
        $column=$child[0]; //ege: name,email,age
        
        $join_array = explode(":", $value); //ege: join:sbu_id+id+bia_sbu $join_array[0] will return join
        
        if($join_array[0]=="join" && $join_array[1]!="")  //join:tbl_employee.dept_id=tbl_department.id&tbl_department.sbu_id=tbl_sbu.id
        {
            $all_join = $join_array[1]; //ege: join:sbu_id+id+bia_sbu $join_array[0] will return sbu_id+id+bia_sbu
            jointable($all_join,$table,$id); //just extract the <td>x</td>
            //unset($join_array);
        }
		/* useicon_hide for table without word join:blabla so that in dialog form no double input text and select option */
		elseif(($join_array[0]=="useicon" || $join_array[0]=="useicon_hide") && $join_array[1]!="" && $join_array[0]!="intable_hide")  //join:tbl_employee.dept_id=tbl_department.id&tbl_department.sbu_id=tbl_sbu.id
        {
            $content = $join_array[1];
			$content_value = ($row[$join_array[1]]);
			if($content_value=='1')
				echo "<td align='center'><img src='css/images/icon_yes.png' /></td>";
			if($content_value=='0')
				echo "<td align='center'><img src='css/images/icon_no.png' /></td>";	
        }
				
        else
        {
            if($join_array[0]!="intable_hide")
			echo "<td>".td_value(nl2br($row[$value])).'</td>';
                //echo "<td>".nl2br($row[$value]).'</td>';
        }    
    }
	if($extra=="checkbox") echo "<td><input type='checkbox' class='multiple_delete' value='$id' /></td>";
    echo '</tr>';
	$counter++;
}
mysql_free_result($result);
mysql_close($link);
echo '</table></div>';
pagination($table,$tr_class,$backup_query1);
//echo "<div id='smart-paginator'></div>";
//echo "<div id='pagination$tr_class'></div>";

function jointable($myarray,$table,$id) //join:tbl_employee.dept_id=tbl_department.id&tbl_department.sbu_id=tbl_sbu.id
{
    $content1="";$content2="";
$fullarray = explode("&", $myarray);
  
if(isset($fullarray[0]))
{
$content1 = $fullarray[0];
    $content1_array = explode("+", $content1);
    $content_value_left = $content1_array[0];
        //$content_value_left_array = explode(".", $content_value_left);
        //$table1 = $content_value_left_array[0];
        $table1_field = $content_value_left;//$content_value_left_array[1];
    
    $content_value_right = $content1_array[1];
        $content_value_right_array = explode(".", $content_value_right);
        $table2 = $content_value_right_array[0];
        $table2_field = $content_value_right_array[1];
        
}

if(isset($fullarray[1]))
{
$content2= $fullarray[1];
    $content2_array = explode("+", $content2);
    $content_value_left = $content2_array[0];
        $content_value_left_array = explode(".", $content_value_left);
        $table3 = $content_value_left_array[0];
        $table3_field = $content_value_left_array[1];
    
    $content_value_right = $content2_array[1];
        $content_value_right_array = explode(".", $content_value_right);
        $table4 = $content_value_right_array[0];
        $table4_field = $content_value_right_array[1];
}
//echo "SELECT $table4.name as resultname FROM $table,$table2,$table4 WHERE $table.$table1_field = $table2.$table2_field AND $table3.$table3_field = $table4.$table4_field AND $table.id=$id";
    include '../conf.php';
    
    //if(isset($fullarray[0]) && isset($fullarray[1]))
    //$sqlstr = "SELECT $table1.name as resultname FROM $table1,$table2,$table3 WHERE $content1 AND $content2 AND $table1.id=$id";
    //$sqlstr = "SELECT users.id,users.name as name1,department.name as name2 FROM users,department WHERE users.department_id = department.id and users.id=8";
    //if(count($xx)<3)
    if(count($fullarray)>1) /* for 3 join */
    {
        
        $sqlstr = "SELECT $table4.name as resultname FROM $table,$table2,$table4 WHERE $table.$table1_field = $table2.$table2_field AND $table3.$table3_field = $table4.$table4_field AND $table.id=$id";
    }
    else /* for 2 join. I use id as default value to link table. It's important to craete table with field name id */
    {
        $sqlstr = "SELECT $table2.$table2_field as resultname FROM $table,$table2 WHERE $table.$table1_field = $table2.id AND $table.id=$id";
    }//echo $sqlstr;
    $result = mysql_query($sqlstr) or die(mysql_error()); 
    
    $row = mysql_fetch_array($result); //can work without loop i guess
    //while($row = mysql_fetch_array($result))
    {
    echo "<td>".$row["resultname"].'</td>'; //no need "name as" also can
    }
}

function td_value($string)
{
	//$string = urldecode($string);
	$string_array = explode("\n", $string); /* only display first line */
	$string = $string_array[0];
	return strlen($string) < 100 ? $string : substr($string, 0,100) . " ...";
}

function pagination($table,$tr_class,$filterby)
{
    //$limit_start = $_POST['limit_start'];
    //$limit_length = 10;
    /*
        Place code to connect to your DB here.
    */
    include('conf.php');    // include your code to connect to DB.

    $tbl_name=$table;        //your table name
    // How many adjacent pages should be shown on each side?
    $adjacents = 3;
    
    $sql_param = $filterby;
       
    /*
    include '../conf.php';
    $result = mysql_query("SELECT count(*) as item from $table $sql_param")
    or die(mysql_error()); 
    
    $row = mysql_fetch_array($result);
    
    /* 
       First get total number of rows in data table. 
       If you have a WHERE clause in your query, make sure you mirror it here.
    */
    $query = "SELECT COUNT(*) as num FROM $tbl_name $sql_param";
	if(!mysql_query($query))
	$query=$filterby;
	
    $total_pages = mysql_fetch_array(mysql_query($query));
    $total_pages = $total_pages["num"];
	
    //echo $total_pages . "==".$filterby;
    /* Setup vars for query. */
    $targetpage = "php_pagination.php";     //your file name  (the name of this file)
    $limit =$_POST['end']; ;               
                      //how many items to show per page
    $page = $_POST['page'];
    //if($page=="")$page = 0;
    
    if($page) 
        $start = ($page - 1) * $limit;          //first item to display on this page
    else
        $start = 0;                             //if no page var is given, set start to 0
    
    /* Get data. */
    $sql = "SELECT id FROM $tbl_name LIMIT $start, $limit";
    $result = mysql_query($sql);
    
    /* Setup page vars for display. */
    if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
    $prev = $page - 1;                          //previous page is page - 1
    $next = $page + 1;                          //next page is page + 1
    $lastpage = ceil($total_pages/$limit);      //lastpage is = total pages / items per page, rounded up.
    $lpm1 = $lastpage - 1;                      //last page minus 1
    
    /* 
        Now we apply our rules and draw the pagination object. 
        We're actually saving the code to a variable in case we want to draw it more than once.
    */
    $pagination = "";
    if($lastpage > 1)
    {   
        $pagination .= "<div $total_pages class=\"pagination\">";
        //previous button
        if ($page > 1) 
            $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='$prev' limit_start='' limit_length=''>previous </a>";
        else
            $pagination.= "<span class=\"disabled\"> previous</span>";  
        
        //pages 
        if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
        {   
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='$counter'>$counter</a>";                  
            }
        }
        elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
        {
            //close to beginning; only hide later pages
            if($page < 1 + ($adjacents * 2))        
            {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='class_pagination_$tr_class' title='row3' page='$counter'>$counter</a>";                  
                }
                $pagination.= "...";
                $pagination.= "<a class='class_pagination_$tr_class' title='row4' page='$lpm1'>$lpm1</a>";
                $pagination.= "<a class='class_pagination_$tr_class' title='row5' page='$lastpage'>$lastpage</a>";        
            }
            //in middle; hide some front and some back
            elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
            {
                $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='1'>1</a>";
                $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='2'>2</a>";
                $pagination.= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='$counter'>$counter</a>";                  
                }
                $pagination.= "...";
                $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='$lpm1'>$lpm1</a>";
                $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='$lastpage'>$lastpage</a>";        
            }
            //close to end; only hide early pages
            else
            {
                $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='1'>1</a>";
                $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='2'>2</a>";
                $pagination.= "...";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='$counter'>$counter</a>";                  
                }
            }
        }
        
        //next button
        if ($page < $counter - 1) 
            $pagination.= "<a class='class_pagination_$tr_class' title='row8' page='$next'>next</a>";
        else
            $pagination.= "<span class=\"disabled\">next </span>";
        $pagination.= "</div>\n";       
    }



        //while($row = mysql_fetch_array($result))
        {
    
        // Your while loop here
    
        }


echo $pagination;
//echo "<div id='limit_start_temp' limit_start='$limit_start' limit_lenght='$limit_end'>$limit_start</div>";
}

function mysql_count22($table,$param)
{
    $sql_param="";
    if($param!="")
    {
        $param_array = explode(":", $param);
        $param1 = $param_array[0];
        $param2 = $param_array[1];
        $sql_param = "where $param1 = '$param2'";
    }
  
    include '../conf.php';
    $result = mysql_query("SELECT count(*) as totaldata from $table $sql_param")
    or die(mysql_error()); 
    
    $row = mysql_fetch_array($result);
    
    return $row["totaldata"];
}

function mysql_count($table,$param)
{
    $sql_param="";
	$displayby_array = explode(",", $param);
	if(count($displayby_array)>0 && $displayby_array[0]!="")
	{
	    $x=0;
	    
	    foreach ($displayby_array as $value) 
	    {
	        if($x==0)
	            $sql_param = $sql_param . "where $value ";
	        else {
	            $sql_param = $sql_param . " and $value ";
	        }
	        $x++;
	    }
	    $found=1;
	}
  
    include '../conf.php';
    $result = mysql_query("SELECT count(*) as totaldata from $table $sql_param")
    or die(mysql_error()); 
    
    $row = mysql_fetch_array($result);
    
    return $row["totaldata"];
}
    
?>
    