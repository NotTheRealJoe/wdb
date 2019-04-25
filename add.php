<?php
require_once('setup.php');
$headers = apache_request_headers();

foreach($headers as $name => $content) {
    if(strtolower($name) == 'accept') {
        $accept_header = strtolower($content);
        break;
    }
}
if(!$accept_header) {
    http_response_code(406);
    exit;
}

function output($success, $message = null) {
    global $accept_header;

    if(strpos($accept_header, 'text/html') !== false) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <title>wdb - Add</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        </head>
        <body>
            <p><?php echo $success ? 'Added successfully.' : $message ?></p>
            <p><a href="index.php">Return to home</a></p>
        </body>
        </html>
        <?php
    } elseif (strpos($accept_header, 'application/json') !== false) {
        header('Content-Type: application/json');
        echo "{\"success\": $success, \"message\": \"$message\"}";
    } elseif (strpos($accept_header, 'text/plain') !== false) {
        header('Content-Type: text/plain');
        echo $success ? 'true' : $message;
    } else {
        http_response_code(406);
    }
    exit;
}

$essid = filter_input(INPUT_POST, 'essid');
if($essid === '' || is_null($essid)) {
    output(false, 'ESSID is required');
}
$bssid_hex = str_replace(':', '', filter_input(INPUT_POST, 'bssid'));
$bssid = $bssid_hex !== '' ? $bssid_hex : null;
$latitude = floatval(filter_input(INPUT_POST, 'latitude'));
if($latitude === 0) $latitude = null;
$longitude = floatval(filter_input(INPUT_POST, 'longitude'));
if($longitude === 0) $longitude = null;
$location_description = filter_input(INPUT_POST, 'location_description');
if($location_description === '') $location_description = null;
$psk = filter_input(INPUT_POST, 'psk');
if($psk === '' || is_null($psk)) {
    output(false, 'PSK is required');
}
$admin_url = filter_input(INPUT_POST, 'admin_url');
if($admin_url === '') $admin_url = null;
$admin_username = filter_input(INPUT_POST, 'admin_username');
if($admin_username === '') $admin_username = null;
$admin_password = filter_input(INPUT_POST, 'admin_password');
if($admin_password === '') $admin_password = null;

if (count($db->query("SELECT true FROM networks WHERE essid=\"$essid\"")->fetchAll(PDO::FETCH_NUM)) > 0) {
    output(true, 'Already in database');
} else {
    $res = $db->query("INSERT INTO networks (essid,latitude,longitude,location_description,psk,admin_url,admin_username,admin_password) VALUES (\"$essid\",$latitude,$longitude,\"$location_description\",\"$psk\",\"$admin_url\",\"$admin_username\",\"$admin_password\")");
    if($res) {
        output(true);
    } else {
        //var_dump($db->errorInfo());
        output(false);
    }
}