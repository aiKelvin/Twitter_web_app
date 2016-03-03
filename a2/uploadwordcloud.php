<?php
	
	session_save_path('/student/aksoyert/www/a2/sessions');
	session_start();
	

	$dbconn = pg_connect("host=localhost dbname=UTORID user=UTORID password=PASSWORD");
	if (!$dbconn){
		$_SESSION['error'] = 'Cant connect to the database!';
		header('Location: errorpage.php');
		exit;
	} 
	

	$author = $_SESSION['username'];	// author column
	$numlikes = 0;	// numberoflikes column
	$q = $_REQUEST['q'];
	echo $q;

	// now that we added the wordcloud to the database, need to display all the word clouds at the top
	
	// get every row from the wordcloud table
	
	$query = "select * from wordcloud limit 10";
		
	$result = pg_query($query) or die('Query failed:' . pg_last_error());
	
	$rows = pg_fetch_all($result);

	
	foreach($rows as $row)
	{
		echo "<div name=word_cloud_div id=word_cloud_div>" . $row['content'] . "</div>";
	}
	

	// finding next messageid to put in
	$find_pk_value = "select max(messageid) as max_messageid from wordcloud";
	$pk_query = pg_query($find_pk_value) or die('Query failed:' . pg_last_error());

	$pk_line = pg_fetch_row($pk_query);
	

	$messageid = $pk_line[0] + 1; 	// messageid column
	$picture = $_SESSION['image'];	// picture column
	$twhen = date("Y-m-d H:i:s");	// twhen column

	// content column goes here
	$content = $q;

	
	// now insert the entry into the wordcloud relation
	$adding_query = "insert into wordcloud (messageid, tstamp, author, picture, numberoflikes, content) values (" . $messageid . ", '" . ($twhen) . "', '" . ($author) . "', '" . ($picture) . "', " . ($numlikes) . ", '" . ($content) . "');";
	
	$adding_result = pg_query($adding_query) or die('Query failed: ' . pg_last_error());


	

	
	

?>
