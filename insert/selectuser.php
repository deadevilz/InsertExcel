<?php
	require("config.php");
	showuser($objConnect);
	function showuser($objConnect)
	{
		$SQL = "SELECT * FROM user";
		$query = mysql_query($SQL,$objConnect);
		echo "<table border='1'><tr>";
		for($i=0;$i<mysql_num_fields($query);$i++)
		{
			$field = mysql_fetch_field($query);
			echo "<td>{$field->name}</td>";
		}
		while($row = mysql_fetch_assoc($query))
		{
			echo "<tr>";
			foreach($row as $cell)
				echo "<td>$cell</td>";
			echo "</tr>";
		}

	}


?>