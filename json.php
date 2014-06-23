<?php

require_once('inc_global.php');

// init
$callback = $purifier->purify($_REQUEST['callback']);

if (count($rssArray) > 0) {

  if ($callback) {
    header("content-type: text/javascript");
    echo $callback . '(' . json_encode($rssArray) . ')';
  } else {
    header("content-type: application/json;");
    echo json_encode($rssArray);
  }
}