<?php
require_once('include.php');

//initialize objects
$facebook = new Facebook(array(
  'appId'  => AppInfo::FACEBOOK_APP_ID,
  'secret' => AppInfo::FACEBOOK_APP_SECRET
));

//get facebook user id and facebook info
$facebook_uid = $facebook->getUser();
if ($facebook_uid) {
  try {
    $facebook_user = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    $facebook_uid = null;
  }
}

//log in if we have no facebook user id
if (!$facebook_uid) {
  header('Location: '.$facebook->getLoginUrl(array(
  	'scope' => 'publish_actions,offline_access,email')
  ));
  die();
}

//get user from db by facebook email addy
$db_user = new Database_User();
$user = $db_user->get($facebook_user['email']);

//if user doesnt exist, store in db
//we'll need email address, user id, and access token later
//when the post-receive ping comes in
if(empty($user)){
	$user['access_token'] = $facebook->getAccessToken();
	$user['email'] = $facebook_user['email'];
	$user['id'] = $facebook_uid;
	$db_user->set($user)->write();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Timeline Post Receive Hook</title>
	</head>
	<body>
	<div id="container">
		<div>
			<h1>Timeline Post Receive Hook</h1>
			<p>Easily Connect Github to your Facebook Timeline!</p>
		</div>
		<div>Hello! You've successfully authenticated with the email address: <?php echo($user['email']); ?></div>
		<div>Please make sure this matches your github email address!</div>
		<div>Please place the following url in github post-receive url: <?php echo(AppInfo::SITE_URL); ?>publish_actions.php</div>
	</div>
	</body>
</html>