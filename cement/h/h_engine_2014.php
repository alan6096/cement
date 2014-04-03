<?php
class menu 
{
    private $class;
    private $id;
 
    public function __construct($class)
    {
        $this->class = $class;
    }
    
    public function begin()
    {
        echo "<div class='mymenu'><div class='tabs'>";
    }
    
    public function tabsheader($id,$title,$ischecked)
    {
        $this->id = $id;
        echo "<div class='tab'>
           <input type='radio' id='$id' name='tab-group-1' $ischecked>
           <label for='$id'>$title</label><div class='content'>";
    }
    
    public function subtabs($id,$url,$title)
    {
        echo "<span class='myspan' id='$id' id2='$this->id'><a href='$url' style='text-decoration:none;color:#666'>$title</a></span>";
    }
    
    public function tabsfooter()
    {
        echo "</div></div>";
    }
    public function end()
    {
        echo "</div></div>";
    } 
}

function getFileWhereFunctionIs()
{   
        $isLoginfile = false;
        $file = debug_backtrace();
        foreach ($file as $arr)
        {           
                //echo "File is at " . $arr['file']; //debugging
                //We check if this file is called from login.php.   
                if (isset ($arr['file']))
                {
                        $pos = strpos($arr['file'], 'login.php');
                        if (!$pos === false) 
                        {
                            $isLoginfile = true;
                        }
                }
        }
        //echo $isLoginfile; //debugging
        //return $isLoginfile;


    if(($result = $isLoginfile) === false)
    {
        /*
        echo $result;
        echo '<b>Current logged in user : ';
        echo $_SESSION['USERNAME'];*/
        $current_time = date("H:i:s");
        echo "  <div id=\"divtable4health\" >
              <TABLE id=\"table4health\" BORDER=0 CELLPADDING=1 CELLSPACING=1 WIDTH=160 >
              <THEAD><TH style=\"background-color:#63BD69;color:#fff\" COLSPAN=3>System Status</TH></THEAD>";
                health();
        echo '</TABLE></div>';
        
        echo "  <div id=\"divtable4health\">
              <TABLE id=\"table4sysinfo\" BORDER=0 CELLPADDING=1 CELLSPACING=1 WIDTH=160>
              <THEAD><TH style=\"background-color:#6A8CCF;color:#fff\" COLSPAN=3>System Information</TH></THEAD>
                <tr>
                    <td>User:</td>
                    <td align=center>" , $_SESSION['USERNAME'] , "</td>
                </tr>
                <tr>
                    <td>Date: </td>
                    <td align=center>" . date('d-m-Y (D)') . "</td>
                </tr>
                <tr>
                    <td>Time: </td>
                    <td align=center><span id='clockhere' style='font-weight: bold;color:#000;'></span></td>
                </tr>
              ";
        echo '</TABLE></div>';
    
        
    }
}

function health()
{
/*******DATABASE*****************************************************************/  
  $no = "<SPAN CLASS=\"NO\" >&nbsp;NO&nbsp;</SPAN>";
  $yes  = "<SPAN  CLASS=\"YES\">&nbsp;YES&nbsp;</SPAN>";
  $junk = exec("ps ax | grep mysqld | grep -v grep",$output);
  if(count($output)>0) {
   $running = $yes;
   $procs = count($output) - 1 . " children";
  } else {
   $running = $no;
   $procs = count($output) . " proc(s)";
  }
  echo "     <TR><TD>Database:</TD><TD ALIGN=\"CENTER\">$running</TD><TD ALIGN=\"RIGHT\">$procs</TD></TR>\n";   

  /*******Sendmail*****************************************************************/ 
  $output = "";
  //$mta = get_conf_var('mta');
  $mta = 'sendmail';
  $junk = exec("ps ax | grep $mta | grep -v grep | grep -v php",$output);
  if(count($output)>0) {
   $running = $yes;
  } else {
   $running = $no;
  }
  $procs = count($output)." proc(s)";
  echo "    <TR><TD>".ucwords($mta).":</TD><TD ALIGN=\"CENTER\">$running</TD><TD ALIGN=\"RIGHT\">$procs</TD></TR>\n";

  /*******Load Average****************************************************************/
  if(file_exists("/proc/loadavg")) {
  $loadavg = file("/proc/loadavg");
  $loadavg = explode(" ", $loadavg[0]);
  $la_1m = $loadavg[0];
  $la_5m = $loadavg[1];
  $la_15m = $loadavg[2];
  echo "<TR><TD>Load Average:</TD><TD ALIGN=\"RIGHT\" COLSPAN=2><TABLE id='table4sysinfo' WIDTH=\"100%\" CELLPADDING=0 CELLSPACING=0><TR>
        <TD ALIGN=\"CENTER\">$la_1m</TD><TD ALIGN=\"CENTER\">$la_5m</TD><TD ALIGN=\"CENTER\">$la_15m</TD></TR></TABLE></TD></TR>";
  }
}

