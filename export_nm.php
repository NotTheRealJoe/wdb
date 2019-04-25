<?php
require_once('setup.php');

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function nm_config($ssid, $psk) {
    $uuid = gen_uuid();
    if(!$psk) {
        return "[connection]
id=$ssid
uuid=$uuid
type=wifi
permissions=

[wifi]
mac-address=
mac-address-blacklist=
mode=infrastructure
ssid=$ssid

[ipv4]
dns-search=
method=auto

[ipv6]
addr-gen-mode=stable-privacy
dns-search=
method=auto
";
    } else {
        return "[connection]
id=$ssid
uuid=$uuid
type=wifi
permissions=

[wifi]
mac-address=
mac-address-blacklist=
mode=infrastructure
ssid=$ssid

[wifi-security]
auth-alg=open
key-mgmt=wpa-psk
psk=$psk

[ipv4]
dns-search=
method=auto

[ipv6]
addr-gen-mode=stable-privacy
dns-search=
method=auto
";
    }
}

$id = uniqid();
mkdir("exports/$id");

$z = new ZipArchive();

if($z->open("exports/$id.zip", ZipArchive::CREATE) !== TRUE) {
    http_response_code(500);
    die();
}

foreach($db->query('SELECT essid,psk FROM networks')->fetchAll(PDO::FETCH_ASSOC) as $row) {
    if(!$row['essid']) continue;
    $z->addFromString($row['essid'] . '.nmconnection', nm_config($row['essid'], $row['psk']));
}

$z->close();

header('Content-Type: application/zip');
header('Content-Disposition: filename="nm-wifi-config.zip"');
echo file_get_contents("exports/$id.zip");

unlink("exports/$id.zip");
?>