<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insert Website</title>
</head>

<body>
<?php
	
	require("config.php");
	if(isset($_POST['submit']))
	   {	if($_FILES['file']['type']=="application/vnd.ms-excel")
		   {	//echo 'fileupload/'.$_FILES['file']['name'];
				//echo $_FILES['file']['type'];
				//move_uploaded_file($_FILES['file']['tmp_name'],'fileupload2/'.($_FILES['file']['name']));
				move_uploaded_file($_FILES['file']['tmp_name'],($_FILES['file']['name']));
				$filename = $_FILES['file']['name'];
				$objCSV = fopen("$filename","r");
				$objArr = fgetcsv($objCSV, 1000, ",");
				
				$countrow=0;
				do	
				{    $countrow++;
					 $fields = selectMaxID($objConnect);
					 if((checkSRDup($objArr,$objConnect,$countrow)&&checkSR($objArr[0])))
					 {		//$userid = selectUser($objArr[7],$objConnect);
							
							//user
							insertUser($objArr[8],$objConnect);
							insertUser($objArr[9],$objConnect);
							insertUser($objArr[10],$objConnect);

							insertUser2($objArr[8],$objConnect);
							insertUser2($objArr[9],$objConnect);

							//Check user
							$useridFLO = selectUser($objArr[8],$objConnect);
							$useridGW = selectUser($objArr[9],$objConnect);
							$useridPE = selectUser($objArr[10],$objConnect);

							//Check RCA
							$RCA_ID = CheckRCA($objArr[12],$objConnect,$countrow);
							
							//Check components
							$Compo_ID = CheckCompo($objArr[13],$objConnect,$countrow);

							//Insert Table1
							$sr_operate = StrToDate($objArr[1]);
							$str_in = StrToDate($objArr[2]);
							$strdate = StrToDate($objArr[3]);
							checkString($objArr);
							$strSQL = "INSERT INTO table1";        
							//$strSQL .="(id,SR,SR_openDate,in,out,sev,client,account,FLO,GW,PE,summary,rca,components,rootcause,remark,customerHold)";
							$strSQL .=" VALUES ";  
							$strSQL .="('".$fields."',\"".trim($objArr[0])."\",'".$sr_operate."','".$str_in."' ";
							$strSQL .=",'".$strdate."',\"".$objArr[4]."\",\"".$objArr[5]."\",\"".$objArr[6]."\",\"".$objArr[7]."\",\"".$useridFLO."\",\"".$useridGW."\"";
							$strSQL .=",'".$useridPE."',\"".$objArr[11]."\",'".$RCA_ID."' ";
							$strSQL .=",'".$Compo_ID."',\"".$objArr[14]."\",\"".$objArr[15]."\",\"".$objArr[16]."\") ";
							//echo $strSQL;
							mysql_query($strSQL,$objConnect)or die("SQL ERROR INSERT");
							echo "<hr><b>ROW ".$countrow."</b>Add Record SUCCESS";
					}
					 else if((!checkSRDup($objArr,$objConnect,$countrow)&&checkSR($objArr[0])))
					{
						$useridFLO = selectUser($objArr[8],$objConnect,$countrow);
						$useridGW = selectUser($objArr[9],$objConnect,$countrow);
						$useridPE = selectUser($objArr[10],$objConnect,$countrow);
						$rcaid = checkRCA($objArr[12],$objConnect,$countrow);
						$compoid = checkCompo($objArr[13],$objConnect,$countrow);
						$Sr_OpeDate = StrToDate($objArr[1]);
						$dateout = StrToDate($objArr[3]);
						$datein = StrToDate($objArr[2]);
						checkString($objArr);
						//echo "<br>".$dateout."<br>";
						$updateSQL = "UPDATE table1 SET SR_openDate=\"".$Sr_OpeDate."\" ,SR_out=\"".$dateout."\" , SLA=\"".$objArr[4]."\",sev=\"".$objArr[5]."\" ,client=\"".$objArr[6]."\",account=\"".$objArr[7]."\",";
						$updateSQL.= "FLO=\"".$useridFLO."\" ,GW=\"".$useridGW."\" ,PE=\"".$useridPE."\" ,summary=\"".$objArr[11]."\" ,";
						$updateSQL.= "RCA_ID=\"".$rcaid."\",Compo_ID=\"".$compoid."\",rootcause=\"".$objArr[14]."\",remark=\"".$objArr[15]."\",customerHold=\"".$objArr[16]."\" WHERE SR=\"".$objArr[0]."\" AND SR_in=\"".$datein."\" AND PE=\"".$useridPE."\"";
						
						//echo "<br>".$updateSQL."<br>";
						mysql_query($updateSQL,$objConnect) or die("SQL ERROR UPDATE");
						echo "<hr><b>ROW ".$countrow."</hr></b> UPDATE SUCCESS";
					}
					
				}while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE);
				fclose($objCSV);
				deleteFile($filename);
				//ShowTable($objConnect);
		   }
		   else
		   {
				echo "File not type .CSV";
		   }
	   }
	   function StrToDate($Str)
	   {	
	   		if(empty($Str))
				$Str="0000-00-00";
	   		$date = new DateTime($Str);
			$dateFormat = $date->format('Y-m-d');
			return $dateFormat;

	   }
	   function checkSR ($SR)
	   {
			return preg_match('/^[0-9]-[0-9]{10}$/',trim($SR));
	   }
	   function selectUser($username,$objConnect)
	   {
			$selectuser = "SELECT User_id FROM user WHERE username ='".$username."'";
			$useridquery = mysql_query($selectuser,$objConnect);
			$userid = mysql_fetch_assoc($useridquery);
			return $userid['User_id'];
	   }
	   function selectMaxUser($objConnect)
	   {
			$selectmaxuser = "SELECT Max(User_id) as max FROM user";
			$maxuserquery = mysql_query($selectmaxuser,$objConnect);
			$maxuser = mysql_fetch_assoc($maxuserquery);
			return $maxuser['max'];
	   }
	   function insertUser($username,$objConnect)
	   {
			if(selectUser($username,$objConnect)=="")
			{	$maxuser = 0;
				$maxuser = selectMaxUser($objConnect)+1;
				//echo "<br>".$maxuser."</br>";
				//echo "<br>".$username."</br>";
				$inuserSQL = "INSERT INTO user (User_id,username) VALUES(".$maxuser.",'".$username."')";
				//echo "<br>".$inuserSQL."</br>";
				mysql_query($inuserSQL,$objConnect) or die ("SQL error");
			}	
	   }
	   function insertUser2($username,$objConnect)
	   {
			if(selectUser2($username,$objConnect)=="")
			{	$maxuser = 0;
				$maxuser = selectMaxUser2($objConnect)+1;
				//echo "<br>".$maxuser."</br>";
				//echo "<br>".$username."</br>";
				$inuserSQL = "INSERT INTO user2 (User_id,username) VALUES(".$maxuser.",'".$username."')";
				//echo "<br>".$inuserSQL."</br>";
				mysql_query($inuserSQL,$objConnect) or die ("SQL error");
			}	
	   }
	function checkSRDup($objArr,$objConnect,$countrow)
	{
		$date = new DateTime($objArr[1]);
		$sr_operate = $date->format('Y-m-d');
		$date2 = new DateTime($objArr[2]);
		$str_in = $date2->format('Y-m-d');	
		$sql = "SELECT * FROM table1 WHERE SR='".trim($objArr[0])."' AND SR_in ='".$str_in."' AND PE=(SELECT User_id FROM user WHERE username ='".$objArr[10]."')";
		//echo $sql;
		$query = mysql_query($sql,$objConnect) or die("SQL ERROR");
		$rowid = mysql_fetch_assoc($query);
		if($rowid=="")
			return true;
		else
			return false;
	}
	/*function checkSRDup2($objArr,$objConnect)
	{
		$sqlid = "SELECT SR,SR_opendate,`in` as strin FROM table1";
		$queryin = mysql_query($selinsql,$objConnect)
		while($rowin = mysql_fetch_assoc($queryin))
		{
			$date = new DateTime($rowin['srtin']);
			$format = $date->format('Y-m-d');
		}

	}*/
	function selectMaxid($objConnect)
	{
		$sqlc = "SELECT MAX(id) as totalno FROM table1";
		$count = mysql_query($sqlc,$objConnect);
		$field = mysql_fetch_assoc($count);
		$fields = $field['totalno']+1;
		return $fields;
	}
	function checkRCA($RCA_Types,$objConnect,$countrow)
	{
		$selectRCA = "SELECT RCA_ID FROM rca WHERE RCA_Types = '".$RCA_Types."'";
		$queryRCA = mysql_query($selectRCA,$objConnect);
		$rowRCA = mysql_fetch_assoc($queryRCA);
		$flag = true;
		if($rowRCA=="")
				echo "<hr><b> ROW ".$countrow."</b> ".$RCA_Types."   RCA NOT FOUND </hr>";
		return $rowRCA['RCA_ID'];
	}
	function CheckCompo($Compo_Types,$objConnect,$countrow)
	{
		$selectcompo = "SELECT Compo_ID FROM components WHERE Compo_types = '".$Compo_Types."'";
		$querycompo = mysql_query($selectcompo,$objConnect);
		$rowcompo = mysql_fetch_assoc($querycompo);
		if($rowcompo=="")
				echo "<hr><b> ROW ".$countrow."</b> ".$Compo_Types."  Components IS NOT FOUND </hr>";
		return $rowcompo['Compo_ID'];
		
	}
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
					echo "<td>$cell</td>";
			 echo "</tr>\n";
		}
	}
	function checkString(&$objArr)
	{   
		$objArr[4] = str_replace("\"", "'",$objArr[4]);
		$objArr[5] = str_replace("\"", "'",$objArr[5]);
		$objArr[6] = str_replace("\"", "'",$objArr[6]);
		$objArr[10] = str_replace("\"", "'",$objArr[10]);
		$objArr[11] = str_replace("\"", "'",$objArr[11]);
		$objArr[13] = str_replace("\"","'",$objArr[13]);
		$objArr[14] = str_replace("\"", "'",$objArr[14]);
	}
	function deleteFile($filename)
	{
		unlink($filename);
	}
	function selectUser2($username,$objConnect)
	{
		$selectuser = "SELECT User_id FROM user2 WHERE username ='".$username."'";
		$useridquery = mysql_query($selectuser,$objConnect);
		$userid = mysql_fetch_assoc($useridquery);
		return $userid['User_id'];
	}
	function selectMaxUser2($objConnect)
	{
		$selectmaxuser = "SELECT Max(User_id) as max FROM user2";
		$maxuserquery = mysql_query($selectmaxuser,$objConnect);
		$maxuser = mysql_fetch_assoc($maxuserquery);
		return $maxuser['max'];
	}
?>
</body>
</html>
