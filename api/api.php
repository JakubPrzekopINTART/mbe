<?php
require_once "../class/Scrapper.php";
require_once "../class/Logger.php";
require_once "../class/Api.php";

$api = new Api();
$api -> setCmd($_GET["cmd"],isset($_GET["id"])?$_GET["id"]:null);
echo $api -> get();

