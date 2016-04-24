
<?php
// require("phpsqlajax_dbinfo.php");

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Opens a connection to a MySQL server & database
include('db_login.php');
$connect = mysql_connect($host,$name,$pass);
mysql_select_db("heatmap",$connect);

// Select all the rows in the data table
$query = "SELECT * FROM data WHERE 1";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}


header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  echo '<data ';
  echo 'name="' . parseToXML($row['name']) . '" ';
  echo 'address="' . parseToXML($row['address']) . '" ';
  echo 'lat="' . parseToXML($row['lat']) . '" ';
  echo 'lng="' . parseToXML($row['lng']) . '" ';
  echo 'type="' . parseToXML($row['type']) . '" ';
  echo 'date="' . parseToXML($row['Date']) . '" ';
  echo 'items="' . parseToXML($row['Items']) . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';

?>
