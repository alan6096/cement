<?php
class form
{
    private $form_id;
	private $size;
    
    public function __construct($form_id)
    {
        $this->form_id=$form_id;
		$this->size=3;
    }
    
    public function begin($class)
    {
        $form_id = $this->form_id;
        echo "<form id='$form_id' method='post'><table id='$class'><tbody>";
    }
    
    public function input_text($title,$id,$size)
    {
    	if(!$size) $size = $this->size;
        echo "<tr><th>$title:</th><td colspan='$size'><input id='$id' name='$id' type='text'></td></tr>";
    }
    
    public function select_option($form,$selected_id,$nozero,$sortby,$size,$nolabel)
    {
        if(!$size)$size="150";  //if no size specified, set default
        $html = explode(",",$form);
        $html_id=$html[0];
        $html_txt=$html[1];
        $html_content=$html[2];
        $table=$html[3];
        $custom_field_name=$html[4];
        
        $label = "<tr><th>$html_txt:</th>";
        
        echo $label.'
        <td colspan="3"><select id="'.$html_id.'" name="'.$html_id.'" style="width:'.$size.'px">'; 
            if($table=="")
                $this->getOptionValueByIDmanual(explode("+",$html_content),$nozero);
            else 
                $this->getOptionValueByID($table,$html_content,$custom_field_name,$selected_id,$nozero,$sortby); 
        echo '</select></td></tr>
        ';
    }
    
    public function textarea($title,$id)
    {
        echo "<tr><th style='vertical-align:top'>$title:</th><td colspan='3'><textarea id='$id' name='$id' rows='12' cols='60'></textarea></td></tr>";
    }
    	
    public function getOptionValueByID($table,$param,$custom_field_name,$selected_id,$nozero,$sortby)
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
		echo $temp;
    }
    
    public function end()
    {
        echo "</tbody></table></form>";
    }
}

function getlinkname($id,$parameter,$different_field_name)
{
	$default_field_name = "name";
	if($different_field_name)
	{
		$default_field_name = $different_field_name;
	}
	//$txt = "hd_case.mileage_id=hd_mileage.name";

	$mainarray = explode("=", $parameter); //arr[0]=hd_case.mileage_id and arr[1]=hd_mileage.name
	$mainaray_left = explode(".", $mainarray[0]); //arr[0]=hd_case and arr[1]=mileage_id
	$mainaray_left_value1 = $mainaray_left[0]; //hd_case
	$mainaray_left_value2 = $mainaray_left[1]; //mileage_id
	
	$mainaray_right = explode(".", $mainarray[1]);
	$mainaray_right_value1 = $mainaray_right[0];
	$mainaray_right_value2 = $mainaray_right[1];
	
	$table1=$mainaray_left_value1;
	$table2=$mainaray_right_value1;
	$link1=$mainaray_left_value2;
	$link2=$mainaray_right_value2;
	
	include 'conf.php';
	$rs = DB_DataObject::factory($table1);
	$rs->id=$id;
	$rs->find();
	$rs->fetch();
	
	$linkresult = $rs->getLink($link1,$table2,$link2); 
	return $linkresult->$default_field_name;
}

//echo getlinkname("3171","hd_case.mileage_id=hd_mileage.id");

function select_option($form,$selected_id,$nozero,$sortby,$size,$nolabel)
{
    if(!$size)$size="150";  //if no size specified, set default
    $html = explode(",",$form);
    $html_id=$html[0];
    $html_txt=$html[1];
    $html_content=$html[2];
    $table=$html[3];
    $custom_field_name=$html[4];
    
    //$is_whereadd = explode("=",$html_content);
    //echo $is_whereadd[0];
    if(!$nolabel)
        $label ='<label for="label_' .$html_id. '">' .$html_txt. '</label>';
    
    echo '
    <span class="'.$html_id.'">
    '.$label.'
    <select id="'.$html_id.'" name="'.$html_id.'" style="width:'.$size.'px">'; 
        if($table=="")
            getOptionValueByIDmanual(explode("+",$html_content),$nozero);
        else 
            getOptionValueByID($table,$html_content,$custom_field_name,$selected_id,$nozero,$sortby); 
    echo '</select></span>
    ';
    //return $content;//<select id="'.$html_id.'" name="'.$html_id.'" style="width:200px">'; getOptionValueByIDmanual(explode("+",$html_content)); echo '</select></span>
}

function getOptionValueByIDmanual($myarray,$nozero)
{
    if(!$nozero)
    echo '<option value=0 style="font-family:arial; font-size:12px"></option>';
    for($x=0;$x<count($myarray);$x++)
    {
        echo '<option value="' . $myarray[$x] . '" style="font-family:arial; font-size:12px">' . $myarray[$x]. '</option>';
    }
}

