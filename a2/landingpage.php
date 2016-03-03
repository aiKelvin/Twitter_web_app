

<?php

	session_save_path('/student/aksoyert/www/a2/sessions');
	session_start();	
	$dbconn = pg_connect("host=localhost dbname=UTORID user=UTORID password=PASSWORD");
	if (!$dbconn){
		header('Location: errorpage.html');
		exit;
	}
	
	$query = "select * from profile where username='" . ($_POST['user']) . "' and password='" . (sha1($_POST['pass'])) . "';";
        $result = pg_query($query) or die ('Query failed:' . pg_last_error());
	
	$line = pg_fetch_row($result);

	if ($line == null)
	{	
		$_SESSION['error'] = 'Invalid username or password!';
		header('Location: errorpage.php');
		exit;
	}
	else if ($line != null)
	{
		$_SESSION['username'] = $_POST['user'];
		$_SESSION['password'] = $_POST['pass'];
		$_SESSION['image'] = $line[3]; 
		header('Location: mainpage.html');
	}

?>
