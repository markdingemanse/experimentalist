<?php

use PDO;
use RuntimeException;

$host   = "";
$dbname = "";
$user   = "";
$pass   = "";
$query  = "";

// dsn is generated based on given db and host
$dsn    = "mysql:dbname=$dbname;host=$host";


try {
    $pdo = new PDO($dsn, $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
} catch (Exception $e) {
    $this->logger->addInfo($e);
    new RuntimeException('something went wrong while instantiating a PDO instance please check the experimentalist Log for more information', 0);
}

$query = $query;
$result = $this->db->query($query);

$this->logger->addInfo('query: ' . $query . " ~~ given result: " . json_encode($result));

display_data($result);

/**
 * generic function for printing a table
 *
 * @param  array
 *
 * @return void
 */
function display_data($data)
{
    $output = "<table>";
    foreach ($data as $key => $var) {
        if ($key===0) {
            $output .= '<tr>';
            foreach ($var as $col => $val) {
                $output .= "<td>" . $col . '</td>';
            }
            $output .= '</tr>';
            foreach ($var as $col => $val) {
                $output .= '<td>' . $val . '</td>';
            }
            $output .= '</tr>';
        } else {
            $output .= '<tr>';
            foreach ($var as $col => $val) {
                $output .= '<td>' . $val . '</td>';
            }
            $output .= '</tr>';
        }
    }

    $output .= '</table>';
    echo $output;
}
