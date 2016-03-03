<?php

	echo '<style> body{background: url(odd_wallpaper.jpg);} form {text-align: center; display:inline-block;} h1{color:#ffffcc;}</style>';
	session_save_path('/student/aksoyert/www/a2/sessions');
	session_start();

	echo '<h1>' . $_SESSION['error'] . '</h1>';

	echo '<form action="' . 'landingpage.html' . '"><input type="' . 'submit' . '" value="' . 'Go back to main page' . '"></form>';

?>
