<?php
$case_id= $_POST['case_id'];
$selected_id = $_POST['id'];
$db_table = $_POST['db_table'];
$input_label = $_REQUEST['input_label'];
$input_field = $_REQUEST['input_field'];
$form_name= $_POST['form_name'];
?>
<html>
<script>
<?php
echo "
$(document).ready(function()
{
	//alert(1);
	$.ajaxSetup ({cache: false});
	
	$('#div_$form_name').dialog
        ({
            autoOpen: true,
            width:'auto',resizable: false,
            modal: true,
            position:'center',
            close: function() {
            	$( this ).dialog('destroy');},
            buttons: 
            {
                Cancel: function()
                {
                	$('#div_$form_name').dialog('destroy');
                },
                Add: function()
                {
                	$.post('h/h_sql_crud.php?mode=update&table=$db_table&id=$selected_id',$('#form_$form_name').serialize(), function(data)
                    {
						$('#dialog_edit_content').load('h_editcase_dialog.php',{selected_id:$case_id});
	                    $('#dialog_edit').dialog('close');
	                    $('#dialog_edit').dialog('open');
	                    $('#div_$form_name').dialog('destroy');
                    });                
                }
            }
        });
        getjsonvalue('id=$selected_id&table=hd_user&tb_field=name,email,telephone,company_id','div_$form_name');
});
";
?>
</script>	
<?php
$input_label_array = explode(",", $input_label);
$input_field_array = explode(",", $input_field);

for ($i=0; $i < count($input_field_array); $i++) 
{ 
	$input_field_array_LR = explode(":", $input_field_array[$i]);
	$input_field_array_R[] = $input_field_array_LR[0];
	if($input_field_array_LR[1]) /* if select_option:array[2] i.e array[2] got value then execute here */
	{
		//select_option_array($input_field_array_LR);
		$input_text_array[] = select_option($input_label_array[$i],$input_field_array_LR);	
	}	

	if(!$input_field_array_LR[1]) /* to avoid generate input_text for everyone */
		$input_text_array[] = "<label class='dialog_label'>".$input_label_array[$i]."</label><input type='text' class'dialog_input' id='".$input_field_array_R[$i]."' name='".$input_field_array_R[$i]."' style='width:200px' />";
	
	unset($input_field_array_LR);
}
//echo $select_option_array2[0];
//echo $table1.$field1;
//echo implode("<br>", $input_text_array);
//echo implode(",", $input_field_array_L);

echo "
	<div id='div_$form_name'>
		<form id='form_$form_name' method='post' style='font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;'>
			<h4>Details</h4>
			<fieldset class='dialog_field'>
			".implode("<br>", $input_text_array)."	
			</fieldset>
		</form>
	</div>
	";

function select_option($label_index,$input_field_array_LR)
{
	$select_option_array[] = $input_field_array_LR[1];
	$select_option_array_LR = explode("=", $select_option_array[0]);
			
	$select_option_array_L = $select_option_array_LR[0];
	$select_option_array_R = $select_option_array_LR[1];
			
	$select_option_array_L_array = explode(".", $select_option_array_L);
	$table1 = $select_option_array_L_array[0];
	$field1 = $select_option_array_L_array[1];
		
	$select_option_array_R_array = explode(".", $select_option_array_R);
	$table2 = $select_option_array_R_array[0];
	$field2 = $select_option_array_R_array[1];
		
	//echo $table1.$field1;
	
	$temp .= "<label class='dialog_label'>$label_index</label>";
	$temp .= "<select class='dialog_select' id='$field1' name='$field1' style='width:200px'>";
	$temp .= select_option_value($table1,$field1,$table2,$field2);
	$temp .= "</select>";
	return $temp;
}

function select_option_value($table1,$field1,$table2,$field2) /* select_option:hd_user.company_id=hd_company.name */
{
	$sql_param = "";
    include 'conf.php';
    
    $result = mysql_query("SELECT * FROM $table2") or die(mysql_error()); 
    
    $temp = '<option value=0></option>';
        
    while($row = mysql_fetch_array($result))
    {
        $id=$row['id'];
        $name=$row[$field2];
        
        $temp = $temp . '<option value="' .$id. '">' .$name. '</option>';
    }
    return $temp;
}
	

?>
<div style='display:none'>
<form style='font-size:10px;font-family:Verdana, Arial, Helvetica, sans-serif;'>
	<h4>Details</h4>
	<fieldset class='dialog_field'>
		
	</fieldset>
</form>
</div>
</html>