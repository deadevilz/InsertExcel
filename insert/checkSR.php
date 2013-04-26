<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
	  if(checkSR("1-8189137881"))
	  {
			echo "true";
	  }
	  else
	  {
			echo "false";
	  }
	  
	  function checkSR ($SR)
	   {
			return preg_match('/^[0-9]-[0-9]{10}$/',"1-8298044838");
	   }
	 
?>
</body>
</html>