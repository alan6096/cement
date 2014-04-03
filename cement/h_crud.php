<?php
$db_table =$_POST['table'];
$db_col =($_POST['table_col']);
$th_txt =($_POST['table_header']);
$classname =$_POST['classname'];
$div_id = "div$classname";
$displayby =$_POST['filterby'];
$filterby_join =$_POST['filterby_join'];
$force_insert =$_POST['force_insert'];
$select_option =$_POST['select_option'];

$select_option_array= explode(",",$select_option);

if(count($select_option_array)>0)
{
    foreach ($select_option_array as $value) 
    {
        $select1[] = select_option2($value,"","");
    }   
}
$select_html = implode("<br>", $select1);
//if(count($select_option_array)<=1) $select_html = "";
if($select_option_array[0]=="") $select_html = "";

mycrud222($db_table,$db_col,$th_txt,$classname,$div_id,$displayby,$filterby_join,$force_insert);
advanceform($classname,$db_col,$th_txt,$select_html); //select_option = dept_id,Department Name,,tbl_department,name

$pagination_html = "<div id='smart-paginator$classname'></div>";
if(mysql_count($db_table,$displayby)<=10)$pagination_html="";

echo "<div id='$div_id' class='reference1'></div>";
//echo "<div id='div$classname' class='reference1'></div>";
echo "<div id='currentpage_$db_table$classname' currentpage='1'></div>";
unset($select1);
?>

<?php


function jointable($myarray,$table,$id) //join:assignee_id+hd_login.username
{
    $content1="";$content2="";$output="";
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
            
            $select_option = "$table1_field,sss,$table2,$table2_field";
            echo $select_option;
            $output = select_option2($select_option);
    }
    return $output;
}

function mycrud222($db_table,$db_col,$th_txt,$classname,$div_id,$displayby,$filterby_join,$force_insert)
{
    $orderby =$_POST['orderby'];
	$extra =$_POST['extra'];
	
    $db_col = str_replace(array("\t","\r","\n"),"",$db_col);
    $th_txt = str_replace(array("\r\n", "\r", "\n"), "", $th_txt);
    
    //$total_count = mysql_count($db_table,$displayby);
    
    //$th_txt=urlencode($th_txt);
    
    $content = "
    <script>
    
    function execute$db_table$classname(start,end,page)
    {
        $('#$div_id').load('h/h_generate_table.php',{extra:'$extra', orderby:'$orderby', page:page, start:start, end:end, tr_class:'$db_table$classname' ,table:'$db_table' ,tb_column:'$db_col' ,displayby:'$displayby', filterby_join:'$filterby_join', tb_th:'$th_txt'});
    	$('#temp_id').text(''); /* to avoid error when delete. make the temp_id empty */
    }   
                
    $(document).ready(function()
    {
        $.ajaxSetup ({cache: false});

        $('#refresh').live('click', function()
        {
            var value1=$('#limit_start$db_table$classname').text();
            var value2=$('#limit_total$db_table$classname').text();
            var page = $('#currentpage_$db_table$classname').attr('currentpage');
                    
            execute$db_table$classname(value1,20,page);
        });";   
        $content = $content . calldialog("$db_table$classname","#$classname",$db_table,$force_insert,$classname);
            
        $content = $content .  "
    });
 
    $('.class_pagination_$db_table$classname').live('click', function() /* code here: when pagination class clicked, retrieved it attributes such as page. Then calculate limit_start then generate table */
    {
        var page = $(this).attr('page');
        var limit_start = (page)*10-10;
        
        var value1=limit_start;
        var value2=$(this).attr('val2');
        
        $('#limit_start$db_table$classname').text(limit_start);
        
        execute$db_table$classname(limit_start,20,page);
        
        
        $('#currentpage_$db_table$classname').attr('currentpage',page);
        $('#currentpage_$db_table$classname').attr('limit_start',limit_start);
    });
    
    </script>
";

    $content = str_replace(array("  ","\t","\r","\n"),"",$content);
    echo $content;
}


