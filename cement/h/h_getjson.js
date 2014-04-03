	function getjsonvalue(param1,param2,jointable)
    {
//alert(param1);
		var myarray = param1.split("&");//split to array by &
		var temp = myarray[2].split("=");//split tb_field=val1 ege:temp[0]=tb_field & temp[1]=val1
		var fieldname = temp[1].split(",");//split val1 by ,
		
	  	$.getJSON("h/h_read_json.php?"+param1, function(data) 
		{
			for(x in fieldname)
			{
				//$("#"+param2+fieldname[x]).val(data[fieldname[x]]);
				//$("#"+fieldname[x]).val(data[fieldname[x]]);
				//$("#"+param2).find("#"+fieldname[x]).text(data[fieldname[x]]);
				$("#"+param2).find("#"+fieldname[x]+"[extra='as_text']").text(data[fieldname[x]]);
				$("#"+param2).find("#"+fieldname[x]+"[extra!='skipjson']").val(data[fieldname[x]]);
				//if($("#"+fieldname[x]).is( "label" ))
				{
					//$("#"+param2).find("#"+fieldname[x]).text(data[fieldname[x]]);
				}
			}
 		});
    }
    
    function getjsontext(param1,param2,jointable)
    {
		var myarray = param1.split("&");//split to array by &
		var temp = myarray[2].split("=");//split tb_field=val1 ege:temp[0]=tb_field & temp[1]=val1
		var fieldname = temp[1].split(",");//split val1 by , 

		var joinarray = jointable.split(",")
		//alert(joinarray[0]);
		
	  	$.getJSON("h/h_read_json.php?"+param1,{join:joinarray}, function(data) 
		{
			for(x in fieldname)
			{
				$("#"+param2).find("#"+fieldname[x]).text(data[fieldname[x]]);
			}
 		});
    }
	
	
	function getjsonvalue2(param1,param2,param3)
    {
//alert(param1);
		var myarray = param1.split("&");//split to array by &
		var temp = myarray[2].split("=");//split tb_field=val1 ege:temp[0]=tb_field & temp[1]=val1
		var fieldname = temp[1].split(",");//split val1 by , 

	  	$.getJSON("h/h_read_json.php?"+param1, function(data) 
		{
			if(param2=="" || !param2){for (x in fieldname){$("#"+fieldname[x]).val(data[fieldname[x]]);}}
	  		
	  		if(param2!=""){for(var x=0;x<fieldname.length-1;x++){$("#"+fieldname[x]).val(data[fieldname[x]]);}} 	
	  		
	  		if(param2!="")
	  		{
		  		$.getJSON("h/h_read_json.php?"+param2,{id: data[param3]}, function(data) 
				{
		   			$("#"+param3).val(data.name);
		 		});	
		 	}	
 		});
    }
    
	function getjsontext22(param1,param2,param3)
    {
//alert(param1);
		var myarray = param1.split("&");//split to array by &
		var temp = myarray[2].split("=");//split tb_field=val1 ege:temp[0]=tb_field & temp[1]=val1
		var fieldname = temp[1].split(",");//split val1 by , 

	  	$.getJSON("h/h_read_json.php?"+param1, function(data) 
		{
			if(param2=="" || !param2){for (x in fieldname){$("#"+fieldname[x]).text(data[fieldname[x]]);}}
	  		
	  		if(param2!=""){for(var x=0;x<fieldname.length-1;x++){$("#"+fieldname[x]).text(data[fieldname[x]]);}} 	
	  		
	  		if(param2!="")
	  		{
		  		$.getJSON("h/h_read_json.php?"+param2,{id: data[param3]}, function(data) 
				{
		   			$("#"+param3).text(data.name);
		 		});	
		 	}	
 		});
    }
    
	function clearformvalue(param1,param2)
    {
		var fieldname = param1.split(",");//split val1 by , 

		for(x in fieldname)
		{
			$("#"+param2).find("#"+fieldname[x]).val("");
		}
    }
    
	function clearformvalue2(param1)
    {
		var fieldname = param1.split(",");//split val1 by , 

		for (x in fieldname){$("#"+fieldname[x]).val("");}
    }