<?php

require_once "paginator.class.php";

$all = 100;
$perpage = 5;
$link = "index.php?page=";

$navigation = pages::page_navigation($all, $perpage, $link);

echo $navigation;