<?php

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DATABASE_HOST'];
$db   = $_ENV['DATABASE_NAME'];
$user = $_ENV['DATABASE_USER'];
$pass = $_ENV['DATABASE_PASS'];

$dsn = "mysql:host=$host;dbname=$db";
use RedBeanPHP\R as R;
R::setup(
    $dsn,
    $user,
    $pass
);

require_once 'controllers/BaseController.php';
require_once 'controllers/SchoolController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/HomeController.php';
function render($template, $data)
{
    $loader = new \Twig\Loader\FilesystemLoader('../views');
    $twig = new \Twig\Environment($loader);
        
    if (isset($_SESSION['user_id'])) { // Als een gebruiker is ingelogd, geef de template user info
        $beans = R::find('user', ' id = ? ', [$_SESSION['user_id']]);
        foreach ($beans as $bean) {
            $data['gebruiker'] = $bean;
        }
    }
    echo $twig->render($template, $data);
}

function error($errorNumber, $errorMessage)
{
    $data['errorNumber'] = $errorNumber;
    $data['errorMessage'] = $errorMessage;
    http_response_code($errorNumber);
    render('/error.twig', $data);
    exit();
}