<?php

$url="https://api.twitter.com/1/trends/daily.json?date=".date("Y-m-d", strtotime("yesterday"));
echo $url;


?>