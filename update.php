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
        <html lang="en">
        <head>
        <title>wdb - Add</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        </head>
        <body>
            <p><?php echo $success ? 'Updated successfully.' : $message ?></p>
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

function sqlFormat($data, $float = false, $hex = false) {
    if($data === '' || $data === null) {
        return 'NULL';
    } elseif($float) {
        $f = floatvalOrNull($data);
        return is_null($f) ? 'NULL' : $f;
    } elseif($hex) {
        return "unhex(\"$data\")";
    } else {
        return "\"$data\"";
    }
}

function postSql($name, $float = false) {
    return sqlFormat(filter_input(INPUT_POST, $name), $float);
}


if (intval($db->query('SELECT count(true) FROM networks WHERE essid='.postSql('essid'))->fetchAll(PDO::FETCH_NUM)[0][0]) < 1) {
    output(false, 'ESSID not found in database');
} else {
    $res = $db->query('UPDATE networks SET latitude='.postSql('latitude', true).',longitude='.postSql('longitude', true).',location_description='.postSql('location_description').', psk='.postSql('psk').',admin_url='.postSql('admin_url').',admin_username='.postSql('admin_username').',admin_password='.postSql('admin_password').',bssid='.postSql('bssid', $hex=true).' WHERE essid='.postSql('essid'));
    if($res) {
        output(true);
    } else {
        //var_dump($db->errorInfo());
        output(false);
    }
}
