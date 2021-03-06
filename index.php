<?php
//Configuration for our PHP Server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

//Make Constants using define
define('clientID', '450fb4d9e03d4b80bc76515250409144');
define('clientSecret', '682c5aa552c04c699a0c5fc588013947');
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
    return $results['data'][0]['id'];
}

//Function to print out images to screen 
function printImages($userID){
	$url = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent?client_id='.clientID.'&count=5';
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);
	// Parse through the information one by one.
	foreach ($results['data'] as  $items){
		$image_url = $items['images']['low_resolution']['url']; //going to go  through all of my results and give myself back the URL of those pictures because we want to save it in the PHp server
		echo '<img src=" '.$image_url.' "/><br/>'; 
		//calling a function to save that $image_url
		savePictures($image_url);
	}
}
//Function to save image to server
function savePictures($image_url){
	echo $image_url. '<br>'; 
	$filename = basename($image_url); //the filename is what we are storing, basename is the PHP built in method that we are using to store $images_url
	echo $filename . '<br>';

	$destination = ImageDirectory . $filename; //making sure that the image does'nt exist in our storage
	file_put_contents($destination, file_get_contents($image_url)); //goes and grabs an imagefile and stores it into our server
}


if (isset($_GET['code'])) {
    $code = $_GET['code'];
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

$userName = $results['user']['username'];

$userID = getUserID($userName);

printImages($userID);
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