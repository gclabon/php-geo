<?php

$home ="index.php";
$target_dir = "csv/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOK = 1;
$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
//for more file types use && $FileType != "png"
//if not corrent file type echo:
if($FileType != "csv") {
    echo "Sorry, only CSV files are allowed.";
    $uploadOk = 0;
}
//if not uploaded echo:
if ($uploadOK == 0) {
	echo "sorry your file was not uploaded.";
}
//if upload ok mv file to target_file location and echo confirmation
else {
	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } 
}

//connect to the database 
include('db_login.php');
$connect = mysql_connect($host,$name,$pass);
mysql_select_db("heatmap",$connect);
    //open and read target file
    $handle = fopen($target_file,"r"); 
     
    //loop through the file and insert into database 
    do { 
        if ($handle[0]) { 
            mysql_query("INSERT INTO data (name, address, type, Items) VALUES 
                ( 
                    '".addslashes($data[0])."', 
                    '".addslashes($data[1])."', 
                    '".addslashes($data[2])."', 
                    '".addslashes($data[3])."' 
                ) 
            "); 
        } 
    } while ($handle = fgetcsv($handle,1000,",","'")); 

//select id and address for each row and create variable then get coords
$result = mysql_query("SELECT id, address FROM data");
while ($row = mysql_fetch_object($result)) {
    $id = $row->id;
    $address = $row->address;

// getCoordinates of $address
    $address = urlencode($address);
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
    $response = file_get_contents($url);
    $json = json_decode($response,true);
 
 // @ supressing errors
    $lat = @$json['results'][0]['geometry']['location']['lat'];
    $lng = @$json['results'][0]['geometry']['location']['lng'];
 
 //update coordinates of $address in database
 mysql_query("UPDATE data SET lat=$lat, lng=$lng WHERE id=$id");

}
echo "<br />";
echo "<a href=\"$home\">Return</a>";
?>
