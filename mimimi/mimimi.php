<?php 

include("./vendor/autoload.php");
use HungCP\PhpSimpleHtmlDom\HtmlDomParser;

$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$dom = HtmlDomParser::file_get_html($actual_link);

$elems = $dom->find("p,li,h1,h2,h3,h4,h5,td,th,blockquote");
foreach ($elems as $elem) {
  $elem->innertext = mb_ereg_replace("/[aeouàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ]/i", "i", $elem->innertext);
}

$str = $dom->save();
echo $str;

echo "<!-- YOU HAVE BEEN TROLLED XD -->";

function get_site_html($site_url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_URL, $site_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    global $base_url; 
    $base_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    return $response;
}