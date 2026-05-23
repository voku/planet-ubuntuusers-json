<?php

declare(strict_types=1);

require_once __DIR__ . '/inc_global.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'strict_variables' => true,
]);

echo $twig->render('index.twig', [
    'rssArray' => getPlanetFeed(),
]);