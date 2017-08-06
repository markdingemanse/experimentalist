<?php

namespace MarkDingemanse\Experimentalism\Slim;

use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RuntimeException;
use Slim\App;

require '../../vendor/autoload.php';

$config = new Dotenv(__DIR__ . '/../../');
$config->load();

$app = new App();
$container = $app->getContainer();

$container['logger'] = function() {
    $logger = new Logger(getenv('logname'));
    $file_handler = new StreamHandler(getenv('loguri'));
    $logger->pushHandler($file_handler);

    return $logger;
};

$container['db'] = function () {
    $host = getenv('host');
    $dbname = getenv('dbname');
    $dsn = "mysql:dbname=$dbname;host=$host";

    try {
        $pdo = new PDO($dsn, getenv('user'), getenv('pass'));

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    } catch (Exception $e) {
        $this->logger->addInfo($e);
        new RuntimeException('something went wrong while instantiating a PDO instance please check the experimentalist Log for more information', 0);
    }


};

$app->get('/', function (Request $request, Response $response) {
    $query = getenv('query');
    $result = $this->db->query($query);

    $this->logger->addInfo('query: ' . $query . " ~~ given result: " . json_encode($result));

    $response->getBody()->write(
        \MarkDingemanse\Experimentalism\Base\display_data($result)
    );

    return $response;
});

$app->run();
