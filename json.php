<?php

require_once('inc_global.php');

// init
$callback = $purifier->purify($_REQUEST['callback']);

if (count($rssArray) > 0) {
	header("content-type: application/json;");

	if ($callback) {
		echo $callback . '(' . json_encode($rssArray) . ')';
	} else {
		echo json_encode($rssArray);
	}
}