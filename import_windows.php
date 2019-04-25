<?php
require_once('setup.php');

$headers = apache_request_headers();

foreach($headers as $name => $content) {
    if(strtolower($name) == 'accept') {
        $accept_header = strtolower($content);
    } elseif(strtolower($name) == 'content-type') {
        $content_type_header = strtolower($content);
    }
    if($accept_header && $content_type_header) break;
}

if(!$accept_header || !$content_type_header) {
    http_response_code(406);
    exit;
}

$succ = true;

if(strpos($content_type_header, 'multipart/form-data') !== false) {
    $sxml = simplexml_load_file($_FILES['config_file']['tmp_name']);
} elseif (strpos($content_type_header,'text/xml') !== false || strpos($content_type_header, 'application/xml') !== false) {
    $sxml = simplexml_load_string('php://input');
} else {
    http_response_code(406);
    $succ = false;
    $error = 'The only acceptable content types for input to this script are multipart/form-data or application/xml';
}

if($succ && $sxml) {
    $net_name = $sxml->SSIDConfig->SSID->name;
    $net_psk = $sxml->MSM->security->sharedKey->keyMaterial;
    if($net_name) {
        if ($sxml->MSM->security->sharedKey->keyType == "passPhrase" && $net_psk) {
            if (count($db->query("SELECT true FROM networks WHERE essid=\"$net_name\"")->fetchAll(PDO::FETCH_NUM)) > 0) {
                $res = 'Already in database';
            } else {
                $db->query("INSERT INTO networks (essid,psk) VALUES (\"$net_name\",\"$net_psk\")");
                $res = 'Added';
            }
        } else {
            http_response_code(500);
            $succ = false;
            $error = 'Unable to parse a passphrase for this network';
        }
    } else {
        http_response_code(500);
        $succ = false;
        $error = 'Unable to parse a SSID for this network';
    }
} else {
    http_response_code(500);
    $succ = false;
    $error = 'The input could not be parsed as XML';
}


if(strpos($accept_header, "text/html") !== false) {
    header('Content-Type: text/html');
    echo '<!DOCTYPE html><html><head><title>wdb - Windows Import</title><link rel="stylesheet" type="text/css" href="style.css"></head><body>';
    if($succ) {
        echo "<p><b>SSID:</b> $net_name</p><p><b>PSK: </b> $net_psk</p><p><b>Result:</b> $res</p><p><a href='index.php'>Return to home</a></p>";
    } else {
        echo "<p>$error</p>";
        exit;
    }
    echo '</body></html>';
} elseif (strpos($accept_header, "application/json") !== false) {
    header('Content-Type: application/json');
    if($succ) {
        echo "{\"error\": false, \"SSID\": \"$net_name\", \"PSK\": \"$net_psk\", \"status\": \"$res\"}";
    } else {
        echo "{\"error\": \"$error\"}";
        exit;
    }
} else {
    http_response_code(406);
    exit;
}