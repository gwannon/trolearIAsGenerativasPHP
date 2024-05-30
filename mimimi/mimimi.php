<?php 

ini_set("display_errors", 1);

define('DEFAULT_BR_TEXT', "\r\n");

include("./vendor/autoload.php");
use HungCP\PhpSimpleHtmlDom\HtmlDomParser;

$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$dom = HtmlDomParser::file_get_html($actual_link);

$elems = $dom->find("p,li,h1,h2,h3,h4,h5,td,th,blockquote,button");
$rand = rand(1,6);
if($rand >= 1 && $rand <= 5) { //Sustituimos todas las vocales por una vocal al azar
  $rep = array_rand(array_flip(["a", "e", "i", "o", "u"]));
  foreach ($elems as $elem) {
    $string = $elem->innertext;
    $control = 0;
    $newstring = "";
    foreach(mb_str_split($string) as $char) {
      if($char == '<') $control = 1;
      else if($char == '>') $control = 0;
      if($control == 0) {
        $char = preg_replace("/[aeoiuàèìòùáéíóúâêîôûãõäëïöü]/", $rep, $char);
        $char = preg_replace("/[AEIOUÀÈÌÒÙÁÉÍÓÚÂÊÎÔÛÃÕÄËÏÖÜ]/", strtoupper($rep), $char);
        $newstring .= utf8_encode($char);
      } else $newstring .= $char;
    }
    $elem->innertext = $newstring;
  }
} else if($rand == 6) { //Reordenamos aleatoriamente todas las palabras
  foreach ($elems as $elem) {
    $words = explode( " ", strip_tags($elem->innertext));
    shuffle($words);
    $text = mb_strtolower(implode(" ",$words));
    $elem->innertext = $text;
  }
}

$str = $dom->save();
echo $str;
echo "<!-- Trolled!!!! -->";

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
