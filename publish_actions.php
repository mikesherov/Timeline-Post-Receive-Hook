<?php
require_once('include.php');

//consume request vars
//http://help.github.com/post-receive-hooks/ for more info
$payload = isset($_REQUEST['payload']) ? json_decode($_REQUEST['payload'], true) : array();
$commits = isset($payload['commits']) ? $payload['commits'] : array();
$repository = isset($payload['repository']) ? $payload['repository'] : array();

//basic sanity check here...
//we could go crazy checking all conditions,
//but this is good enough for a demo
if(empty($commits) || empty($repository)){
	die(':(');
}

//initialize objects
$facebook = new Facebook(array(
  'appId'  => AppInfo::FACEBOOK_APP_ID,
  'secret' => AppInfo::FACEBOOK_APP_SECRET
));
$db_user = new Database_User();
$db_commit = new Database_Commit();
$db_repository = new Database_Repository();

//store the repo
$db_repository->set($repository)->write();

//store the commits
foreach($commits as $commit){
	$commit['repository'] = $repository['url'];
	$db_commit->set($commit);
}
$db_commit->write();

//process the commits
foreach($commits as $commit){
	$request = array();

	//grab the user in our database based on git commit's email addy
	//this is why we asked for "email" permission
	$user = $db_user->get($commit['author']['email']);

	//we need a user access token to publish actions
	//but in this context (github pinging us), we don't have it
	//so we retrieve it from the database
	//which is why we asked for "offline_access" permission
	$access_token = $user['access_token'];
	if($access_token){
		$facebook->setAccessToken($access_token);

		//publish the action, this is why we asked for "publish_actions" permission ;)
		try{
			$request['result'] = $facebook->api($user['id'].'/post_receive_hook:push', 'POST', array(
				'commit' => AppInfo::SITE_URL.'og.php?type=commit&id='.$commit['id'],
			));
		} catch (FacebookApiException $e) {
			error_log($e->getMessage());
		}
	}
}