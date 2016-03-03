<?php

	$dbconn = pg_connect("host=localhost dbname=UTORID user=UTORID password=PASSWORD");
	if (!$dbconn){
		$_SESSION['error'] = 'Cant connect to the database!';
		header('Location: errorpage.php');
		exit;
	} 


	$query = "select * from wordcloud order by tstamp desc";

	$result = pg_query($query) or die('Query failed:' . pg_last_error());
	
	$rows = pg_fetch_all($result);

	
	foreach($rows as $row)
	{
		echo "<div name=word_cloud_div id=word_cloud_div>" . $row['content'] . "</div>";
	}
?>
