<?php
require_once("class/Scrapper.php");
require_once("class/Logger.php");
echo "START: ".date("Y-m-d H:i:s")."<BR>";
$scrapper = new Scrapper();
//$scrapper->setUrl('https://goldmanrecruitment.pl/oferty-pracy/');
$scrapper->setUrl('https://goldmanrecruitment.pl/wp-json/appmanager/v1/ads?page={page}&lang=pl&specialization=&location=');

$scrapper -> parse();
echo "DONE: ".date("Y-m-d H:i:s");

