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

if isset(($GET['code'])) {
    $code = ($GET['code']);
    $url = 'https://api.instagram.com/oauth/access_token';
    $access_token_settings = array('client_id' => clientID, 
                                   'client_secret' => clientSecret,
                                   'grant_type' => 'authorization_code',
                                   'redirect_uri' => redirectURI,
                                   'code' => $code
                                    );
}


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
		<a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI?>&response_type=code">LOGIN</a>
		<script src="js/main.js"></script>
	</body>
</html>