function getOptionValueByID2($table,$param,$custom_field_name,$selected_id,$nozero,$sortby)
{
    $selected="";
    include 'conf.php';
    $rs = DB_DataObject::factory($table);
    if($sortby)
    $rs->orderBy($sortby);
    
    $data=explode("&",$param);
    $totaldata=count($data);
    if($param=="")$totaldata=0;
    
    for($x=0;$x<$totaldata;$x++)
    {
        $filter=explode("=",$data[$x]);
        $filterBy=$filter[0];
        $filterValue=$filter[1];
        
        $rs->$filterBy=$filterValue;
    }
    
    $rs->find();
    
    if(!$nozero)
    echo '<option value=0 style="font-family:arial; font-size:12px"></option>';
    while($rs->fetch())
    {
        $selected="";
        $id=$rs->id;
        $name=$rs->name;
        if($custom_field_name)$name=$rs->$custom_field_name;
        if($selected_id==$id)
            $selected = "selected";
        echo "<option value='$id' style='font-family:arial; font-size:12px' $selected>" .$name. '</option>';
    }
}

function get_table_colname($table)
{
	include 'conf.php';
	$res = mysql_query("select * from $table");
	
	$i=0;
	while ($i < mysql_num_fields($res)) 
	{
		$fieldname = $fieldname. ($i>0 ? ",":"").mysql_field_name($res, $i);
		$i++;
	}
	return $fieldname;
}

function getall_table_detail($table)
{
	include 'conf.php';
	$res = mysql_query("select * from $table");
	
	$i=0;
	while ($i < mysql_num_fields($res)) 
	{
		$fieldname = $fieldname. ($i>0 ? ",":"").mysql_field_name($res, $i);
		$i++;
	}
	return "table=$table&tb_field=$fieldname";
}

