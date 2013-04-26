<?php
 
   require("config.php");
    //echo $_POST["user"];
   insertRCA($_POST["inputrca"],$objConnect);
   
   //RCA
	function selectRCA($RCA_Types,$objConnect)
   {
		$selectrca = "SELECT RCA_ID FROM rca WHERE RCA_Types = '".$RCA_Types."'";
		$rcaquery = mysql_query($selectrca,$objConnect)or die ("SQL error3");
		//echo $selectrca;
		$rowrca = mysql_fetch_assoc($rcaquery);
		return $rowrca;
   }

	function insertRCA($RCA_Types,$objConnect)
	{ if (selectRCA($RCA_Types,$objConnect)=="")
		{	 $maxrca = 0;
			 $maxrca = selectMaxRCA($objConnect)+1;
			 $inrcaSQL = "INSERT INTO RCA (RCA_ID,RCA_Types) VALUES(".$maxrca.",'".$RCA_Types."')";
			 mysql_query($inrcaSQL,$objConnect) or die ("SQL error3");
			 echo "Insert RCA Types".$RCA_Types." is Completed";
	
		}
	  else
		{
			echo "RCA types".$RCA_Types." is duplicate";
		}
	}

	function selectMaxRCA($objConnect)
	{
		$maxrcaSQL = "SELECT MAX(RCA_ID) as maxid FROM rca";
		$maxquery = mysql_query($maxrcaSQL,$objConnect)or die ("SQL error3");
		$maxrca = mysql_fetch_assoc($maxquery);
		return $maxrca['maxid'];
	}

?>