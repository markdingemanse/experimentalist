<?php

use PDO;
use Exception;

$host   = "";
$dbname = "";
$user   = "";
$pass   = "";
$query  = "SELECT 'something sweet'";

// dsn is generated based on given db and host
$dsn    = "mysql:dbname=$dbname;host=$host";

try {
    $pdo = new PDO($dsn, $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
    http_response_code(503);
    exit('error connection to the database');
}

$result = $pdo->query($query);

header('Content-type: application/json');
echo json_encode($result);
