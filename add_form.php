<?php
require("setup.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>wdb - Add</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<form method="post" action="add.php">
    <p>ESSID (network name): <input type="text" name="essid" /></p>
    <p>BSSID (base station MAC): <input type="text" name="bssid" /></p>
    <p>Latitude: <input type="text" name="latitude" /></p>
    <p>Longitude: <input type="text" name="longitude" /></p>
    <p>Location Description: <input type="text" name="location_description" /></p>
    <p>PSK: <input type="text" name="psk" /></p>
    <p>Admin URL: <input type="text" name="admin_url" /></p>
    <p>Admin username: <input type="text" name="admin_username" /></p>
    <p>Admin password: <input type="text" name="admin_password" /></p>
    <p><input type="submit" /></p>
</form>
</body>
</html>