class form
{
    private $form_id;
	private $size;
    private $hostname;
    private $username;
    private $password;
    private $database;
    private $sql_param;
	private $id;
    
    public function __construct($form_id)
    {
        $this->form_id=$form_id;
		$this->size=3;
        $this->sql_param="";
        
        $hostname = "localhost";
        $username = "root";
        $password = "wmlevel5";
        $database = "helpdesk1";
        
        //$connection = mysql_connect("localhost", "root", "wmlevel5") OR die('Could not connect to MySQL: ' . mysql_error());
        //mysql_select_db("helpdesk1");
        
        //$connection = mysql_connect($hostname, $username, $password) OR die('Could not connect to MySQL: ' . mysql_error());
        //mysql_select_db($database);
        include "conf.php";
    }
    
    public function sql_param($sql_param,$id)
    {
        $this->sql_param = $sql_param;
		$this->id = $id;
    }
    
    public function dbconnect()
    {
        $hostname = "localhost";
        $username = "root";
        $password = "wmlevel5";
        $database = "helpdesk1";
        
        $connection = mysql_connect($hostname, $username, $password) OR die('Could not connect to MySQL: ' . mysql_error());
        mysql_select_db($database);
    }
    
    public function begin($class)
    {
        $form_id = $this->form_id;
        echo "<form id='$form_id' method='post'><table class='form_table' id='$class'><tbody>";
    }
    
    public function input_text($title,$id,$extra_param,$value,$size)
    {
    	if(!$size) $size = $this->size;
        //echo "<tr><th>$title:</th><td colspan='$size'><input id='$id' name='$id' type='text' value='$value'></td></tr>";
		
		if($extra_param=="hide")
			echo "<tr><td colspan='$size'><input id='$id' name='$id' type='hidden' style='display:none' value='$value'></td></tr>";
		
		elseif($extra_param=="disabled")
		{
			echo "<tr id='tr_$id'><th>$title:</th><td colspan='$size'><input disabled id='$id' name='$id' type='text' value='$value'><input id='$id' name='$id' type='hidden' value='$value'></td></tr>";
		}
		
		elseif($extra_param=="skipjson")
		{
			echo "<tr id='tr_$id'><th>$title:</th><td colspan='$size'><input disabled id='$id' name='$id' type='text' value='$value'><input id='$id' extra='skipjson' name='$id' type='hidden' value='$value'></td></tr>";
		}
		
		elseif($extra_param=="password")
		{
			echo "<tr id='tr_$id'><th>$title:</th><td colspan='$size'><input id='$id' name='$id' type='password' value='$value'></td></tr>";		
		}
		
		elseif($extra_param=="hide2")
		{
			echo "<tr id='tr_$id'><td colspan='$size'><input  extra='skipjson' id='$id' name='$id' type='hidden' value='$value'></td></tr>";	
		}
			
		else
			echo "<tr id='tr_$id'><th>$title:</th><td colspan='$size'><input id='$id' name='$id' type='text' value='$value'></td></tr>";
    }
	
