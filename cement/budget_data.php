<?php 
include 'h/h_session.php'; 
include 'h/h_engine.php';
include 'skdb/skdb.php';
?>
<!DOCTYPE HTML>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
<html>
<head>
<link type="text/css" href="css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/h_dialogform.css">
<link rel="stylesheet" type="text/css" href="css/h_table.css">
<link rel="stylesheet" type="text/css" href="css/h_pagination.css">

<script src="js/jquery.js"></script>
<script type="text/javascript" src="h/h_getjson.js"></script>
<script type="text/javascript" src="js/jquery-ui.custom.min.js"></script>

<script>


$(document).ready(function()
{$.ajaxSetup({cache: false});
    
    $("#content1").load("h_crud.php",{table:"budget",table_col:"sector,material,total,plant,filedate",table_header:"Sector,Material,Total,Plant,Date", classname:"setting_table1", filterby:'plant="<?php echo $_POST['plant']; ?>"'});

	
});

<?php  

function isadmin()
{
	$username = $_SESSION['username'];
	$rs= new sksql("userlogin");
	$rs->whereadd("username='$username'");
	$rs->find();
	
	$row = $rs->fetch();
	return $row->role;
	//$rs->id=$row->id;
	//$rs_link = $rs->getlink($param[0],$param[1],$param[2]);
	//return $rs_link->name;
}

function dialog_new($button_id,$dialogform,$sqltable,$url,$updatemainform)
{
    $dialogid = "$dialogform" . "id";
    $sqltable_detail = getall_table_detail($sqltable);
    $current_year = date("Y");
    
    echo "
    $('body').append('<div id=\"$dialogid\"><div id=\"dialogcontent_$dialogform\"></div></div>');
    $('#$dialogid').dialog
    ({
            autoOpen: false,
            width:'680px',resizable: false,
            modal: true,
            position:'center',
            buttons: 
            {
                Cancel: function()
                {
                    $('#$dialogid').dialog( 'close' );
                },
                Add: function()
                {
                    var id = $('#temp_user').text()+'$current_year';
                    var staff_username = $('#temp_user').text();
                     
                    $.post('h/h_sql_crud.php?id='+id+'&staff_username='+staff_username+'&mode=create&table=$sqltable',$('#$dialogform').serialize(), function(data)
                    {
                        $('#$dialogid').dialog( 'close' );
                        $('#refresh').trigger('click'); /* need this to refresh the <table> */
						$updatemainform   
                    });
                }
            }
        });
	
	$('#dialogcontent_$dialogform').load('$url'); /* must put outside here to avoid form load at different angle */
	
    $('#$button_id').live('click',function()
    {
        $('#$dialogid').dialog('open');
    });
    ";
}

function dialog_edit($button_id,$dialogform,$sqltable,$id,$url,$updatemainform)
{
    $dialogid = "$dialogform" . "id";
    $sqltable_detail = getall_table_detail($sqltable);
    echo "
    $('body').append('<div id=\"$dialogid\"><div id=\"dialogcontent_$dialogform\"></div></div>');
    $('#$dialogid').dialog
    ({
            autoOpen: false,
            width:'980px',resizable: true,
            modal: true,
            position:'center',
            buttons: 
            {
                Cancel: function()
                {
                    $('#$dialogid').dialog( 'close' );
                },
                Add: function()
                {                   
                    $.post('h/h_sql_crud.php?mode=update&table=$sqltable',$('#$dialogform').serialize(), function(data)
                    {
                    	//var obj = $.parseJSON(data);
                        //alert(obj.content);
                    	//alert('Data Loaded: ' + data );
                        $('#$dialogid').dialog( 'close' );
                        //getjsontext('id=$id&$sqltable_detail','tni_form','category_office=category_office.id,category_os=category_os.id');
                        $('#refresh').trigger('click'); /* need this to refresh the <table> */
                        $updatemainform
                    });
                }
            }
        });
		
    $('#dialogcontent_$dialogform').load('$url'); /* must put outside here to avoid form load at different angle */
    $('#$button_id').live('click',function()
    {
        $('#$dialogid').dialog('open');
        //getjsonvalue('id='+$id+'&$sqltable_detail','$dialogform');
        getjsonvalue('id=$id&$sqltable_detail','$dialogform');
    });        
    ";
}

?>
</script>
</head>
<body>
<div  style="float:right">
    
</div>    
    
<div style="width:600px">
<div id="content3"></div>	
	
<div id="content1" style="float:left"><form id='form2' method='post'>
</div>

<div id="content2" style="float:right">
</div>
</div>

<div id="temp_user"></div>
</body>
</html>
<?php
function userexist()
{
    $username = $_SESSION['id'];
    $rs= new sksql("employee_tni");
    $rs->whereadd("id='$username'");
    $rs->find();
    $row = $rs->fetch();
    return $row->id;
}
?>