function calldialog($classname,$form,$table,$hidden_value,$classnameori)
{
	if($hidden_value!="") $hidden_array = "hidden_value=$hidden_value&";
	else $hidden_array="";
	
	return '
	$("#'.$classname.'").remove();
	$("html").append(\'<div id="'.$classname.'" style="display:none"></div>\');
	$("#'.$classname.'").append(\'<input type="button" id="refresh" value="" style="display:none" />\');
	$("#'.$classname.'").append(\'<div id="temp_id" style="display:none"></div>\');
	$("#'.$classname.'").append(\'<div id="startend'.$classname.'" style="display:none" val1="0" val2="10"></div>\');

	$("#refresh").trigger("click");
	
	$.ajaxSetup ({
    	cache: false
		});
		
	$(".'.$classname.'").live("click", function()
	{
		$(".'.$classname.'").css("background","white");
		$(this).css("background","#006699");
		var id = $(this).attr("id");
		$("#temp_id").html(id);
	});		
			
	$("#myupdate'.$table.$classname.'").live("click", function()
	{
		var id = $("#temp_id").text();
		getjsonvalue2("id="+id+"&'.getall_table_detail($table).'","'.$classnameori.'");
        $("'.$form.'").dialog
        ({
        	width:"500px",resizable: false,
            modal: true,
            buttons: 
            {
                Cancel: function(){$("'.$form.'").dialog( "close" );$("'.$form.'").dialog( "destroy" );},
                Submit: function() 
                {
                    $.post("z/z_sql_crud.php?'.$hidden_array.'mode=update&table='.$table.'&id="+id,$("'.$form.'").serialize(), function(data) 
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
            buttons: 
            {
                Cancel: function(){$("'.$form.'").dialog( "close" );$("'.$form.'").dialog( "destroy" );},
                Submit: function() 
                {
                    $.post("z/z_sql_crud.php?'.$hidden_array.'mode=create&table='.$table.'",$("'.$form.'").serialize(), function(data) 
					{
						var obj = $.parseJSON(data);
						
						$("'.$form.'").dialog( "close" );
						$("#refresh").trigger("click");
					});	
                }
            }
        });
    });
	
	$("#mydelete'.$table.$classname.'").live("click", function()
	{
		var id = $("#temp_id").text();
		$.post("z/z_sql_crud.php?'.$hidden_array.'mode=delete&table='.$table.'&id="+id, function(data) 
		{
			$("#refresh").trigger("click");
		});
	});
	';
}

function advanceform999($form_id,$input_field,$label_field,$extra_field)
{
	$input_field = str_replace(array("\t","\r","\n"),"",$input_field);//str_replace(array("\r\n", "\r", "\n"), "", $input_field);
	$label_field = str_replace(array("\r\n", "\r", "\n"), "", $label_field);
	
	$temp="";
	$input_id = explode(",",$input_field);
	$label_txt = explode(",",$label_field);
	
	$count=0;
	foreach ($input_id as $key) 
	{
		$getlink_temp = explode(":",$key);
		if($getlink_temp[0]!="getlink") $input_id_new[$count]=$key;
		$count++;
	}	
	
	$temp = '
	<div style="display:none">
	<form id="'.$form_id.'" method="post" style="font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;">
		<h4>Details</h4>
		<fieldset class="dialog_field">';
		
		$x=0;
	if($input_field)
	{	
		foreach ($input_id_new as $key)
		{
			$temp = $temp . '<label for="'.$key.'" class="dialog_label">'.$label_txt[$x].' : </label><INPUT class="dialog_input" NAME="'.$key.'" id="'.$key.'" TYPE="text"><br>';
			$x++;	
		}
	}	
	$temp = $temp . $extra_field;	
	$temp = $temp . '
		</fieldset>
	</form>
	</div>
';
echo $temp;
}

function advanceform($form_id,$input_field,$label_field,$extra_field)
{
	$input_field = str_replace(array("\t","\r","\n"),"",$input_field);//str_replace(array("\r\n", "\r", "\n"), "", $input_field);
	$label_field = str_replace(array("\r\n", "\r", "\n"), "", $label_field);
	
	$temp="";
	$input_id = explode(",",$input_field);
	$label_txt = explode(",",$label_field);
	
	$count=0;
	foreach ($input_id as $key) 
	{
		$getlink_temp = explode(":",$key);
		if($getlink_temp[0]!="getlink") $input_id_new[$count]=$key;
		$count++;
	}	
	
	$temp = '
	<div style="display:none">
	<form id="'.$form_id.'" method="post" style="font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;">
		<h4>Details</h4>
		<fieldset class="dialog_field">
		<table id="tableform">
		';
		
		$x=0;
	if($input_field)
	{	
		foreach ($input_id_new as $key)
		{
			$temp = $temp . '<tr><th>'.$label_txt[$x].' : </th><td colspan="3"><INPUT class="dialog_input" NAME="'.$key.'" id="'.$key.'" TYPE="text"></td></tr>';
			$x++;	
		}
	}	
	$temp = $temp . $extra_field;	
	$temp = $temp . '
		</table></fieldset>
	</form>
	</div>
';
echo $temp;
}

function mycrud($db_table,$db_col,$th_txt,$classname,$div_id,$displayby,$force_insert)
{
	$db_col = str_replace(array("\t","\r","\n"),"",$db_col);
	$th_txt = str_replace(array("\r\n", "\r", "\n"), "", $th_txt);
	
	$th_txt=urlencode($th_txt);
	
	$content = "
	<script>
	$.ajaxSetup ({cache: false});
	function execute$classname(start,end)
	{
		$('#$div_id').load('z/z_read_table.php?start='+start+'&end='+end+
					'&tr_class='+
						'mytable$db_table$classname'+
					
					'&table='+
						'$db_table'+
						
					'&tb_column='+
						'$db_col'+
					
					'&displayby='+
						'$displayby'+	
						
					'&tb_th=$th_txt');
	}	
				
	$(document).ready(function()
	{
		$('#refresh').live('click', function()
		{
			var value1=$('#startendmytable$db_table$classname').attr('val1');
			var value2=$('#startendmytable$db_table$classname').attr('val2');
			execute$classname(value1,value2);
		});";
		
		$content = $content . calldialog("mytable$db_table$classname","#$classname",$db_table,$force_insert,$classname);
		$content = $content .  "
	});
		
	$('.mypaginationmytable$db_table$classname').live('click', function()
	{
		var value1=$(this).attr('val1');
		var value2=$(this).attr('val2');
		execute$classname(value1,value2);
		$('#startendmytable$db_table$classname').attr('val1',value1);$('#startendmytable$db_table$classname').attr('val2',value2);
	});
	</script>";
	echo  str_replace(array("  ","\t","\r","\n"),"",$content);
}
	
function mycrud2($db_table,$db_col,$th_txt,$classname,$div_id,$displayby,$force_insert)
{
	$db_col = str_replace(array("\t","\r","\n"),"",$db_col);
	$th_txt = str_replace(array("\r\n", "\r", "\n"), "", $th_txt);
	
	$th_txt=urlencode($th_txt);
	
	$content = "
	<script>
	$.ajaxSetup ({cache: false});
	function execute$classname(start,end)
	{
		$('#$div_id').load('z/z_generate_table.php?start='+start+'&end='+end+
					'&tr_class='+
						'mytable$db_table$classname'+
					
					'&table='+
						'$db_table'+
						
					'&tb_column='+
						'$db_col'+
					
					'&displayby='+
						'$displayby'+	
						
					'&tb_th=$th_txt');
	}	
				
	$(document).ready(function()
	{
		$('#refresh').live('click', function()
		{
			var value1=$('#startendmytable$db_table$classname').attr('val1');
			var value2=$('#startendmytable$db_table$classname').attr('val2');
			execute$classname(value1,value2);
		});";
		
		$content = $content . calldialog("mytable$db_table$classname","#$classname",$db_table,$force_insert,$classname);
		$content = $content .  "
	});
		
	$('.mypaginationmytable$db_table$classname').live('click', function()
	{
		var value1=$(this).attr('val1');
		var value2=$(this).attr('val2');
		execute$classname(value1,value2);
		$('#startendmytable$db_table$classname').attr('val1',value1);$('#startendmytable$db_table$classname').attr('val2',value2);
	});
	</script>";
	echo  str_replace(array("  ","\t","\r","\n"),"",$content);
}	
?>