<?php
require("setup.php");

$data = $db->query('SELECT * FROM networks WHERE essid="' . filter_input(INPUT_GET, 'essid') . '"')->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>wdb - Add</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <style>
        input[type=text] {
            width: 50vw;
        }
    </style>
</head>
<body>
<form method="post" action="update.php">
    <table>
        <tbody>
        <tr>
            <td>ESSID (network name):</td>
            <td>
                <input type="text" name="essid" value="<?php echo $data['essid']; ?>" readonly style="color: black; font-weight: bold" /><br />
            </td>
        </tr>
        <tr>
            <td>BSSID (base station MAC):</td>
            <td><input type="text" name="bssid" value="<?= bin2mac($data['bssid']) ?>"/></td>
        </tr>
        <tr>
            <td>Latitude:</td>
            <td><input type="text" name="latitude" value="<?= $data['latitude'] ?>" /></td>
        </tr>
        <tr>
            <td>Longitude:</td>
            <td><input type="text" name="longitude" value="<?= $data['longitude'] ?>" /></td>
        </tr>
        <tr>
            <td>Location Description: </td>
            <td><input type="text" name="location_description" value="<?= $data['location_description'] ?>" /></td>
        </tr>
        <tr>
            <td>PSK:</td>
            <td><input type="text" name="psk" value="<?= $data['psk'] ?>" /></td>
        </tr>
        <tr>
            <td>Admin URL:</td>
            <td><input type="text" name="admin_url" value="<?= $data['admin_url'] ?>" /></td>
        </tr>
        <tr>
            <td>Admin username:</td>
            <td><input type="text" name="admin_username" value="<?= $data['admin_username'] ?>" /></td>
        </tr>
        <tr>
            <td>Admin password:</td>
            <td><input type="text" name="admin_password" value="<?= $data['admin_password'] ?>" /></td>
        </tr>
        </tbody>
    </table>
    <p><input type="submit" /></p>
</form>
</body>
</html>