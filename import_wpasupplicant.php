<?php
require('setup.php');

class Network {
    public $essid;
    public $psk;

    function __construct($essid, $psk) {
        $this->essid = $essid;
        $this->psk = $psk;
    }
}

$data_string = file_get_contents($_FILES['config_file']['tmp_name']);

$networks = array();

$start_i = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>wdb - wpasupplicant.conf Import</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<p>Here are the results of your import:</p>
<table>
    <thead><td>SSID</td><td>PSK</td><td>Status</td></thead>
    <tbody>
<?php
while($start_i < strlen($data_string) - 1) {
    // Beginning and end of each block
    $start_i += strpos(substr($data_string, $start_i), 'network') + strlen("network");
    $start_i += strpos(substr($data_string, $start_i), '=') + 1;
    $start_i += strpos(substr($data_string, $start_i), '{') + 1;
    $end_i = strpos(substr($data_string, $start_i), '}') + $start_i;

    // ESSID
    $start_i_essid = strpos(substr($data_string, $start_i, $end_i - $start_i), 'ssid') + $start_i;
    $start_i_essid += strpos(substr($data_string, $start_i_essid, $end_i - $start_i_essid), '=') + 1;
    $start_i_essid += strpos(substr($data_string, $start_i_essid, $end_i - $start_i_essid), '"') + 1;
    $end_i_essid = strpos(substr($data_string, $start_i_essid), '"') + $start_i_essid;
    $essid = substr($data_string, $start_i_essid, $end_i_essid - $start_i_essid);

    // PSK
    $start_i_psk_pre = strpos(substr($data_string, $start_i, $end_i - $start_i), 'psk');
    if($start_i_psk_pre === false) {
        $psk = null;
    } else {
        $start_i_psk = $start_i_psk_pre + $start_i;
        $start_i_psk += strpos(substr($data_string, $start_i_psk, $end_i - $start_i_psk), '=') + 1;
        $start_i_psk += strpos(substr($data_string, $start_i_psk, $end_i - $start_i_psk), '"') + 1;
        $end_i_psk = strpos(substr($data_string, $start_i_psk), '"') + $start_i_psk;
        $psk = substr($data_string, $start_i_psk, $end_i_psk - $start_i_psk);
    }

    array_push($networks, new Network($essid, $psk));
    if(!is_null($psk)) {
        echo "<tr><td>$essid</td><td>$psk</td><td>";
        if (count($db->query("SELECT true FROM networks WHERE essid=\"$essid\"")->fetchAll(PDO::FETCH_NUM)) > 0) {
            echo '<span class="neutral">Already in database</span>';
        } else {
            $db->query("INSERT INTO networks (essid,psk) VALUES (\"$essid\",\"$psk\")");
            echo '<span class="good">Added</span>';
        }
        echo '</td></tr>';
    }

    $start_i = $end_i + 1;
}
?>
    </tbody>
</table>
<p><a href="index.php">Back to home</a></p>
</body>
</html>