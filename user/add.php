<?php
require('../setup.php');

if($user_props['admin']) {
    $username = filter_input(INPUT_POST, 'username');

    $password = password_hash(filter_input(INPUT_POST, 'password'), PASSWORD_DEFAULT);

    if(filter_input(INPUT_POST, 'readonly') === 'yes') {
        $readonly = 'true';
    } else {
        $readonly = 'false';
    }

    if(filter_input(INPUT_POST, 'admin') === 'yes') {
        $admin = 'true';
    } else {
        $admin = 'false';
    }

    $succ = $db->query('INSERT INTO users (username,password,readonly,admin) VALUES ("'.$username.'","'.$password.'",'.$readonly.','.$admin.')');
} else {
    http_response_code(401);
}

if($succ) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>wdb - Add User</title>
        <link rel="stylesheet" type="text/css" href="../style.css" />
    </head>
    <body>
    <p>User added.</p>
    <p><a href="../index.php">Return to home</a></p>
    </body>
    </html>
    <?php
} else {
    http_response_code(500);
}