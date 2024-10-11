<?php 

ini_set("display_errors", 1);

define('DEFAULT_BR_TEXT', "\r\n");

include("./vendor/autoload.php");
use HungCP\PhpSimpleHtmlDom\HtmlDomParser;

$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$dom = HtmlDomParser::file_get_html($actual_link);


//#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})



$str = $dom->save();
echo $str;
echo "#Trolled!!!!";