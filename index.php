<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/views/login':
        require __DIR__ . '/views/login.php';
        break;

    case '/views/registration':
        require __DIR__ . '/views/registration.php';
        break;


    case '/views/dashboard':
        require __DIR__ . '/views/dashboard.php';
        break;

    case '/config/dataBaseConnect':
        require __DIR__ . '/config/dataBaseConnect.php';
        break;


    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}


?>