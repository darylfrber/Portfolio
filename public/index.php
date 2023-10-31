<?php

require_once '../vendor/autoload.php';

// Defineer welke Controllers er zijn
$controllers = [
    'Home' => 'HomeController',
    'User' => 'UserController',
    'School' => 'SchoolController',
];

// Bekijk of er een controller is meegegeven in de URL parameters
if (!isset($_GET['controller'])) {
    error('404', 'Controller niet gevonden');
}

$controllerName = $_GET['controller'];

// Check of de controller bestaat
// array_key_exists controleert of bepaalde waarde in de array zit (string, array)
if (!array_key_exists($controllerName, $controllers)) {
    error('404', 'Controller \'' . $controllerName . 'Controller\' niet gevonden');
}

// Maak de juiste controller aan gebaseerd op de URL
$controllerClass = $controllers[$controllerName];
$Controller = new $controllerClass();

// Kijk of er een methode is meegegeven in de URL
if (isset($_GET['method'])) {
    $methodName = $_GET['method'];

    // Bekijk of de methode bestaat met de functie method_exists
    if (method_exists($Controller, $methodName)) {
        // Roep de methode aan
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postMethodName = $methodName;

            if (method_exists($Controller, $postMethodName)) {
                // Roep de POST-methode aan met de individuele elementen van $_POST als argumenten
                $postArguments = array_values($_POST);
                $Controller->$postMethodName(...$postArguments);
            } else {
                error('404', 'POST method \'' . $postMethodName . '\' not found');
            }
        } else {
            // Roep de normale methode aan
            $Controller->$methodName();
        }
    } else {
        error('404', 'Method \'' . $methodName . '\' not found');
    }
} else {
    error('404', 'Method not found');
}
