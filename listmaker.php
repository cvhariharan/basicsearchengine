<?php
//Working
set_time_limit(0);
require "simple_html_dom.php";
$seed = file_get_contents("seed.txt");
$cf = fopen("indexed.txt","a+");
$seeds = explode(',',$seed);
$urlfile = fopen("urls.txt","a+");
$dbhost ="";
$dbuser = "";
$dbpass="";

$link=mysqli_connect($dbhost,$dbuser,$dbpass);
mysqli_select_db($link, "");
$i=0;
$j=0;

for($j=0;$j<count($seeds);$j++)
{
	
	//$urls[$i]=$row['urls'];
	echo "$seeds[$j]<br>";
	$check1 = "SELECT urls FROM seeds WHERE urls=\"$seeds[$j]\"";
	$checkresult1 = mysqli_query($link,$check1);
	$checkrow1 = mysqli_fetch_array($checkresult1);
	if(count($checkrow1)<=0){
	fwrite($urlfile,"$seeds[$j],");
	$add1 = "INSERT INTO seeds (urls) VALUES(\"$seeds[$j]\")";
	$result1 = mysqli_query($link,$add1);
	fwrite($cf,"$seeds[$j],");
	$data = file_get_html($seeds[$j]);
	foreach($data->find('a') as $t){
	$needlea = explode('/',$seeds[$j]);
    $needle = $needlea[2];
	$c = explode('/',"$t");
	if(count($c)>=3 && $c[2]==$needle)
	{
	$thref = $t->href;
	echo "$thref<br>";
	$check2 = "SELECT urls FROM seeds WHERE urls LIKE \"$thref\"";
	$checkresult2 = mysqli_query($link,$check2);
	$checkrow2 = mysqli_fetch_array($checkresult2);
	if(count($checkrow2)<=0){
	fwrite($urlfile,"$thref");
	$add2 = "INSERT INTO seeds (urls) VALUES(\"$thref\")";
	$result2 = mysqli_query($link,$add2);
	fwrite($cf,"$thref,");
	}
	}
	}
	}
}
$cfread = file_get_contents('indexed.txt');
$crawled = explode(',',$cfread);
$sql = "SELECT * FROM seeds";
$result = mysqli_query($link,$sql);
//print_r($urls);
while($row=mysqli_fetch_array($result))
{
$urls[$i] = $row['urls'];
$i++;
}
//print_r($urls);
//echo "<br><br>";
//print_r($crawled);
//$tocrawl = array_diff($urls,$crawled);
echo "<br><br>";
//print_r($tocrawl);
for($i=0;$i<count($urls);$i++)
{
$data = file_get_html($urls[$i]);
foreach($data->find('a') as $element){
	$t = $element->href;
	if(!in_array(trim($t),$crawled)){
	$needlea = explode("/",$urls[$i]);
    $needle = $needlea[2];
	$c = explode("/","$t");
	if(count($c)>=3 && $c[2]==$needle){
	$thref1 = $t;
	$check3 = "SELECT urls FROM seeds WHERE urls LIKE \"$thref1\"";
	$checkresult3 = mysqli_query($link,$check3);
	$checkrow3 = mysqli_fetch_array($checkresult3);
	if(count($checkrow3)<=0){
	echo "$thref1<br>";
	$add3 = "INSERT INTO seeds (urls) VALUES(\"$thref1\")";
	$result3 = mysqli_query($link,$add3);
	fwrite($cf,"$thref1,");
	}
	}
	}
}
}
?>