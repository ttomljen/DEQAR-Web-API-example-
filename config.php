<?php

//Please replace the information in quotes with your information

$username='your_username';
$password='your_password';
$agency_acronym='your_agency_acronym';   //If there is a space in the acronym then use '%20' to change the space, if you are not sure what your acronym is you can find it on the admin interface (https://admin.deqar.eu/) under 'Reference data' -> 'Agencies'

$agency_name='your_agency_name';   


////////////////////////////////////////////////////////////////////////////////////////////////////////

//The code snippet below is required to retrieve the authorization token using a username and password

//The url you wish to send the POST request to
$url = 'https://backend.deqar.eu/accounts/get_token/';

//The data you want to send via POST
$fields = array('username' => $username, 'password' => $password);

//url-ify the data for the POST
$fields_string = http_build_query($fields);

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

//execute post
$result = curl_exec($ch);

$explode_result= explode('"', $result);


$authToken= $explode_result[3];




return array(

    'agency_name' => $agency_name,
    'agency_acronym' => $agency_acronym,
    'username' => $username,
    'password' => $password,
    'authToken' => $authToken,


);