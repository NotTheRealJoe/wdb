<?php
require_once('db.php');

// A variable so pages can check what content type they should output
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

// Authentication
function unauthenticated() {
    http_response_code(401);
    header('WWW-Authenticate: Basic realm="wdb"');
    exit;
}
$user_props = [
    'ro' => null,
    'admin' => null
];

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
    unauthenticated();
} else {
    $user_data = $db->query('SELECT password,readonly,admin FROM users WHERE username="'.$_SERVER['PHP_AUTH_USER'].'"')->fetch(PDO::FETCH_ASSOC);
    if(!password_verify($_SERVER['PHP_AUTH_PW'], $user_data['password'])) {
        unauthenticated();
    }
    $user_props['ro'] = $user_data['readonly'] == '1';
    $user_props['admin'] = $user_data['admin'] == '1';
}
unset($user_data);

// Globally available functions
function bin2mac($bin) {
    if($bin === NULL) return '';
    $str = strtoupper(bin2hex($bin));
    return substr($str, 0, 2) . ':' . substr($str, 2, 2) . ':' . substr($str, 4, 2) . ':' . substr($str, 6, 2) . ':' . substr($str, 8, 2) . ':' . substr($str, 10, 2);
}

