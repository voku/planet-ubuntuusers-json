<?php

require_once('inc_global.php');

$twig = new \voku\twig\TwigWrapper('index.twig', array(__DIR__), array('cache' => false));

$twig->assign('rssArray', $rssArray);

echo $twig->render();