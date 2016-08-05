<html>
<head>
<title>Serchify</title>
</head>
<body>
<center><form method="post" action="">
<input type="text" name="query" placeholder="Query" required>
<input type="submit" name="submit" placeholder="submit">
</form></center>
<?php
//Working
//error_reporting(0);
$dbhost ="";
$dbuser ="";
$dbpass="";

$link=mysqli_connect($dbhost,$dbuser,$dbpass);
mysqli_select_db($link, "");
$queryp = $_POST["query"];
$query = str_replace("%20"," ",$queryp);
$prisearch = "SELECT * FROM pages WHERE MATCH(plaintext) AGAINST('$query')
ORDER BY MATCH(plaintext) AGAINST('$query') DESC
";
$priresult = mysqli_query($link,$prisearch);
while($prirow = mysqli_fetch_array($priresult))
{
	$urls[$i]=$prirow['urls'];
	$titles = $prirow['title'];
	$metatag = $prirow['metatag'];
	echo "<a href=\"$urls[$i]\">$urls[$i]</a></h4><br>";
	//echo "<h5>$metatag</h5><br>";
	$i++;
}

?>
</body>
</html>