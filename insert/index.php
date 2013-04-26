<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Index Website</title>
<link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
  
    
    <fieldset class="fieldset">
        	<legend>Upload</legend>
      <form action = "insert.php" method = "post"
        enctype="multipart/form-data" name="form1" id="form1">
        <p>File1:
           <input name="file" type="file" id="file" accept=".csv"/>

        
     
        <p/>
        
       
    	<input class="submit" type="submit" name="submit" value="upload" />
        </form> 
    </fieldset class="fieldset">
    <fieldset class="fieldset">
      <legend>Select Table1</legend>
      <form action = "selecttable1.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
      	<p> CLICK FOR SELECT TABLE1 </p>
      	<input class="submit" type="submit" name="selecttable1" value="SELECT" />
      	</form>
    </fieldset >
  


    <fieldset class="fieldset">
      <legend>Select User</legend>
      <form action="selectuser.php"  method="post" enctype="multipart/form-data" name="form1" id="form1">
      	<p> CLICK FOR SELECT USER </p>
      	<input class="submit" type="submit" name="selectuser" value="SELECT" />
      </form>
    </fieldset>

    <fieldset class="fieldset">
      <legend>Select RCA</legend>
      <form action="selectrca.php" = "" method="post" enctype="multipart/form-data" name="form1" id="form1">
      	<p> CLICK FOR SELECT RCA </p>
      	<input class="submit" type="submit" name="selectuser" value="SELECT" />
      </form>
    </fieldset>

  
    <fieldset class="fieldset">
      <legend>Select Components</legend>
      <form action="selectcompo.php" = "" method="post" enctype="multipart/form-data" name="form1" id="form1">
      	<p> CLICK FOR SELECT COMPONENTS </p>
      	<input class="submit" type="submit" name="selectuser" value="SELECT" />
      </form>
    </fieldset>
    <fieldset class="fieldset">
      <legend>Insert RCA</legend>
      <form action = "addtodbrca.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
      	<p> INPUT RCA_TYPE </p>
      	<p><input type="text" name="inputrca" value="rca_type" /></p>
      	<p><input class="submit" type="submit" name="insertrca" value="INSERT" /></p>
      	</form>
    </fieldset>
  

    <fieldset class="fieldset">
      <legend>Insert Components</legend>
      <form action = "addtodb.php" method = "post" enctype="multipart/form-data" name="form1" id="form1">
      	<p> INPUT Components_Types </p>
      	<p><input type="text" name="inputcompo" value="components_type" /></p>
      	<p><input class="submit" type="submit" name="insertcomponents" value="INSERT" /></p>
      </form>
    </fieldset>
  
</body>
</html>
