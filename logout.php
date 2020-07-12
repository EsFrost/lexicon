<?php

	session_start();

	session_destroy();

	header('Location: ./login'); // Return to home page
	die;

?>