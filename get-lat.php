<?php
$address = "chandigarh";
$key = "AIzaSyB-XRhR-FuDDrkTbY2Ze6R2TrMcxqHmj2Q";
$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.$key;
$geocode=file_get_contents($url);
print_r($geocode);


    $output= json_decode($geocode);
    echo $latitude = $output->results[0]->geometry->location->lat;
    echo '<br>';
	echo $longitude = $output->results[0]->geometry->location->lng
?>