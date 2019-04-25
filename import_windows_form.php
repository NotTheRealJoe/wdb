<?php
require('setup.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>wdb - Android Import</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<p>Upload a wifi config xml file from Windows</p>
<form action="import_windows.php" method="post" enctype="multipart/form-data">
    <p><input type="file" name="config_file" /></p>
    <p><input type="submit" value="submit" name="submit" /></p>
</form>
</body>
</html>