<?php

session_save_path('/student/aksoyert/www/a2/sessions');
session_start();


$search = $_POST['search'];

require_once('TwitterAPIExchange.php');
session_save_path('/student/aksoyert/www/a2/sessions');
session_start();

echo $_SESSION['username'] . "s word cloud";

echo "<br>";
echo '<img src = "' . $_SESSION['image'] . '" style="width:128px;height:128px;">';
echo "<button>Like!</button> <br>";

$settings= array(
	'oauth_access_token'=> "703612240499515393-lk4M8Yaz014Shpnssx0AcneASm0QY4W",
	'oauth_access_token_secret'=> "dZm2PwJdhvh2Tn1HvK4b5ZURjQjwFKf7Be1TTjRAtAmY2",
	'consumer_key' => "FvjWTPeH263fENdPKUDmKEJJH",
	'consumer_secret' => "BTn8EWdpWRlO69DE3uc9J0y3ZWNiVbFOThr8myeyt1Q7cZaPUN"
);

$url = 'https://api.twitter.com/1.1';
$getfield;
$requestMethod = 'GET';
$q = $_REQUEST['q'];

if((strcmp($q[0], "@")) != 0){ 
	$url = $url . '/search/tweets.json';
	$getfield = '?q=' . $_REQUEST['q'];
	$twitter = new TwitterAPIExchange($settings);
	$result = $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
			 ->performRequest();
	$jr = json_decode($result, true);
	$dict = array();
	$newArray = array();
	$blacklist = array("@", "#", "a", "an", "and", "are", "as", "at", "be", "by", "for", "from", "has", "he", "in", "is", "it", "its", "of", "on", "that", "the", "to", "was", "were", "will", "with", "we", "i", "can", "i'm", "http", "https");
		
	foreach ($jr['statuses'] as $key=>$value) {

		if ((strcmp($value['text'][0], "R") == 0) && (strcmp($value['text'][1], "T") == 0)){
			$whatIWant = substr($value['text'], strpos($value['text'], ':') + 1);
			$words = preg_split("/\s+/", $whatIWant);
			$words = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $words);
		}
		
		else{
			$words = preg_split("/\s+/", $value['text']);
			$words = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $words);
		
		}
		// removes blacklisted words (stop words)	
		foreach ($words as $w)
		{
			
			
			if(!(in_array(strtolower($w), $blacklist)))
			{
				array_push($newArray, strtolower($w));
			}
		}
	}
			
	// adding the words to the new dictionary
	foreach ($newArray as $k)
	{

		if (!isset($dict[$k]))
		{
			$dict[$k] = 1;
				
		}
				
		else
		{	
			$dict[$k] = $dict[$k] + 1;
					
		}
	}
				
}


if ((strcmp($q[0], "@")) == 0){
	$url = $url . '/statuses/user_timeline.json';
	$getfield = '?screen_name=' . substr($q, 1);
	$twitter = new TwitterAPIExchange($settings);
	$result = $twitter->setGetfield($getfield)
			->buildOauth($url, $requestMethod)
			->performRequest();
	$jr = json_decode($result, true);
	$dict = array();
	$newArray = array();
	$blacklist = array("@", "#", "a", "an", "and", "are", "as", "at", "be", "by", "for", "from", "has", "he", "in", "is", "it", "its", "of", "on", "that", "the", "to", "was", "were", "will", "with", "we", "i", "can", "i'm", "http", "https");
	
	// remove all spaces and punctuation
	foreach ($jr as $j)
	{	
		$words = preg_split("/\s+/", $j['text']);
		$words = preg_replace("/(?![.=$'€%-])\p{P}/u", "", $words);
		$words = preg_replace("/'/", "\&#39;", $words);
	
		// removes blacklisted words (stop words)	
		foreach ($words as $w)
		{
				//echo $w . "<br>";
			
			
			if(!(in_array(strtolower($w), $blacklist)))
			{
				array_push($newArray, strtolower($w));
					
			}
		}
	}
			
	// adding the words to the new dictionary
	foreach ($newArray as $k)
	{

		if (!isset($dict[$k]))
		{
			$dict[$k] = 1;
				
		}
				
		else
		{	
			$dict[$k] = $dict[$k] + 1;
					
		}
	}
}			

	// sort keys by value, highest to lowest
	arsort($dict);	

	
	foreach ($dict as $g => $val)
	{
			//echo "<li>$g => $val<li>";
	}

	// we have dictionary sorted from greatest to least, respective to values (most occuring words to least)
	$up_array = array();
	foreach ($dict as $new => $val)
	{
		if(strcmp($new, "") != 0){
			if($val >= 2){
			array_push($up_array, $sub_up_array = array($new, "`|`|||`", $val, "|`````|`|"));

			}
		}
	}

	foreach ($up_array as $x){
		foreach ($x as $y){
			echo $y;
	}
	$_SESSION['wordcloud'] = $up_array;
}
	


?>
