<?php
// DIC configuration

$container = $app->getContainer();

// Monolog logger
$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Twig view
$container['view'] = function ($container) {
    $settings = $container->get('settings')['renderer'];
    $view = new Slim\Views\Twig($settings['view_path'], []);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};

// PDO database
$container['db'] = function ($container) {
    $settings = $container->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'] . ";charset=utf8", $settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// controllers
$container['HomeController'] = function ($container) {
    return new \App\Controllers\ControllerHome($container['view'], $container['router']);
};

$container['NotesController'] = function ($container) {
    return new \App\Controllers\ControllerNotes($container['view'], $container['router'], $container['db']);
};

$container['LogController'] = function ($container) {
    return new \App\Controllers\ControllerLog($container['view'], $container['router'], $container['db']);
};

$container['AccountController'] = function ($container) {
    return new \App\Controllers\ControllerAccount($container['view'], $container['router'], $container['db']);
};

$container['AuthenticationMW'] = function ($container) {
    return new \App\Middlewares\Authentication($container['view'], $container['router'], $container['db']);
};

$container['LoginMW'] = function ($container) {
    return new \App\Middlewares\LoginChecker($container['view'], $container['router']);
};

$container['notFoundHandler'] = function ($container) {
    return new \App\Middlewares\NotFoundHandler($container['view'],
        function ($request, $response) use ($container) {
        return $container['response']->withStatus(404);
    });
};
