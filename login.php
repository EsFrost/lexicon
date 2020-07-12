<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lexicon - Login</title>
        <link rel="icon" type="image/png" href="./src/site-logo.png">
        <link rel="stylesheet" href="./css/paper.min.css">
        <link rel="stylesheet" href="./css/style.css">
    </head>
	<body>

<?php

// Starts the session
session_start();

include_once './creds.php';
include_once './functions.php';

if (isset($_SESSION['logged_in'])) {

	header('Location: ./admin');

}
else {
	?>

	<div class="margin-top paper container flex-center text-center">
		<form class="text-center" action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
			<fieldset class="form-group">
					<div class="row flex-center">
						<label for="usrForm">
							<span>Username: </span>
							<input type="text" name="usrForm">
						</label>
					</div>

					<div class="row flex-center">
						<label for="pswdForm">
							<span>Password: </span>
							<input type="password" name="pswdForm">
						</label>
					</div>

					<div class="row flex-center">
						<button type="submit" name="loginBtn">
							Login
						</button>
					</div>
			</fieldset>
		</form>
	

	<?php

if (isset($_POST['loginBtn'])) {
	
	if (empty($_POST['usrForm']) || empty($_POST['pswdForm'])) {
		echo '<p class="text-danger">All fields are required</p>';
	}
	else {
		$usrForm = filter_var(sanitize_input_lowLevel($_POST['usrForm']), FILTER_SANITIZE_STRING);
		$pswdForm = filter_var(sanitize_input_lowLevel($_POST['pswdForm']), FILTER_SANITIZE_STRING);
		$pswdForm = md5($pswdForm);

		if ($usrForm == $username && $pswdForm == $password) {
			$_SESSION['logged_in'] = true;
			header('Location: ./admin'); // Return to home page
		}
		else {
			echo "
					<p class='text-danger'>Username or password didn't match to an account. Try again.</p>
				";
		}
	}

}

}

?>

    
        </div>
    </body>
</html>