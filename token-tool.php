<?php
// vrm info
$installationID = '******';
$user = '******';
$userID = '******';
$pass = '******';

// login to portal
$postData = array(
    'username'=> $user
    ,'password'=> $pass
    //,'sms_token'=>'8005131' // After step 1, you get an sms code
                              // put here and uncomment for step2
);

/*
// Step 1 and 2

$curl = curl_init('https://vrmapi.victronenergy.com/v2/auth/login');
curl_setopt_array($curl, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    )
    ,CURLOPT_POSTFIELDS => json_encode($postData)
));


// send request
$response = curl_exec($curl);

// Check for errors
if($response === FALSE){
    die(curl_error($curl));
}

// Decode the response
$responseData = json_decode($response, TRUE);

// Close the cURL handler
curl_close($curl);

echo '<pre>';
print_r($responseData);


exit;

 */
/*

// Step 3

// copy the token from the response above in step 2 into $token here
// this is a short lived token and it auto expires
// ALSO copy the userID from the response into the variable at top of script
$token = '<Copy token from response above>';

$postData = array(
    'name' => "Tracking #1"
);

// create a new permanent token named "Tracking #1"
$curl = curl_init("https://vrmapi.victronenergy.com/v2/users/$userID/accesstokens/create");
curl_setopt_array($curl, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        'X-Authorization: Bearer ' . $token, // Note Bearer for temp tokens
        'Content-Type: application/json'
    )
    ,CURLOPT_POSTFIELDS => json_encode($postData)
));


// send request
$response = curl_exec($curl);

// Check for errors
if($response === FALSE){
    die(curl_error($curl));
}

// Decode the response
$responseData = json_decode($response, TRUE);


// Close the cURL handler
curl_close($curl);

echo '<pre>';
print_r($responseData);


exit;

//(
//    [success] => 1
//    [token] => <copy this value into $ptoken below>
//    [idAccessToken] => 125370
//)

 */

/*

// Step 4

// DO NOT LOOSE THIS TOKEN, THERE IS NO WAY TO RECOVER IT
// Your only option is to delete is and create a new token
    
    $ptoken = '<copied from response above';

// here you can list your permanent access tokens or try fetching the GPS data
//$curl = curl_init("https://vrmapi.victronenergy.com/v2/installations/$installationID/widgets/GPS");
$curl = curl_init("https://vrmapi.victronenergy.com/v2/users/$userID/accesstokens/list");
curl_setopt_array($curl, array(
    CURLOPT_POST => FALSE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => array(
        "X-Authorization: Token $ptoken",  // Note "Token" for permanent tokens
        'Content-Type: application/json'
    )
));

// send request
$response = curl_exec($curl);

// Check for errors
if($response === FALSE){
    die(curl_error($curl));
}

// Decode the response
$responseData = json_decode($response, TRUE);

echo '<pre>';
print_r($responseData);

// Close the cURL handler
curl_close($curl);

$start = strtotime('-3 days');
$end = strtotime('now');

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
$response = curl_exec($curl);

// Check for errors
if($response === FALSE){
    die(curl_error($curl));
}

echo '<pre>';
print_r($response);

// Close the cURL handler
curl_close($curl);

 */

?>
