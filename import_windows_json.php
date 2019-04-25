<?php
header('Content-Type: application/json');

require_once('setup.php');

foreach($headers as $name => $content) {
    if(lower($name) == 'content-type') {
        $content_type_header = lower($content);
    }
}
if(!$content_type_header) {
    http_response_code(406);
    exit;
}

if($content_type_header == 'multipart/form-data') {
    $sxml = simplexml_load_file($_FILES['config_file']['tmp_name']);
} elseif ($content_type_header == 'text/xml' || $content_type_header == 'application/xml') {
    $sxml = simplexml_load_string('php://input');
} else {
    http_response_code(406);
    header('Content-Type: text/plain');
    echo 'The only acceptable content types for input to this script are multipart/form-data or application/xml';
    exit;
}



if($sxml) {
    if($sxml->MSM->security->sharedKey->keyType == "passPhrase") {
        $net_name = $sxml->SSIDConfig->SSID->name;
        $net_psk = $sxml->MSM->security->sharedKey->keyMaterial;

        if (count($db->query("SELECT true FROM networks WHERE essid=\"$net_name\"")->fetchAll(PDO::FETCH_NUM)) > 0) {
            $res = 'Already in database';
        } else {
            $db->query("INSERT INTO networks (essid,psk) VALUES (\"$net_name\",\"$net_psk\")");
            $res = 'Added';
        }

        echo "{\"error\": false, \"SSID\": \"$net_name\", \"PSK\": \"$net_psk\", \"status\": \"$res\"}";
    } else {
        echo '{"error": "This network does not appear to use a pre-shared key"}';
    }
} else {
    echo '{"error": "XML data could not be parsed"}';
}