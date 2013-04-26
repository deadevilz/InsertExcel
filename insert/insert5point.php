<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
					 if((!checkSRDup($objArr,$objConnect,$countrow)&&checkSR($objArr[0])))
					 {		//$userid = selectUser($objArr[7],$objConnect);
							
							//user
							insertUser($objArr[7],$objConnect);
							insertUser($objArr[8],$objConnect);
							insertUser($objArr[9],$objConnect);

							//Check user
							$useridFLO = selectUser($objArr[7],$objConnect);
							$useridGW = selectUser($objArr[8],$objConnect);
							$useridPE = selectUser($objArr[9],$objConnect);

							//Check RCA
							$RCA_ID = CheckRCA($objArr[11],$objConnect,$countrow);
							
							//Check components
							$Compo_ID = CheckCompo($objArr[12],$objConnect,$countrow);

							//Insert Table1
							$date = new DateTime($objArr[1]);
							$sr_operate = $date->format('Y-m-d');
							$date2 = new DateTime($objArr[2]);
							$str_in = $date2->format('Y-m-d');	
							$outdate = new DateTime($objArr[3]);
							$strdate = $outdate->format('Y-m-d');
							checkString($objArr);
							$strSQL = "INSERT INTO table1";        
							//$strSQL .="(id,SR,SR_openDate,in,out,sev,client,account,FLO,GW,PE,summary,rca,components,rootcause,remark,customerHold)";
							$strSQL .=" VALUES ";  
							$strSQL .="('".$fields."',\"".$objArr[0]."\",'".$sr_operate."','".$str_in."' ";
							$strSQL .=",\"".$objArr[3]."\",'".$objArr[4]."',\"".$objArr[5]."\",\"".$objArr[6]."\",'".$useridFLO."','".$useridGW."' ";
							$strSQL .=",'".$useridPE."',\"".$objArr[10]."\",'".$RCA_ID."' ";
							$strSQL .=",'".$Compo_ID."',\"".$objArr[13]."\",\"".$objArr[14]."\",'".$objArr[15]."') ";
							mysql_query($strSQL,$objConnect);
							
					}
					 if(trim($objArr[3])=="")
					{
						$rcaid = checkRCA($objArr[11],$objConnect,$countrow);
						$compoid = checkCompo($objArr[12],$objConnect,$countrow);
						$date2 = new DateTime($objArr[2]);
						$str_in = $date2->format('Y-m-d');
						$updateSQL = "UPDATE table1 SET RCA_ID='".$rcaid."',Compo_ID='".$compoid."',rootcause='".$objArr[13]."',remark='".$objArr[14]."',customerHold='".$objArr[15]."' WHERE SR='".$objArr[0]."' AND `in`='".$str_in."'";
						//echo "<br>".$updateSQL;
						mysql_query($updateSQL,$objConnect) or die("SQL ERROR");
						
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
	  function checkSR ($SR)
	   {
			return preg_match('/^[0-9]-[0-9]{10}$/',$SR);
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
				echo "<br>".$maxuser."</br>";
				echo "<br>".$username."</br>";
				$inuserSQL = "INSERT INTO user (User_id,username) VALUES(".$maxuser.",'".$username."')";
				echo "<br>".$inuserSQL."</br>";
				mysql_query($inuserSQL,$objConnect) or die ("SQL error");
			}	
	   }
	function checkSRDup($objArr,$objConnect,$countrow)
	{
		$date = new DateTime($objArr[1]);
		$sr_operate = $date->format('Y-m-d');
		$date2 = new DateTime($objArr[2]);
		$str_in = $date2->format('Y-m-d');	
		$boo = false;$stop = false;
		$sqlid = "SELECT SR,SR_opendate,`in` as strin,RCA_Types,`out` as strout FROM table1,rca WHERE rca.rca_id=table1.rca_id";
		//echo $sqlid;
		$queryid = mysql_query($sqlid,$objConnect);
		/*while($rowid = mysql_fetch_assoc($queryid) and !$boo and !$stop)
		{	//echo $objArr[11];
			if($objArr[11]=="Re-assign")//rca เป็น re-assign
			{
				$boo=false;$stop=true;
			}
			else if($rowid['SR']==$objArr[0] && $sr_operate==$rowid['SR_opendate'] && ($str_in>=$rowid['strin']))//sr,in,out ซ้ำกันหมด Dup
			{	
				echo "<hr> <b> ROW ".$countrow."</b> sr ".$objArr[0]."  Is Duplicate </hr>";
				$boo = true;
			}
			/*else if($rowid['SR']==$objArr[0] && $objArr[3]!=$rowid['strout'] && $str_in!=$rowid['strin'])//sr เบอร์เดิม in out ต่างกัน
			{
				$boo = false;
			}*/
		}
		return $boo;
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
		$objArr[5] = strtr($objArr[5],"\"", "'");
		$objArr[6] = strtr($objArr[6],"\"", "'");
		$objArr[10] = strtr($objArr[10],"\"", "'");
		$objArr[13] = strtr($objArr[13],"\"", "'");
		$objArr[14] = strtr($objArr[14],"\"", "'");
	}
	function deleteFile($filename)
	{
		unlink($filename);
	}
?>
</body>
</html>