	public function input_text_join($title,$id,$param,$style)
    {
        $param_array = explode("=", $param);
        
        $param_array_left = explode(".", $param_array[0]);
        $table1 = $param_array_left[0];
        $table1_field = $param_array_left[1];
        
        $param_array_right = explode(".", $param_array[1]);
        $table2 = $param_array_right[0];
        $table2_field = $param_array_right[1];
        
        $param_array_right2 = explode(".", $param_array[2]);
        $table3 = $param_array_right2[0];
        $table3_field = $param_array_right2[1];
        
        $all_select = "$table2.$table2_field";
        $all_table .= "$table1,$table2";
        $all_condition .= "$table1.$table1_field = $table2.id";
        
        if($table3)
        {
            $all_select = "$table3.$table3_field";
            $all_table .= ",$table3";
            $all_condition .= " AND $table2.$table2_field = $table3.id";
        }
        
/* ege: SELECT hd_company.name as resultname FROM h_ticket,hd_user,hd_company WHERE h_ticket.user_id = hd_user.id AND hd_user.company_id = hd_company.id AND h_ticket.id=3147 */        
/*      $sqlstr = "SELECT $table2.$table2_field as resultname FROM $table1,$table2 $table3_sql_table WHERE $table1.$table1_field = $table2.id $table3_sql_field AND $table1.id=$this->id"; */ 
        $sqlstr = "SELECT $all_select as resultname FROM $all_table WHERE $all_condition AND $table1.id=$this->id";
        $result = mysql_query($sqlstr) or die(mysql_error());   
        $row = mysql_fetch_array($result); //can work without loop i guess

        $value = $row["resultname"]; //no need "name as" also can
        
        if($style=="disabled")
			echo "<tr id='tr_$id'><th>$title:</th><td colspan='$size'><input extra='skipjson' id='$id' name='$id' type='text' value='$value' disabled></td></tr>";
		else
        	echo "<tr id='tr_$id'><th>$title:</th><td colspan='$size'><input extra='' id='$id' name='$id' type='text' value='$value'></td></tr>";
        
    }
    
    public function label_text($title,$id,$table)
    {
        $sql_param = $this->sql_param;
        $result = mysql_query("SELECT * FROM $table $sql_param") or die(mysql_error());
        while($row = mysql_fetch_array($result))
        {
            $value = $row[$id];
        }
        if(!$size) $size = $this->size;
        echo "<tr><th>$title:</th><td colspan='$size'><label id='$id' name='$id'>$value</label><input id='$id' name='$id' type='hidden' value='$value'></td></tr>";
    }
    
