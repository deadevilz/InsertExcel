<?php

require("config.php");
$sr = $_POST['sr'];
$SQL = "SELECT sr,'in','out',RCA_ID FROM table1 WHERE sr='".$sr."'";
//echo $SQL;
$query = mysql_query($SQL,$objConnect) or die("SQL Error");
if($row = mysql_fetch_assoc($query)=="")
{
	echo "SR NOT FOUND";
}
else
{
		echo "<table border='1'><tr>";
		for($i=0; $i<mysql_num_fields($query);$i++)
		{
			$field = mysql_fetch_field($query);
			echo "<td>{$field->name}</td>";
		}
		do
		{	 echo "<tr>";		
			 foreach($row as $cell)
					echo "<td>$cell</td>";
			 echo "</tr>\n";
		}while($row = mysql_fetch_assoc($query));

}


?>