<!DOCTYPE html>
<html>
<?php
require_once('include.php');

//consume request vars
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'commit';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;

//basic sanity check
if(!$id){
	die(':(');
}

if($type == 'commit'){
	//facebook crawls each commit when we publish_action
	//and specify a url it has not seen before
	//so we display the relevant info
	$db_commit = new Database_Commit();
	$object = $db_commit->get($id);
	print OpenGraphObject::markup(array(
		'og:title' => substr($object['id'], 0, 10),
		'og:image' => 'https://a248.e.akamai.net/assets.github.com/images/modules/header/logov6-hover.svg',
		'og:description' => 'Description',
		'og:type' => 'post_receive_hook:commit',
		//we've defined that every commit has a repository it belongs to
		//this will cause facebook to also crawl this url if it hasn't
		'post_receive_hook:repository' =>AppInfo::SITE_URL.'og.php?type=repository&id='.$object['repository'],
	));
}else{
	//because each commit contains a url to a repo,
	//we also get crawled for the repo!
	$db_repository = new Database_Repository();
	$object = $db_repository->get($id);
	print OpenGraphObject::markup(array(
		'og:title' => $object['name'],
		'og:type' => 'post_receive_hook:repository',
		'og:description' => 'Description',
		'og:image' => 'https://a248.e.akamai.net/assets.github.com/images/modules/header/logov6-hover.svg',
	));
}
?>
	</head>
	<body>
		<script>
		//this is here because the object's url is actually on github
		//this og.php page is just for fb to crawl
		//fb's crawl doesn't follow javascript redirects
		//but when a user lands here, we redir them to the relevant github commit
		window.top.location = '<?php echo($object['url']); ?>';
		</script>
	</body>
</html>