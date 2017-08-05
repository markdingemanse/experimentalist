<?php

namespace MarkDingemanse\Experimentalism\Slim;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\App;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use PDO;
use RuntimeException;

require '../../vendor/autoload.php';
require '../../config.php';

$app = new App(["settings" => $config]);
$container = $app->getContainer();

$container['logger'] = function($config) {
    $logger = new Logger($config['settings']['name']);
    $file_handler = new StreamHandler($config['settings']['loguri']);
    $logger->pushHandler($file_handler);

    return $logger;
};

$container['db'] = function ($config) {
    $db = $config['settings']['db'];
    $host = $db['host'];
    $dbname = $db['dbname'];
    $dsn = "mysql:dbname=$dbname;host=$host";

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass']);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    } catch (Exception $e) {
        $this->logger->addInfo($e);
        new RuntimeException('something went wrong while instantiating a PDO instance please check the experimentalist Log for more information', 0);
    }


};

$app->get('/', function (Request $request, Response $response) use ($config){
    $query = $config['experimentalism']['query'];
    $result = $this->db->query($query);

    $this->logger->addInfo('query: ' . $query . " ~~ given result: " . json_encode($result));

    $response->getBody()->write(
        \MarkDingemanse\Experimentalism\Base\display_data($result)
    );

    return $response;
});

$app->run();
