<?php require('../setup.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>wdb - Add User</title>
    <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
<form method="post" action="add.php">
    <table>
        <tbody>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="username" /></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" /></td>
        </tr>
        </tbody>
    </table>
    <p><input type="checkbox" name="readonly" value="yes" />Read-Only</p>
    <p><input type="checkbox" name="admin" value="yes" />Admin</p>
    <p><input type="submit" value="Add"/></p>
</form>
</body>
</html>