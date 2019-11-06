<?php

include('flickr.php');

$Flickr = new Flickr('YOUR_FLICKR_API_KEY');

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ipdata.co/?api-key=YOUR_IPDATA_API_KEY",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo $response;
}

//echo "<br>".gettype($response);

$response = json_decode($response);

//echo "<br>".gettype($response)."<br>";

//echo "<br>".$response->latitude."<br>";
//echo "<br>".$response->longitude."<br>";

$lat = $response->latitude;
$lon = $response->longitude;



$key = 'YOUR_AIR_VISUAL_API_KEY';



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'api.airvisual.com/v2/nearest_city?lat='.$lat.'&lon='.$lon.'&key='.$key,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 80,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$result = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);


$result = json_decode($result,true);
  
$photo = $Flickr->search($lat,$lon);

$imgsrc = 'http://farm' . $photo["farm"] . '.static.flickr.com/' . $photo["server"] . '/' . $photo["id"] . '_' . $photo["secret"] . '.jpg';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Web Mashup</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body style='background-image: url("<?php echo $imgsrc?>");background-repeat:no-repeat;background-size: 100vw 100vh;'>
  <div class="container" style="text-align:center; padding:15% 0%">
  <div class="card" style="width:60%;display:inline-block;text-align:left;">
    <div class="card-header">
      <h1>Location : <?php echo $response->city.",".$response->country_name ?></h1>
    </div>
    <div class="card-body">
      <h6>Time Stamp :<?php echo $result['data']['current']['pollution']['ts'];?></h6>
      <h4>Air Quality Index (US Standards) : <?php echo $result['data']['current']['pollution']['aqius'];?></h4>
      <h5>Primary Pollutant : <?php echo $result['data']['current']['pollution']['mainus'];?></h5>
    </div> 
  </div>
  </div>



</body>
</html>
</html>