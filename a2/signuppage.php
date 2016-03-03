<?php
	
	
	session_save_path('/student/aksoyert/www/a2/sessions/');	
	session_start();
	$username = $_POST['user'];
	$password = $_POST['pass'];
	$image_name = $_FILES['img']['name'];
	$default_img_name = $_POST['picturelist'];
	$final_img;
	$target_dir = "";
	$target_file = $target_dir . basename($_FILES["img"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	
	$dbconn = pg_connect("host=localhost dbname=UTORID user=UTORID password=PASSWORD");
	if (!$dbconn){
		
		$_SESSION['error'] = 'Cant connect to the database!';
		header('Location: errorpage.php');
		exit;
	}

	// check if the username already exists
	$query = "select count(*) from (select * from profile where username='" . ($username) . "') as foo;";
	$result = pg_query($query) or die('Query failed:' . pg_last_error());
	
	$line = pg_fetch_row($result);
	
	if ($line[0] == 1)
	{
		$_SESSION['error'] = 'Username already exists!';
		header('Location: errorpage.php');
		exit;
	}

	// check if the use username starts with alphabet 

	$first_char = substr($username, 0,1);
	
	if (!ctype_alpha($first_char))
	{
		$_SESSION['error'] = 'Your username does not start with an alphabet character!';
		header('Location: errorpage.php');
		exit;
	}
	
	// check contains only alphabets, numbers or underscore "_"
	
	if (preg_match('/[^A-Za-z0-9_]/', $username))
	{
		$_SESSION['error'] = 'Your username contains non alphabet, number or underscore characters!';
		header('Location: errorpage.php');
		exit;
	}

	// check if password is at least 6 characters long

	if (strlen($password) < 6)
	{
		$_SESSION['error'] = 'Your password is too short! It needs to be at least 6 characters!';
		header('Location: errorpage.php');
		exit;
	}

	// first check if they chose their own profile picture, if not then use the picture selected in the dropdown

	// they chose an image!
	if ($image_name != null)
	{	

		$check = getimagesize($_FILES["img"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
			} else {
				$uploadOk = 0;
			}
	
		// Check file size
		if ($_FILES["img"]["size"] > 50000) {
			$uploadOk = 0;
		}

		
		
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$uploadOk = 0;
		}
		
		
		if ($uploadOk == 0)
		{
			$_SESSION['error'] = 'Something wrong with the image file!';
			header('Location: errorpage.php');
			exit;
			
		}
		else
		{
			move_uploaded_file($_FILES['img']['tmp_name'], $target_file);
		}
		
		$final_img = $image_name;

	}

		// they didnt choose an image, so use the dropdown value
	else
	{
		$final_img = $default_img_name . ".jpg";
	}
	

	// if everything is good, then add the user to our profile table

	// first need to find the next primary key value to put for the user
	$find_pk_value = "select max(profileid) as max_profileid from profile";
	$pk_query = pg_query($find_pk_value) or die('Query failed:' . pg_last_error());

	$pk_line = pg_fetch_row($pk_query);;

	// then we can add it (the user) to the database
	$adding_query = "insert into profile (profileid, username, password, picture) values (" . ($pk_line[0] + 1) . ", '" . ($username) . "', '" . (sha1($password)) . "', '" . ($final_img) . "');";
	$adding_result = pg_query($adding_query) or die('Query failed:' . pg_last_error());


	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
	$_SESSION['image'] = $final_img; 
	chmod ($target_file,0777);

	header('Location: landingpage.html');

	
?>