function advanceform($form_id,$input_field,$label_field,$extra_field)
{
    $input_field = str_replace(array("\t","\r","\n"),"",$input_field);//str_replace(array("\r\n", "\r", "\n"), "", $input_field);
    $label_field = str_replace(array("\r\n", "\r", "\n"), "", $label_field);
    
    $temp="";
    $input_id = explode(",",$input_field);
    $label_txt = explode(",",$label_field);
    
    /*
    $count=0;
    foreach ($input_id as $key) 
    {
        $getlink_temp = explode("=",$key);
        if($getlink_temp[0]!="join") 
            $input_id_new[$count]=$key;
        $count++;
    }
    */
    $i=0;
    foreach ($input_id as $key)  //$key = name, email, age
    {
        $right_value = explode(",", $key); //make $key content into array
        
        foreach ($right_value as $value) //value now become single entity i.e [x1]=name, [x2]=email
        {
            $join_array = explode(":", $value); //if [x3]=join:id+id+id found then
            if($join_array[0]!="join" && $join_array[0]!="useicon")
            {
                $input_id_new[$i][0]=$value;
				$input_id_new[$i][1]="";
            }
			if($join_array[0]=="useicon" || $join_array[0]=="intable_hide")
            {
                $input_id_new[$i][0]=$join_array[1];
				$input_id_new[$i][1]="";
            }
			if($join_array[0]=="useicon_hide") /*to skip so we can write own select option */
            {
                $input_id_new[$i][0]=$join_array[1];
                $input_id_new[$i][1]="skipthis";
            }
			$i++;
        }
    }
    
    $temp = '
    <div style="display:none">
    <form id="'.$form_id.'" method="post" style="font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;">
        <h4>Details</h4>
        <fieldset class="dialog_field">';
        
        $x=0;
        $type = "";
		
    if($input_field)
    {
        $temp .= "<table>";
    	for ($x=0; $x < count($input_id_new); $x++) 
    	{ 
			$key = $input_id_new[$x][0];
			
			if($key=="password")$type="password";
			
			if($input_id_new[$x][1]=="")
			{
				$temp = $temp . '<tr><td><label for="'.$key.'" class="dialog_label">'.$label_txt[$x].' : </label></td><td><INPUT type="'.$type.'" class="dialog_input" NAME="'.$key.'" id="'.$key.'" TYPE="text"></td></tr>';
            	//$x++;
            	$type="";
			}
            
		}
    }   
    $temp = $temp . $extra_field;
    $temp .= "</table>";
    $temp = $temp . '
        </fieldset>
    </form>
    </div>
';
echo $temp;
}

