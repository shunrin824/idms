<html>
<?php
//putenv("LANG=en_US.UTF-8");
ini_set('display_errors', "On");

echo"--1--<br>";
$xml = file_get_contents('http://iss.ndl.go.jp/api/sru?operation=searchRetrieve&query=isbn='.$_GET['isbn']);
print_r($xml);#ここの出力では特殊文字
echo("<br><br>");



echo"--2--<br>";
$xml = htmlspecialchars_decode($xml);
print_r($xml);#ここの出力では特殊文字変換済み。
echo("<br><br>");

echo"--a--<br>";
$domDocument = new DOMDocument();
$domDocument -> loadHTML($xml);
$xmlString = $domDocument->saveXML();
$xmlObject = simplexml_load_string($xmlString);
$array = json_decode(json_encode($xmlObject), true);
ver_dump($array);
echo"--3--<br>";
$simplexml = simplexml_load_string($xml);
print_r( $simplexml );
echo("<br><br>");

//$simplexml = $xml;

    $title = $simplexml->records->record->recordData->children("hatena", true)->title;
print_r( $title );

//$ns = $xml->getnamespaces(true);
//$title = $xml->records->record->recordData->children("data", true)->title;
//echo("<br><br>");
//print_r($ns);
//echo("<br><br>");
//print_r($xml);#なぜかここの出力では日本語が消える。
//echo("<br><br>");
//print_r($title);


//$dom = DOMDocument::loadXML($XML);


?>
 <br><br>
 <?=($title)?>
</html>
