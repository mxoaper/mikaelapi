<?php
//Configuration for our PHP Server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

//Make Constants using define
define('clientID', '450fb4d9e03d4b80bc76515250409144');
define('clienSecret', '682c5aa552c04c699a0c5fc588013947');
define('redirectURI', 'http://localhost/mikaelapi/index.php');
define('ImageDirectory', 'pics/');


//Function that is going to connect to instagram.
function connectToInstagram($url){
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 2,
        ));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
//function to get userID cause username doesnt allow us to get pictures.
function getUserID($userName) {
    $url = 'http://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.clientID;
    $instagramInfo = connectToInstagram($url);
    $results = json_decode($instagramInfo, true);
    echo $results['data']['0']['id'];
}

if (isset($_GET['code'])) {
    $code = ($_GET['code']);
    $url = 'https://api.instagram.com/oauth/access_token';
    $access_token_settings = array('client_id' => clientID, 
                                   'client_secret' => clientSecret,
                                   'grant_type' => 'authorization_code',
                                   'redirect_uri' => redirectURI,
                                   'code' => $code
                                    );
//cURL is what we use in PHP, its a library calls to other API's
$curl = curl_init($url); //setting a cUrl session and we put in $irl because that's where we are getting the data from.
curl_setopt($curl,CURLOPT_POST,true);
curl_setopt($curl,CURLOPT_POSTFIELDS,$access_token_settings); //Setting the POSTFIELDS to the array setup that we created.
curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1); //setting it equal to 1 because we are getting strings back.
curl_setopt($curl,CURLOPT_POST, false); //but in live work-production we want to set this to true


$result = curl_exec($curl);
curl_close($curl);


$results = json_decode($result, true);
echo $results['user']['username'];
}
else{
?>

<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>
		<!-- Creating a login for people to go and give approval for our web app to access their Instagram Account -->
		<!-- After getting approval we are now going to have the information so that we can playu with this -->
		<a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI?>&response_type=code">Login</a>
		<script src="js/main.js"></script>
	</body>
</html>
<?php
}
?>