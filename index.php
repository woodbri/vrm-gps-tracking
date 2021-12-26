<?php
// ****************** edit these for your configuration ****************
// vrm info
$installationID = '*****';
$user = '******';
$userID = '******';
$pass = '******';
$ptoken = '******';

// set some defaults for the map in case we fail to reach vrm portal
// I picked my home lat, lon
$title = 'My Tracking Map Page';
$lat = '42.902734';
$lon = '-71.258601';
$speed = 'unknown';
$altitude = 'unknown';

// default days in the past you want you track to show
$days = 3;

// ****** don't edit below this line unless you know what your doing ****

// if we got ?days=nn on the url overload default here
if (isset($_REQUEST['days'])) {
    $days = intval($_REQUEST['days']);
}

// set up a request to get the current GPS location
// this will return location age, lat, lon, speed, altitude
$curl = curl_init("https://vrmapi.victronenergy.com/v2/installations/$installationID/widgets/GPS");
curl_setopt_array($curl, array(
    CURLOPT_POST => FALSE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        "X-Authorization: Token $ptoken",
        'Content-Type: application/json'
    )
));

// send request
$response = curl_exec($curl);

// If we didn't get an error the decode the response
// and fetch data from it
if($response !== FALSE){

    // Decode the response
    $responseData = json_decode($response, TRUE);

    /*
    // print the raw decoded response so we can see whats in it
    echo '<pre>';
    print_r($responseData);
     */

    $lat = $responseData['records']['data']['attributes']['4']['value'];
    $lon = $responseData['records']['data']['attributes']['5']['value'];
    $speed = $responseData['records']['data']['attributes']['142']['valueFormattedWithUnit'];
    $altitude = $responseData['records']['data']['attributes']['584']['valueFormattedWithUnit'];
    $age = $responseData['records']['data']['attributes']['secondsAgo']['valueFormattedWithUnit'];

}

// Close the cURL handler for this request
curl_close($curl);

// Set the start and end time for downloading the gps tracks
// If ?days=nn is not present on the url then days=3 is defaulted above
$start = strtotime('-' . strval($days) . ' days');
$end = strtotime('now');

// Set up the request to download the gps tracks
$curl = curl_init("https://vrmapi.victronenergy.com/v2/installations/$installationID/gps-download?end=$end&start=$start");
curl_setopt_array($curl, array(
    CURLOPT_POST => FALSE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        'X-Authorization: Token ' . $ptoken,
        'Content-Type: application/json'
    )
));

// send request
$gpsresponse = curl_exec($curl);

// set up a default path at the home lat, lon in case the request fails
$path = "LINESTRING($lon $lat,$lon $lat)";
$errors = '';

// if no errors, then use regular expressions to strip out the coordinates
// and reformat them into OGC geometry LINESTRING object as a string
if($gpsresponse !== FALSE and strpos($gpsresponse, 'errors') === false){

    $pat1 = '/^.*<coordinates>/Ds';
    $sub1 = 'LINESTRING(';
    $pat2 = '/<\/coordinates>.*$/Ds';
    $sub2 = ')';
    $pat3 = '/ /';
    $sub3 = '@';
    $pat4 = '/,/';
    $sub4 = ' ';
    $pat5 = '/@/';
    $sub5 = ',';


    $path = preg_replace($pat1, $sub1, $gpsresponse);
    $path = preg_replace($pat2, $sub2, $path);
    $path = preg_replace($pat3, $sub3, $path);
    $path = preg_replace($pat4, $sub4, $path);
    $path = preg_replace($pat5, $sub5, $path);

}
else if (strpos($gpsresponse, 'errors') !== false) {
    // otherwise set the error string here
    $errors = $gpsresponse;
}

// Close the cURL handler for this request
curl_close($curl);

/*
// print out some debug info in case we need it
echo "<pre>------------------------------\n";
echo "When: $age ago\n";
echo "lat: $lat\n";
echo "lon: $lon\n";
echo "speed: $speed\n";
echo "altitude: $altitude\n";
echo "path: $path\n";
 */

// now output the webpage
// this page use an NPM project to generate the javascript for the
// OpenLayers mapping application and overlays the gps track "gpsPath"
// that we extracted above in the PHP.
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title><?php echo $title?></title>
    <!-- Pointer events polyfill for old browsers, see https://caniuse.com/#feat=pointer -->
    <script src="https://unpkg.com/elm-pep"></script>
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v3/polyfill.min.js?features=fetch,requestAnimationFrame,Element.prototype.classList,URL,TextDecoder,Number.isInteger"></script>
<script>
        // transfer values from php into Javascript variables
        var lat = <?php echo $lat?>;
        var lon = <?php echo $lon?>;
        var speed = '<?php echo $speed?>';
        var altitude = '<?php echo $altitude?>';
        var gpsPath = '<?php echo $path?>';
    </script>
    <style>
        .map {
            width: 100%;
            height:600px;
        }
    </style>
    <link rel="stylesheet" href="main.1f19ae8e.css">
    <script src="main.1f19ae8e.js"></script>
    </head>
    <body>
        <div id="map" class="map"></div>
        <div id="info">
        <p>Data from <?php echo $age?> ago.<br>
lat: <?php echo $lat?><br>
lon: <?php echo $lon?><br>
speed: <?php echo $speed?><br>
altitude: <?php echo $altitude?><br>
errors: <?php echo $errors?><br>
<!-- path: <?php echo $path?> -->
</p>
<?php
/*
    echo "<pre>\n";
    print_r($responseData);
    echo "</pre>\n";
 */
?>
        </div>
        <script src="main.1f19ae8e.js"></script>
    </body>
</html>
