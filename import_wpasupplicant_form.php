<?php
require('setup.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>wdb - wpa_supplicant.conf Import</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<p>Upload <code>/etc/wpa_supplicant.conf</code></p>
<form action="import_wpasupplicant.php" method="post" enctype="multipart/form-data">
    <p><input type="file" name="config_file" /></p>
    <p><input type="submit" value="submit" name="submit" /></p>
</form>
</body>
</html>