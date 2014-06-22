<?php

require_once 'vendor/composer/autoload.php';

// init
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$rssArray = array();
$rssUrl = 'http://planet.ubuntuusers.de/feeds/full/10/';

$feed = new SimplePie();

$feed->set_feed_url($rssUrl);
$feed->set_output_encoding('UTF-8');	
$feed->enable_order_by_date(false);
$feed->enable_cache(false);
$feed->handle_content_type();
$feed->init();
$feed->handle_content_type();

// fetch global title
$globalTitle = $feed->get_title();

// fetch rss-items
if ($feed->data) {

	$i = 0;
	foreach($feed->get_items() as $item) {
		$rssArray['posts'][$i]['md5'] = md5($item->get_title() . $item->get_date('j M Y'));
		$rssArray['posts'][$i]['link'] = $item->get_permalink();
		$rssArray['posts'][$i]['title'] = $item->get_title();
		$rssArray['posts'][$i]['date'] = $item->get_date('j M Y');
		$rssArray['posts'][$i]['content'] = $item->get_content();
		$rssArray['posts'][$i]['author'] = $item->get_author();

		$i++;
	}
}