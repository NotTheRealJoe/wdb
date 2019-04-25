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

$columns = ['essid', 'psk', 'location_description', 'hex(bssid)', 'latitude', 'longitude', 'admin_url', 'admin_username', 'admin_password'];

$data = $db->query('SELECT ' . join(',', $columns) . ' FROM networks ORDER BY essid')->fetchAll(PDO::FETCH_ASSOC);

if(strpos($accept_header, 'text/html') !== false) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>wdb</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>
    </head>
    <body>
    <p>Import from...
        <a class="btn" href="import_android_form.php">Android config file</a>
        <a class="btn" href="import_windows_form.php">Windows config file</a>
        <a class="btn" href="import_wpasupplicant_form.php">wpa_supplicant.conf (Linux or old Android)</a>
        <a class="btn" href="add_form.php">Manual Entry</a>
    </p>
    <p>Export to...
        <a class="btn" href="export_nm.php">NetworkManager config files</a>
    </p>
    <?php
    echo '<table><thead><tr><td>action</td>';
    foreach($columns as $column) {
        echo "<td>$column</td>";
    }
    echo '</tr></thead><tbody>';
    foreach ($data as $row) {
        echo '<tr><td><a class="icon" href="update_form.php?essid='.$row['essid'].'">&#x270F;&#xFE0F;</a> &#x1F5D1;&#xFE0F;</td>';
        foreach ($row as $col) {
            echo "<td>$col</td>";
        }
        echo '</tr>';
    }
    echo '</tbody></table>';

    if($user_props['admin']) {
        echo '<p>Users: <a class="btn" href="user/add_form.php">Add</a></p>';
    }
    ?>
    </body>
    </html>
    <?php
} elseif(strpos($accept_header, 'application/json') !== false) {
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    http_response_code(406);
}