<?php
class sksql
{
	private $table;
	private $sql_param;
	private $result;
	private $row;
	private $query;
	private $whereadd;
    public $id;
	private $temp_array = array();
	private $orderby;
	private $select_sql;
	private $fullquery;
	
	/* for sql_insert part */
	private $insert_array;
	/* end here */
	private $update_array;
	
	/*
	public function __construct($table,$sql_param)
	{
		$this->table = $table;
		$this->sql_param = $sql_param;
		$this->whereadd = $sql_param;

		include 'conf.php';
		
		if($sql_param<>"")
			$this->sql_param = "where $this->sql_param";
		
		$this->query = "SELECT * FROM $this->table $this->sql_param";
		echo $this->query;
		$this->result  = mysql_query($this->query) or die(mysql_error());
	}
	 */
	
	public function __construct($table)
	{
		$this->table = $table;
		$this->select_sql = "SELECT * FROM $this->table";
		include 'conf.php';
	}
	
	public function updateadd($sql_param)
	{
		$this->update_array[] = $sql_param;
	}
	
	public function update()
	{
		for ($i=0; $i < count($this->whereadd_array); $i++) 
		{
			$content = $this->whereadd_array[$i];
			if($i==0)
				$where_add_sql = "where $content";
			else
				$where_add_sql .= " and $content";
		}
		
		for ($i=0; $i < count($this->update_array); $i++) 
		{
			$content[$i] = $this->update_array[$i];
		}
		
		echo $content[0];

		$this->query = "UPDATE $this->table SET ".implode(",", $this->update_array)." $where_add_sql";
		
		echo $this->query;
		if(strlen($this->fullquery)>0)
			$this->query = $this->fullquery;
			
		mysql_query($this->query) or die(mysql_error());
	}
	
	public function delete()
	{		
		$this->query = "DELETE FROM $this->table WHERE id=$this->id";
		
		if(strlen($this->fullquery)>0)
			$this->query = $this->fullquery;
			
		mysql_query($this->query) or die(mysql_error());
	}
	
	public function insertadd($sql_param)
	{
		$this->insert_array[] = $sql_param;
	}
	
	public function insert()
	{
		for ($i=0; $i < count($this->insert_array); $i++) 
		{
			$content = $this->insert_array[$i];
			$content_left = explode("=", $content); /* extract name=mike */
			$field_name[] = $content_left[0]; /* extract name only */
			$values[] = "'" .$content_left[1]. "'"; /* extract mike only */
		}

		$this->query = "INSERT INTO $this->table (".implode(",", $field_name).") VALUES (".implode(",", $values).")";
				
		if(strlen($this->fullquery)>0)
			$this->query = $this->fullquery;
			
		mysql_query($this->query) or die(mysql_error());
		//echo implode(",", $field_name);
	}
	
	public function fullquery($sql_param)
	{
		$this->fullquery = $sql_param;//$this->aa.= "$sql_param";
		//echo $this->aa;echo implode("&", $this->temp_array);
		
	}
	
	public function whereadd($sql_param)
	{
		$this->whereadd_array[] = $sql_param;//$this->aa.= "$sql_param";
		//echo $this->aa;echo implode("&", $this->temp_array);
		
	}
	
	public function orderby($sql_param)
	{
		$this->orderby= "order by $sql_param";
		//$this->query = "SELECT * FROM $this->table $this->sql_param order by $orderby";
		//$this->result  = mysql_query($this->query) or die(mysql_error());
	}
	
	public function getlink($coltarget,$join_table,$join_col)//getLink('status_id', 'hd_status', 'id');	
	{
		$join_query = "SELECT * FROM $this->table,$join_table WHERE $this->table.$coltarget = $join_table.id AND $this->table.$join_col='$this->id'";
		//echo $join_query ." ~ ";
		$join_result  = mysql_query($join_query) or die(mysql_error());
		
		$row = mysql_fetch_object($join_result);
        {
            //return $row['resultname'];
			//unset($row['resultname']);
			return $row;
        }
	}
	
	public function find()
	{
		for ($i=0; $i < count($this->whereadd_array); $i++) 
		{
			$content = $this->whereadd_array[$i];
			if($i==0)
				$where_add_sql = "where $content";
			else
				$where_add_sql .= " and $content";
		}
		$this->query = "$this->select_sql $where_add_sql $this->orderby"; //echo $this->query ."<br>";
		
		if(strlen($this->fullquery)>0)
			$this->query = $this->fullquery;
			
		$this->result  = mysql_query($this->query) or die(mysql_error());
	}
	
	public function fetch()
	{		
		return (mysql_fetch_object($this->result));
	}
}
?>