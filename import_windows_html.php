<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>wdb - Android Import</title>
</head>
<body>
<?php
require_once('setup.php');

if($sxml = simplexml_load_file($_FILES['config_file']['tmp_name'])) {

    if($sxml->MSM->security->sharedKey->keyType == "passPhrase") {
        $net_name = $sxml->SSIDConfig->SSID->name;
        $net_psk = $sxml->MSM->security->sharedKey->keyMaterial;

        if (count($db->query("SELECT true FROM networks WHERE essid=\"$net_name\"")->fetchAll(PDO::FETCH_NUM)) > 0) {
            $res = '<span class="neutral">Already in database</span>';
        } else {
            $db->query("INSERT INTO networks (essid,psk) VALUES (\"$net_name\",\"$net_psk\")");
            $res = '<span class="good">Added</span>';
        }

        echo "<p><b>SSID:</b> $net_name</p><p><b>PSK:</b> $net_psk</p><p><b>Status:</b> $res</p>";
    } else {
        echo '<p>This network does not appear to use a PSK</p><p><a href="index.php">Return to list</a></p>';
    }
} else {
    echo '<p>' . $_FILES['android_config_file']['name'] . ' does not appear to be a valid XML file</p>';
}
?>
</body>
</html>
