<?php
  
    require("config.php");
	showTable($objConnect);
	function showTable($objConnect)
	{
		$sql = "SELECT * FROM table1";
		$query = mysql_query($sql,$objConnect);
		echo "<table border='1'><tr>";
		for($i=0; $i<mysql_num_fields($query);$i++)
		{
			$field = mysql_fetch_field($query);
			echo "<td>{$field->name}</td>";
		}
		while($row = mysql_fetch_assoc($query))
		{	 echo "<tr>";		
			 foreach($row as $cell)
			 {	if($cell=="0000-00-00")
					$cell=" ";
				echo "<td>$cell</td>";
			 }
			 echo "</tr>\n";
		}
	}
?>