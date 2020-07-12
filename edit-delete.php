<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lexicon - Edit</title>
        <link rel="icon" type="image/png" href="./src/site-logo.png">
        <link rel="stylesheet" href="./css/paper.min.css">
        <link rel="stylesheet" href="./css/style.css">
    </head>
	<body>

		<nav class="border split-nav">
			<div class="nav-brand">
				<h3>
					<a href="./admin">Lexicon - Nordic dictionary</a>
				</h3>
			</div>

			<div class="collapsible">
				<input type="checkbox" id="collapsible1" name="collapsible1">
				<button>
					<label for="collapsible1">
						<div class="bar1"></div>
						<div class="bar2"></div>
						<div class="bar3"></div>
					</label>
				</button>
				<div class="collapsible-body">
					<ul class="inline">
						<li><a href="./form">Add entry</a></li>
						<li><a href="./logout">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>
<?php

session_start();

error_reporting(E_ALL ^ E_NOTICE);

include_once './functions.php';

if (isset($_SESSION['logged_in'])) {

	//decodes json
	$jsonString = file_get_contents('./dictionary.json');
	$data = json_decode($jsonString, true);

	$uid = $_GET['uid'];
	$count = count($data);

	for ($i = 1 ; $i < $count ; $i++) {
		if (isset($data[$i])) {
			if ($data[$i]['uid'] == $uid) {

	?>

	<div class="paper container margin-top flex-center text-center">
		<form class="text-center form-group" method="post" action="<?php $_SERVER['PHP_SELF'];?>">
			<fieldset class="form-group">
				<span class="row flex-center">
					<label for="norwegian" class="paper-radio margin-right">
						<input id="norwegian" type="radio" name="lang" value="Norwegian" <?php if ($data[$i]['language'] == 'Norwegian') echo "checked"; ?>>
						<span>Norwegian</span>
					</label>
					<label for="swedish" class="paper-radio">
						<input id="swedish" type="radio" name="lang" value="Swedish" <?php if ($data[$i]['language'] == 'Swedish') echo "checked"; ?>>
						<span>Swedish</span>
					</label>
				</span>
			</fieldset>

			<div class="row flex-center">
				<label class="margin-right-small" for="wordPhrase">Word-phrase: </label>
				<input type="text" name="wordPhrase" value="<?php echo $data[$i]['wordphrase'] ?>">
			</div>
			<div class="row flex-center">
				<label class="margin-right-small" for="prepos">Preposition: </label>
				<input type="text" name="prepos" value="<?php echo $data[$i]['preposition'] ?>">
			</div>
			<div class="row flex-center">
				<label class="margin-right-small" for="gender">Gender: </label>
				<input type="text" name="gender" value="<?php echo $data[$i]['gender'] ?>">
			</div>
			<div class="row flex-center">
				<label class="margin-right-small" for="translation">Translation: </label>
				<input type="text" name="translation" value="<?php echo $data[$i]['translation'] ?>">
			</div>

			<div class="row flex-center">
				<button class="margin-right-small" type="submit" name="editBtn">Edit</button>
				<button type="submit" name="deleteBtn">Delete</button>
			</div>
			
		</form>

	<?php
			}
		}
	}


	//delete button
	if (isset($_POST['deleteBtn'])) {

		for ($i = 1; $i < $count ; $i++) {
			if (isset($data[$i])) {
				if ($data[$i]['uid'] == $uid) {
					unset($data[$i]);
				}
			}
		}

		//encodes json
		$newJsonString = json_encode($data);

		//writes changes to file
		file_put_contents('./dictionary.json', $newJsonString);

		header('Location: ./admin');
	}

	//edit button
	if (isset($_POST['editBtn'])) {

		$lang = sanitize_input_lowLevel($_POST["lang"]);
		$flag = 0;
	

		if (empty($_POST['wordPhrase']) || (empty($_POST['translation']))) {
			$flag = 1;
		}

		else {
			$wordPhrase = sanitize_input_lowLevel($_POST['wordPhrase']);
			$wordPhrase = filter_var($wordPhrase, FILTER_SANITIZE_STRING);

			$translation = sanitize_input_lowLevel($_POST['translation']);
			$translation = filter_var($translation, FILTER_SANITIZE_STRING);

			foreach($data as $dictionary) {
				if ($dictionary['uid'] != $uid) {
					if($dictionary['wordphrase'] == $wordPhrase) {
						$wordPhraseExists = true;
					}
				}
			}
		}

		if (!empty($_POST['prepos'])) {
			$prepos = sanitize_input_lowLevel($_POST['prepos']);
			$prepos = filter_var($prepos, FILTER_SANITIZE_STRING);
		}
		else {
			$prepos = '-';
		}

		if (!empty($_POST['gender'])) {
			$gender = sanitize_input_lowLevel($_POST['gender']);
			$gender = filter_var($gender, FILTER_SANITIZE_STRING);
		}
		else {
			$gender = '-';
		}

		if (isset($wordPhraseExists)) {
			echo "<h3 class='row flex-center text-danger'>This word or phrase already exists. Try again!</h3>";
		}
		else {

			for ($i = 1 ; $i < $count ; $i++) {
				if (isset($data[$i])) {
					if ($data[$i]['uid'] == $uid) {
						$data[$i]['uid'] = (int)$uid;
						$data[$i]['language'] = $lang;
						$data[$i]['wordphrase'] = $wordPhrase;
						$data[$i]['preposition'] = $prepos;
						$data[$i]['gender'] = $gender;
						$data[$i]['translation'] = $translation;
					}
				}
			}

			if ($flag == 1) {
				echo "<h3 class='text-danger row flex-center'>Word-phrase and translation fields are mandatory!</h3>";
			}
			else {
				//encodes json
				$newJsonString = json_encode($data);

				//writes changes to file
				file_put_contents('./dictionary.json', $newJsonString);

				header('Location: ./edit-delete?uid='.$uid);
			}
				
		}

	}

}
else {
	header('Location: ./login');
}

?>
		</div>
		<hr>
	</body>
</html>