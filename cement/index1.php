<?php include 'h/h_session.php';  ?>
<!DOCTYPE html>
<html>
	<link rel="stylesheet" href="views444/css/style.css" />
	<style type="text/css">@import "style.css";</style>
    <script src="js/jquery.js"></script>
    <script src="js/tab.js"></script>
	<script type="text/javascript" src="js/jquery-ui.custom.min.js"></script>
	<link type="text/css" href="css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet">
	<script type="text/javascript" src="z_getjson.js"></script>
	<link type="text/css" href="css/z_style.css2" rel="stylesheet">
	<link type="text/css" href="css/z_table.css" rel="stylesheet">
	<link type="text/css" href="css/z_menu.css" rel="stylesheet">
	<link type="text/css" href="css/z_button.css" rel="stylesheet">
	
<script>
$(document).ready(function()
{
	$('#kch_plant').live('click',function()
    {
        $("#content").load("budget_data.php",{plant:"kuching"});
    });
    
    $('#btu_plant').live('click',function()
    {
        $("#content").load("budget_data.php",{plant:"bintulu"});
    });
    
    $('#setting').live('click',function()
    {
        $("#content").load("admin_setting.php");
    });
});	
</script>
</head>

	
<body><!--<div id="your-dialog-id2">Your modal dialog</div><a href="#" id="open">Open dialog</a> -->

<div id="container">

<div id="header">
	<img id="" src="css/cms.gif" alt="" ><span>CMSB</span></img>
	<div>Welcome, <?php echo $_SESSION['name']; ?> 
    <a href="ldap_status.php?status=logout">Logout</a>
    <button id="setting">Setting</button></div>
</div>

<div id="mainc" style="min-height:95%">

		<div id='left-sidebar' style='display: block;'>
		<ul class='sidenav'>
		
		<li id='home' style='text-decoration: underline;'>
			<a href='index.php'><img id='' src='css/home.png' alt='' height='20' width='20'>Home
			</a>
		</li>
		
		<li class='mainlink' id='plant' style='text-decoration: underline;'>
			<a class='mainlink' href='#'><img id='' src='css/bia.png' alt='' height='20' width='20'>Budget Report By Plant
			<span>
				<ul class="sidenav2">
				<li id="kch_plant">Kuching Plant</li>
				<li id="btu_plant">Bintulu Plant</li>
				</ul>
			</span>
			</a>
		</li>
		
		<li class='mainlink' id='material' style='text-decoration: underline;'>
			<a class='mainlink' href='#'><img id='' src='css/brr.png' alt='' height='20' width='20'>Sales Report By Material
			<span>
				<ul class="sidenav2">
				<li id="sublink" onclick="window.location.href = 'material_weekly.php'">Weekly</li>
				<li onclick="window.location.href = 'material_month.php'">Monthly</li>
				<li onclick="window.location.href = 'material_year.php'">Yearly</li>
				</ul>
			</span>
			</a>
		</li>
		
		<li class='mainlink' id='so' style='text-decoration: underline;'>
			<a class='mainlink' href='#'><img id='' src='css/brr.png' alt='' height='20' width='20'>Sales Report By Office
			<span>
				<ul class="sidenav2">
				<li id="sublink" onclick="window.location.href = 'so_weekly.php'">Weekly</li>
				<li onclick="window.location.href = 'so_month.php'">Monthly</li>
				<li onclick="window.location.href = 'so_year.php'">Yearly</li>
				</ul>
			</span>
			</a>
		</li>
		
		<li class='mainlink' id='stock_lvl' style='text-decoration: underline;'>
			<a href='#'><img id='' src='css/brr.png' alt='' height='20' width='20'>Stock Level
			<span>
				<ul class="sidenav2">
				<li onclick="window.location.href = 'stock_main.php'">Material - Cement</li>
				<li onclick="window.location.href = 'clinker_main.php'">Material - Clinker</li>
				</ul>
			</span>
			</a>
		</li>
		</ul></div>
<div id="content" style="">

	<div style="width:80%;margin: 0px auto;"><p><font face="Simplified Arabic"><b><font size="4">Cement Sales Report</font></b><br>Sales reports and dashboards are very common in any company. It help to visualize sales data to understand the trends and sales performance.The key to keeping a sales team running efficiently is to make sure everyone stays on the same page. One of the ways a sales manager can make this happen is to have useful sales reports easily available.<br>&nbsp;</font></p></div>
				

</div>

</div>

<div id="footer" style="background-color:#3C444D;clear:both;text-align:center;"></div>

</div>

</body>
</html>