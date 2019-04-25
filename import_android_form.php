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
<p>Upload <code>/data/misc/wifi/WifiConfigStore.xml</code> from an Android device</p>
<form action="import_android.php" method="post" enctype="multipart/form-data">
    <p><input type="file" name="android_config_file" /></p>
    <p><input type="checkbox" name="include_open" value="yes" /><label>Include open networks</label><br /></p>
    <p><input type="submit" value="submit" name="submit" /></p>
</form>
</body>
</html>