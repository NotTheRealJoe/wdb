<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>wdb - Android Import</title>
</head>
<body>
<?php
require_once('setup.php');

if($sxml = simplexml_load_file($_FILES['android_config_file']['tmp_name'])) {
    $nets = [];

    foreach($sxml->NetworkList->Network as $network) {
        $ssid = NULL;
        $psk = NULL;

        foreach ($network->WifiConfiguration->string as $s) {
            if ($s->attributes()['name'] == 'SSID') {
                $ssid = substr($s, 1, strlen($s) - 2);
            }

            if ($s->attributes()['name'] == 'PreSharedKey') {
                $psk = substr($s, 1, strlen($s) - 2);
            }

            if ($ssid && $psk) break;
        }
        if(($ssid && $psk) || ($ssid && $_POST['include_open'] == 'yes')) {
            $nets[$ssid] = $psk;
        }
    }

    ksort($nets);

    echo '<p>Here are the wifi networks extracted from your config:</p><table><thead><td>SSID</td><td>Key</td><td>Database status</td></thead><tbody>';
    foreach($nets as $net_name => $net_psk) {
        if (count($db->query("SELECT true FROM networks WHERE essid=\"$net_name\"")->fetchAll(PDO::FETCH_NUM)) > 0) {
            $res = '<span class="neutral">Already in database</span>';
        } else {
            $db->query("INSERT INTO networks (essid,psk) VALUES (\"$net_name\",\"$net_psk\")");
            $res = '<span class="good">Added</span>';
        }

        echo "<tr><td>$net_name</td><td>$net_psk</td><td>$res</td></tr>";
    }
    echo '</tbody></table><p><a href="index.php">Return to list</a></p>';

} else {
    echo '<p>' . $_FILES['android_config_file']['name'] . ' does not appear to be a valid XML file</p>';
}
?>
</body>
</html>
