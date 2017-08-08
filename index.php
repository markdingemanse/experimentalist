<?php

use PDO;
use RuntimeException;
use Exception;

$host   = "sql8.pcextreme.nl";
$dbname = "62398vhost";
$user   = "62398vhost";
$pass   = "vhost";
$query  = "SELECT 'something sweet'";

// dsn is generated based on given db and host
$dsn    = "mysql:dbname=$dbname;host=$host";

try {
    $pdo = new PDO($dsn, $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    new RuntimeException('something went wrong while instantiating a PDO instance', 0);
}

$result = $pdo->query($query);

die(json_encode($result));
