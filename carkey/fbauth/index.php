<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Display username and profile picture</title>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
</head>
<body>
<?php 
$signed_request=$_POST['signed_request']; 
$secret="e2a3c9f11020f6b2804f3e97e2dd2e27";
$data=parse_signed_request($signed_request, $secret);

function parse_signed_request($signed_request, $secret) {
	list($encoded_sig, $payload) = explode('.', $signed_request, 2);

	// decode the data
	$sig = base64_url_decode($encoded_sig);
	$data = json_decode(base64_url_decode($payload), true);

	if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
		//error_log('Unknown algorithm. Expected HMAC-SHA256');
		return null;
	}

	// check sig
	$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
	if ($sig !== $expected_sig) {
		//error_log('Bad Signed JSON signature!');
		return null;
	}

	return $data;
}

function base64_url_decode($input) {
	return base64_decode(strtr($input, '-_', '+/'));
}

if($data != null)
{
?>

<script>

var oauth_url = 'https://www.facebook.com/dialog/oauth/';
oauth_url += '?client_id=462745573740793';
oauth_url += '&redirect_uri=' + encodeURIComponent('https://apps.facebook.com/senthilvmr/');

var oath_token="<?php echo $data['oauth_token']; ?>";
var user_id="<?php echo $data['user_id']; ?>";
var error="<?php echo $_POST['error']  ?>";

if(oath_token == "" || user_id == "")
{
	alert("You have not logged into facebook yet...");
	window.top.location = oauth_url;
}
else if(error=="access_denied")
    alert("Access denied to this application...");
else
{
    alert("Successfully logged into application...");
 }
</script>
<?php 
if($data['oauth_token'] !="" && $data['user_id'] !="")
{
?>
	<h1>You see this because you successfully logged into facebook and you authorised this application...</h1>	
	<img src="http://graph.facebook.com/<?php echo $data['user_id']; ?>/picture?type=large" ></img>
<?php 
$profile_info=file_get_contents("http://graph.facebook.com/{$data['user_id']}");
$profile_arr=json_decode($profile_info);
echo '<h3>Your Name :- ' . $profile_arr->name .'</br>Profile link :- '.$profile_arr->link.'</br> User name :- '.$profile_arr->username.'</br>Gender  :- '.$profile_arr->gender;
}
}else
	echo "Bad signed JSON signature or Request is manually entered...";
?>

</body>
</html>