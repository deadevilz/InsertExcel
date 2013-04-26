 <?php
  
   require("config.php");
   //echo $_POST["user"];
   insertCompo($_POST["inputcompo"],$objConnect);
   function selectCompo($Compo_Types,$objConnect)
   {
		$selectcompo = "SELECT Compo_ID FROM components WHERE Compo_Types = '".$Compo_Types."'";
		$compoquery = mysql_query($selectcompo,$objConnect)or die ("SQL error2");
		//echo $selectcompo;
		$rowcompo = mysql_fetch_assoc($compoquery);
		return $rowcompo;
   }
  
   function insertCompo($Compo_Types,$objConnect)
   {
		if(selectCompo($Compo_Types,$objConnect)=="")
		{	$maxcompo = 0;
			$maxcompo = selectMaxCompoid($objConnect)+1;
			//echo "<br>".$maxcompo."</br>";
			//echo "<br>".$Compo_Types."</br>";
			$incompoSQL = "INSERT INTO components (Compo_ID,Compo_Types) VALUES(".$maxcompo.",'".$Compo_Types."')";
			//echo "<br>".$inuserSQL."</br>";
			mysql_query($incompoSQL,$objConnect) or die ("SQL error2");
			echo "Insert Components Types ".$Compo_Types." Completed";
		}	
		else
		{
			echo "Components types ".$Compo_Types." is duplicate";
		}
	}
	function selectMaxCompoid($objConnect)
	{
		$maxcompoSQL = "SELECT MAX(Compo_ID) as maxid FROM components";
		$maxquery = mysql_query($maxcompoSQL,$objConnect)or die ("SQL error2");
		$maxcompo = mysql_fetch_assoc($maxquery);
		return $maxcompo['maxid'];
	}
	
?>