function calldialog($classname,$form,$table,$hidden_value,$classnameori)
{
    if($hidden_value!="") $hidden_array = "hidden_value=$hidden_value&";
    else $hidden_array="";
    
    $content = '
    $("#'.$classname.'").remove();
    $("html").append(\'<div id="'.$classname.'" style="display:none"></div>\');
    $("#'.$classname.'").append(\'<input class="temp_navigation_value" type="button" id="refresh" value="" style="display:none" />\');
    $("#'.$classname.'").append(\'<div class="temp_navigation_value" id="temp_id" style="display:none"></div>\');
    $("#'.$classname.'").append(\'<div class="temp_navigation_value" id="startend'.$classname.'" style="display:none" val1="0" val2="10"></div>\');
    $("#'.$classname.'").append(\'<div class="temp_navigation_value" id="limit_start'.$classname.'"" style="display:none">0</div>\');
    $("#'.$classname.'").append(\'<div class="temp_navigation_value" id="limit_total'.$classname.'"" style="display:none">10</div>\');

    $("#refresh").trigger("click");
    
    $.ajaxSetup ({cache: false});
    
    $(".'.$classname.'").live("click", function()  /* execute when <tr> clicked */
    {
        $(this).addClass("selected").siblings().removeClass("selected");
        
        var id = $(this).attr("id");
        $("#temp_id").html(id);        
    });     
            
    $("#myupdate'.$table.$classname.'").live("click", function()
    {
        var id = $("#temp_id").text();
        getjsonvalue("id="+id+"&'.getall_table_detail($table).'","'.$classnameori.'");
        $("'.$form.'").dialog
        ({
            width:"500px",resizable: false,
            modal: true,
            close: function() {$("'.$form.'").dialog( "destroy" );},
            buttons: 
            {
                Cancel: function(){$("'.$form.'").dialog( "close" );$("'.$form.'").dialog( "destroy" );},
                Submit: function() 
                {
                    $.post("h/h_sql_crud.php?'.$hidden_array.'mode=update&table='.$table.'&id="+id,$("'.$form.'").serialize(), function(data) 
                    {
                        $("'.$form.'").dialog( "close" );
                        $("#refresh").trigger("click");
                    }); 
                }
            }
        });
    });
    
    $("#mycreate'.$table.$classname.'").live("click", function()
    {
        clearformvalue("'.get_table_colname($table).'","'.$classnameori.'");
        $("'.$form.'").dialog
        ({
            width:"500px",resizable: false,
            modal: true,
            close: function() {$("'.$form.'").dialog( "destroy" );},
            buttons: 
            {
                Cancel: function(){$("'.$form.'").dialog( "close" );$("'.$form.'").dialog( "destroy" );},
                Submit: function() 
                {
                	
                    $.post("h/h_sql_crud.php?'.$hidden_array.'mode=create&table='.$table.'",$("'.$form.'").serialize(), function(data) 
                    {
                        $("'.$form.'").dialog( "close" );
						
						var page = $("#currentpage_'.$classname.'").attr("currentpage");			
						var total_child = $(".'.$classname.'").length;
						if(total_child>=10)page=parseInt(page)+1;
						var limit_start = (page)*10-10;
						$("#currentpage_'.$classname.'").attr("currentpage",page);
						
						execute'.$classname.'(limit_start,10,page);		
                    }); 
                }
            }
        });
    });
    
    $("#mydelete'.$table.$classname.'").live("click", function()
    {
    	var page = $("#currentpage_'.$classname.'").attr("currentpage");		
        var id = $("#temp_id").text();
		
if(id!="") /* if temp_id empty, stop here */
{
	var result = confirm("Want to delete?" + id);
	if(result==true)
	{
        $.post("h/h_sql_crud.php?'.$hidden_array.'mode=delete&table='.$table.'&id="+id, function(data) 
        {
            /* $("#refresh").trigger("click"); */
			/* alert( $(".subindex").html() ); */
			/* alert( $(".'.$classname.'[id=\'"+id+"\']").attr("counter") ); */
			/* alert( $(".'.$classname.'").length ); */
			
			var total_child = $(".'.$classname.'").length;
			if(total_child<=1)page=page-1;
			var limit_start = (page)*10-10;
			$("#currentpage_'.$classname.'").attr("currentpage",page);
			
			execute'.$classname.'(limit_start,10,page);
        });
	}	
}
    });
    ';

    $content = $content . $content2;
    
    //$content = str_replace(array("  ","\t","\r","\n"),"",$content);
    return $content;
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
	
	/*
    if($param!="")
    {
        $param_array = explode(":", $param);
        $param1 = $param_array[0];
        $param2 = $param_array[1];
        $sql_param = "where $param1 = '$param2'";
    }*/
  
    include 'conf.php';
    $result = mysql_query("SELECT count(*) as totaldata from $table $sql_param")
    or die(mysql_error()); 
    
    $row = mysql_fetch_array($result);
    
    return $row["totaldata"];
}

function get_table_colname($table)
{
    $fieldname="";
    include 'conf.php';
    $res = mysql_query("SELECT * FROM $table");
    
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

function getall_table_detail($table)
{
    $fieldname="";
    include 'conf.php';
    $res = mysql_query("select * from $table");
    
    $i=0;
    while ($i < mysql_num_fields($res)) 
    {
        //$fieldname[$i]=mysql_field_name($res, $i);
        $fieldname = $fieldname. ($i>0 ? ",":"").mysql_field_name($res, $i);
        $i++;
    }
    return "table=$table&tb_field=$fieldname";
    //echo mysql_field_name($res, 0) . "\n";
    //return mysql_field_name($res, $index);
}

function select_option2($form,$size,$nolabel)
{
    //if(!$size)$size="150";    //if no size specified, set default|| department_id,Department:,,department,name
    $html = explode("+",$form); //company_id+Company++hd_company.name || html_id + html_txt + html_content + join_field
    $html_id=$html[0];
    $html_txt=$html[1];
    $html_content=$html[2];
    
    $join_field = $html[3];
    if($join_field!="")
    {
        $join_field_array = explode(".", $join_field);
        if(count($join_field_array)>1)
        {
            $table=$join_field_array[0];
            $custom_field_name=$join_field_array[1];
        }    
    }
    
    
    //$is_whereadd = explode("=",$html_content);
    //echo $is_whereadd[0];
    if(!$nolabel)
        $label ='<tr><td><label for="label_' .$html_id. '" class="dialog_label">' .$html_txt. ' : </label></td>';
    
    $temp = '

    '.$label.'
    <td><select class="dialog_select" id="'.$html_id.'" name="'.$html_id.'">'; 
        if($table=="")$temp = $temp . getOptionValueByIDmanual2(explode("&",$html_content),$nozero);
        else $temp = $temp . getOptionValueByID2($table,$html_content,$custom_field_name); 
    $temp = $temp . '</select></td></tr>
    ';
    return $temp;
    //return $html_id;//<select id="'.$html_id.'" name="'.$html_id.'" style="width:200px">'; getOptionValueByIDmanual(explode("+",$html_content)); echo '</select></span>
    unset($join_field_array);
}

function getOptionValueByIDmanual2($myarray,$nozero)
{
	if($nozero!="nozero")
	{
		$temp = '<option value=0></option>';
	}
    	
    for($x=0;$x<count($myarray);$x++)
    {
        $temp = $temp . '<option value="' . $x . '">' . $myarray[$x]. '</option>';
    }
    return $temp;
}

function getOptionValueByID2($table,$param,$custom_field_name)
{
    $sql_param = "";
    
    include 'conf.php';
    
    $data=explode("&",$param);
    $totaldata=count($data);
    if($param=="")$totaldata=0;
    
    for($x=0;$x<$totaldata;$x++)
    {
        $filter=explode("=",$data[$x]);
        $filterBy=$filter[0];
        $filterValue=$filter[1];
        
        $rs->$filterBy=$filterValue;
        
        if($x==0) //separate where & and clause
            $sql_param = $sql_param . "where $filterBy='$filterValue'";
        if($x>0)
            $sql_param = $sql_param . " and $filterBy='$filterValue'";
    }
    
    $result = mysql_query("SELECT * FROM $table $sql_param") or die(mysql_error()); 
    
    $temp = '<option value=0></option>';
        
    while($row = mysql_fetch_array($result))
    {
        $id=$row['id'];
        $name=$row['name'];
        if($custom_field_name && isset($row["$custom_field_name"]))
            $name=$row["$custom_field_name"];
        
        $temp = $temp . '<option value="' .$id. '">' .$name. '</option>';
    }
    return $temp;
}
?>