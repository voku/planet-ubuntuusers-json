<?php

declare(strict_types=1);

use HTMLPurifier;
use HTMLPurifier_Config;

require_once __DIR__ . '/vendor/autoload.php';

const FEED_URL = 'http://planet.ubuntuusers.de/feeds/full/10/';

function getPlanetFeed(): array
{
    $purifier = createPurifier();
    $feed = new SimplePie();

    $feed->set_feed_url(FEED_URL);
    $feed->set_output_encoding('UTF-8');
    $feed->enable_order_by_date(false);
    $feed->enable_cache(true);
    $feed->set_cache_duration(300);
    $feed->set_cache_location(getRuntimeCacheDirectory('simplepie'));
    $feed->init();

    $posts = [];

    foreach ($feed->get_items() ?? [] as $item) {
        $title = purifyHtml($purifier, $item->get_title());
        $date = (string) ($item->get_date(DATE_ATOM) ?: '');

        $posts[] = [
            'md5' => md5($title . $date),
            'link' => (string) ($item->get_permalink() ?: ''),
            'title' => $title,
            'date' => $date,
            'content' => purifyHtml($purifier, $item->get_content()),
            'author' => extractAuthorName($item),
        ];
    }

    return [
        'meta' => [
            'title' => (string) ($feed->get_title() ?: 'planet.ubuntuusers.de'),
            'source' => FEED_URL,
            'generated_at' => gmdate(DATE_ATOM),
        ],
        'posts' => $posts,
        'error' => $feed->error() ?: null,
    ];
}

function createPurifier(): HTMLPurifier
{
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.SerializerPath', getRuntimeCacheDirectory('htmlpurifier'));

    return new HTMLPurifier($config);
}

function getRuntimeCacheDirectory(string $suffix): string
{
    $path = sys_get_temp_dir() . '/planet-ubuntuusers-json/' . $suffix;

    if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
        throw new RuntimeException('Unable to create cache directory: ' . $path);
    }

    return $path;
}

function purifyHtml(HTMLPurifier $purifier, ?string $html): string
{
    return $purifier->purify((string) ($html ?? ''));
}

function extractAuthorName(object $item): string
{
    $author = $item->get_author();

    if (!is_object($author)) {
        return '';
    }

    foreach (['get_name', 'get_email', 'get_link'] as $method) {
        if (!method_exists($author, $method)) {
            continue;
        }

        $value = trim((string) $author->{$method}());

        if ($value !== '') {
            return $value;
        }
    }

    return '';
}