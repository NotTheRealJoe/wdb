<?php
require('setup.php');

$sure = filter_input(INPUT_GET,'sure') === 'yes';
$essid = filter_input(INPUT_GET, 'essid');

if($sure) {
    $succ = $db->query('DELETE FROM networks WHERE essid="'.$essid.'"');
}

if(strpos($accept_header, 'text/html') !== false) {
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>wdb - Delete</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
    <?php
    if($sure && $succ) {
        echo '<p>Deleted.</p><p><a href="index.php">Return to home</a></p>';
    } elseif($sure) {
        echo '<p>Database error.</p>';
    } else {
        ?>
        <p>Are you sure you want to delete <b><?= $essid ?></b>?</p>
        <p><a class="btn" href="delete.php?sure=yes&essid=<?= $essid ?>">Yes</a> <a class="btn" href="index.php">No</a></p>
        <?php
    }
    ?>
    </body>
    </html>
<?php
} elseif(strpos($accept_header, 'application/json') !== false) {
    if($sure && $succ) {
        echo '{"success":true}';
    } elseif($sure) {
        echo '{"success":false, "error":"Database error."}';
    } else {
        echo '{"success":false, "error":"You are not sure"}';
    }
} else {
    http_response_code(406);
}