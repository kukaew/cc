<?
$get = rtrim($_GET['d'],'/');

if (!$get) $get= date('d-m-Y');

if (file_exists("json/$get.json")) {echo file_get_contents("json/$get.json"); }
else {
	$date = '?date_req=' . str_replace('-','/',$get);
	$curs = array();

	$xml=simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp" . $date);

	foreach($xml->Valute as $m) $curs[(string)$m->CharCode] = (float)str_replace(",", ".", (string)$m->Value) / (float)str_replace(",", ".", (string)$m->Nominal);

	$echo = '{';

	foreach ($curs as $k=>$v) $echo .=  "\"$k\":$v,";

	$echo .= '"RUB":1}';

	$fp = fopen("json/".$get.".json", "w");

	fwrite($fp, $echo);

	fclose($fp);

	echo $echo;
}
?>