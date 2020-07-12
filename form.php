<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lexicon - Add</title>
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

include_once './functions.php';

if (isset($_SESSION['logged_in'])) {
	//decodes json
	$jsonString = file_get_contents('./dictionary.json');
	$jsonData = json_decode($jsonString, true);

	//initializes the variables
	$wordPhrase = $prepos = $gender = $translation = $lang = $uid = '';


?>

			<div class="paper container margin-top flex-center text-center">
				<form class="text-center form-group" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<fieldset class="form-group">
						<span class="row flex-center">
							<label for="norwegian" class="paper-radio margin-right">
								<input id="norwegian" type="radio" name="lang" value="Norwegian" <?php if (isset($lang) && $lang=='Norwegian') echo "checked"; ?> />
								<span>Norwegian</span>
							</label>
							<label for="swedish" class="paper-radio">
								<input id="swedish" type="radio" name="lang" value="Swedish" <?php if (isset($lang) && $lang=='Swedish') echo "checked"; ?> />
								<span>Swedish</span>
							</label>
						</span>
					</fieldset>

					<div class="row flex-center">
						<label for="wordPhraseId" class="margin-right-small">Word-Phrase:</label>
						<input id="wordPhraseId" type="text" name="wordPhrase" value="<?php echo $wordPhrase; ?>">
					</div>

					<div class="row flex-center">
						<label for="prepId" class="margin-right-small">Preposition</label>
						<input id="prepId" type="text" name="prepos" value="<?php echo $prepos; ?>">
					</div>

					<div class="row flex-center">
						<label for="genderId" class="margin-right-small">Gender:</label>
						<input id="genderId" type="text" name="gender" value="<?php echo $gender; ?>">
					</div>

					<div class="row flex-center">
						<label for="wordPhraseId" class="margin-right-small">Translation:</label>
						<input id="translationId" type="text" name="translation" value="<?php echo $translation; ?>">
					</div>

					<button type="submit" name="submitBtn">Create entry</button>
				</form>
			
<?php

	//if submit button is pressed
	if (isset($_POST['submitBtn'])) {

		//checks if language option is selected
		if (empty($_POST["lang"])) {
			$lang = "Norwegian";
		}
		else {
			$lang = sanitize_input_lowLevel($_POST["lang"]);
		}

		//checks if the word and translation are set
		if (empty($_POST["wordPhrase"]) || (empty($_POST['translation']))) {
			$requiredNotMet = true;
		}
		else {
			$wordPhrase = sanitize_input_lowLevel($_POST["wordPhrase"]);
			$wordPhrase = filter_var($wordPhrase, FILTER_SANITIZE_STRING);

			$translation = sanitize_input_lowLevel($_POST["translation"]);
			$translation = filter_var($translation, FILTER_SANITIZE_STRING);

			foreach($jsonData as $dictionary) {
				if ($dictionary['uid'] !== 1) {
					if($dictionary['wordphrase'] == $wordPhrase) {
						$wordPhraseExists = true;
					}
				}
			}
		}

		//checks for preposition and gender
		if (!empty($_POST['prepos'])) {
			$prepos = sanitize_input_lowLevel($_POST["prepos"]);
			$prepos = filter_var($prepos, FILTER_SANITIZE_STRING);
		}
		else {
			$prepos = "-";
		}

		if (!empty($_POST['gender'])) {
			$gender = sanitize_input_lowLevel($_POST["gender"]);
			$gender = filter_var($gender, FILTER_SANITIZE_STRING);
		}
		else {
			$gender = "-";
		}

		

		
		function checkUid($array){

			//creates unique id
			$uid = mt_rand();

			//checks if id exists
			foreach($array as $dictionary) {
				if($dictionary['uid'] === $uid) {
					$uid = mt_rand();
					checkUid();
				}
			}
			
			return $uid;
		}

		if (isset($wordPhraseExists)) {
			echo "<h4 class='text-danger'>This word or phrase already exists. Try again!</h4>";
		}
		elseif (isset($requiredNotMet)) {
			echo "<h4 class='text-danger'>Word or phrase and translation fields are required!</h4>";
		}
		else {

			//json lenth and store new data
			$count = count($jsonData);
			$jsonData[$count]['uid'] = checkUid($jsonData);
			$jsonData[$count]['language'] = $lang;
			$jsonData[$count]['wordphrase'] = $wordPhrase;
			$jsonData[$count]['preposition'] = $prepos;
			$jsonData[$count]['gender'] = $gender;
			$jsonData[$count]['translation'] = $translation;
			

			//encodes json
			$newJsonString = json_encode($jsonData);

			//writes changes to file
			file_put_contents('./dictionary.json', $newJsonString);

			//variable to show or hide review of entry
			$showReview = true;
		}
		
	}

	if(isset($showReview)) {

		if ($prepos == '-') {
			$preposEcho = '';
		}
		else {
			$preposEcho = $prepos;
		}

		echo '
		<div class="card">
			<div class="card-body">
				<h3 class="card-title">Word or phrase: '.$preposEcho.' '.$wordPhrase.'</h3>
				<h4 class="card-text">Translation: '.$translation.'</h4>
				<p class="card-subtitle">Language: '.$lang.' / Gender: '.$gender.'</p>
			</div>
		</div>
		';
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