    public function select_option($form,$selected_id,$nozero,$sortby,$size,$nolabel)
    {
        if(!$size)$size="165";  //if no size specified, set default
        $html = explode(",",$form);
        $html_id=$html[0];
        $html_txt=$html[1];
        $html_content=$html[2];
        $table=$html[3];
        $custom_field_name=$html[4];
        
        $label = "<tr id='tr_$html_id'><th>$html_txt:</th>";
        
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
    
	public function getOptionValueByIDmanual($myarray,$nozero)
	{
		if($nozero!="nozero")
	    	$temp = '<option value=0></option>';
	    for($x=0;$x<count($myarray);$x++)
	    {
	        $temp = $temp . '<option value="' . $x . '">' . $myarray[$x]. '</option>';
	    }
	    echo $temp;
	}
		
    public function getOptionValueByID($table,$param,$custom_field_name,$selected_id,$nozero,$sortby)
    {
        $sql_param = "";
    
		//$this->dbconnect();
		
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
		
		if($nozero!="nozero")
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
	
	public function getjoinvalue($param)
    {
        $param_array = explode("=", $param);
        
        $param_array_left = explode(".", $param_array[0]);
        $table1 = $param_array_left[0];
        $table1_field = $param_array_left[1];
        
        $param_array_right = explode(".", $param_array[1]);
        $table2 = $param_array_right[0];
        $table2_field = $param_array_right[1];
        
        $sqlstr = "SELECT $table2.$table2_field as resultname FROM $table1,$table2 WHERE $table1.$table1_field = $table2.id AND $table1.id=$this->id";
        $result = mysql_query($sqlstr) or die(mysql_error());   
        $row = mysql_fetch_array($result); //can work without loop i guess

        return $row["resultname"]; //no need "name as" also can
        
        
    }
    
    public function end()
    {
        echo "</tbody></table></form>";
        //echo "</form>";
    }
}

class sql
{
    private $hostname;
    private $username;
    private $password;
    private $database;
    private $sql_param;
    private $id;
    
    public function __construct($sql_param,$id)
    {
        $this->sql_param=$sql_param;
        $this->id=$id;
        
        $hostname = "localhost";
        $username = "root";
        $password = "wmlevel5";
        $database = "helpdesk1";
        
        $connection = mysql_connect($hostname, $username, $password) OR die('Could not connect to MySQL: ' . mysql_error());
        mysql_select_db($database);
    }
    
    public function getvalue($fieldname,$table)
    {
        $sql_param = $this->sql_param;
        $result = mysql_query("SELECT * FROM $table $sql_param") or die(mysql_error());
        while($row = mysql_fetch_array($result))
        {
            $value = $row[$fieldname];
        }
        if(!$size) $size = $this->size;
        return $value;
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
               
        $temp = '
        <select id="'.$html_id.'" name="'.$html_id.'" style="width:'.$size.'px">'; 
            if($table=="")
                $temp .= $this->getOptionValueByIDmanual(explode("+",$html_content),$nozero);
            else 
                $temp .= $this->getOptionValueByID($table,$html_content,$custom_field_name,$selected_id,$nozero,$sortby); 
        $temp .=  '</select>';
        return $temp;
    }
    //h_ticket.assigned_id = h_user.name
    
    public function getjoinvalue($param)
    {
        $param_array = explode("=", $param);
        
        $param_array_left = explode(".", $param_array[0]);
        $table1 = $param_array_left[0];
        $table1_field = $param_array_left[1];
        
        $param_array_right = explode(".", $param_array[1]);
        $table2 = $param_array_right[0];
        $table2_field = $param_array_right[1];
        
        $sqlstr = "SELECT $table2.$table2_field as resultname FROM $table1,$table2 WHERE $table1.$table1_field = $table2.id AND $table1.id=$this->id";
        $result = mysql_query($sqlstr) or die(mysql_error());   
        $row = mysql_fetch_array($result); //can work without loop i guess

        return $row["resultname"]; //no need "name as" also can
        
        
    }
    public function getOptionValueByID($table,$param,$custom_field_name,$selected_id,$nozero,$sortby)
    {
        $sql_param = "";
    	
        $data=explode("&",$param);
        $totaldata=count($data);
        if($param=="")$totaldata=0;
        
        for($x=0;$x<$totaldata;$x++)
        {
            $filter=explode("=",$data[$x]);
            $filterBy=$filter[0];
            $filterValue=$filter[1];
            
            $rs->$filterBy=$filterValue;
            
            //if($x==0) //separate where & and clause
                //$sql_param = $sql_param . "where $filterBy='$filterValue'";
            //if($x>0)
                //$sql_param = $sql_param . " and $filterBy='$filterValue'";
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
}    

function unique_generatecode()
{
	$table = "h_ticket";
	$sql_param = "";
	$generate_code = generateCode();
	
	include 'conf.php';
	$result = mysql_query("SELECT * FROM $table") or die(mysql_error());
	while($row = mysql_fetch_array($result))
	{
		$track_id = $row['track_id'];
		if($generate_code==$track_id) $generate_code = generateCode();
	}
	return $generate_code;
}

function generateCode()
{
    $unique =   FALSE;
    $length =   9;
    $chrDb  =   array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9');

    while (!$unique){

          $str = '';
          for ($count = 0; $count < $length; $count++){

              $chr = $chrDb[rand(0,count($chrDb)-1)];

              //if (rand(0,1) == 0)
              {
                 //$chr = strtolower($chr);
              }
              if (3 == $count)
              {
                 $str .= '-';
              }
			  
			  if (6 == $count)
              {
                 $str .= '-';
              }
              $str .= $chr;
          }

          /* check if unique */
          //$existingCode = UNIQUE CHECK GOES HERE  
          if (!$existingCode){
             $unique = TRUE;
          }
    }
    return $str;
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
?>