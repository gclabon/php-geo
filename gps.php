<?php


function getCoordinates($address){
    $address = urlencode($address);
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
    $response = file_get_contents($url);
    $json = json_decode($response,true);
 
    $lat = $json['results'][0]['geometry']['location']['lat'];
    $lng = $json['results'][0]['geometry']['location']['lng'];
 
    /*return array($lat, $lng);*/
	echo "Lat: $lat ";
	echo "Lng: $lng";
}
 
 
$coords = getCoordinates($_POST['address2']);
print_r($coords);

?>