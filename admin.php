<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lexicon - Home</title>
        <link rel="icon" type="image/png" href="./src/site-logo.png">
        <link rel="stylesheet" href="./css/paper.min.css">
        <link rel="stylesheet" href="./css/style.css">
    </head>
	<body>

<?php

session_start();

if (isset($_SESSION['logged_in'])) {
	?>

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

		<div class="margin-top">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<div class="row flex-center">
					<button type="submit" name="sortBtn">
						Sort dictionary
					</button>
				</div>
			</form>

            <div class="row flex-center">Type:&nbsp;<strong class="text-secondary">[show all]</strong>&nbsp;in search box to see all entries.</div>
		</div>

		<div class="container">
			<div class="row flex-center">
				<div class="col-12 col">
					<h1 class="text-center">Nordic Dictionary</h1>

					<div class="form-group text-center">
						<fieldset class="form-group">
							<span class="row flex-center">
								<label for="both" class="paper-radio">
									<input id="both" type="radio" name="lang" value="Both" checked>
									<span>Both</span>
								</label>
								<label for="norwegian" class="paper-radio margin-left">
									<input id="norwegian" type="radio" name="lang" value="Norwegian">
									<span>Norwegian</span>
								</label>
								<label for="swedish" class="paper-radio margin-left">
									<input id="swedish" type="radio" name="lang" value="Swedish">
									<span>Swedish</span>
								</label>
							</span>
						</fieldset>

						<div class="text-center row">
							<div class="col emtpy-div sm-3 md-3 lg-3">
							</div>
							<div class="col sm-6 md-6 lg-6">
								<input type="text" class="input-block" id="search" placeholder="Enter word or phrase.">
							</div>
						</div>
					</div>

					<div id="match-list">
						
					</div>
				</div>
			</div>
		</div>

	<script src="./js/main-admin.js"></script>	

	<?php
	//Sorts the dictionary (asc with the value of wordphrase)
	if (isset($_POST['sortBtn'])) {

        //decodes json
        $jsonString = file_get_contents('./dictionary.json');
        $data = json_decode($jsonString, true);

		usort($data, function($a, $b) {
			if($a['uid'] == 1 || $b['uid'] == 1) {
				return;
			}
			else {
				return $a['wordphrase'] <=> $b['wordphrase'];
			}
		});

		//encodes json
		$newJsonString = json_encode($data);

		//writes changes to file
		file_put_contents('./dictionary.json', $newJsonString);

		header('Location: ./admin');
	}

	
}
else {
	header('Location: ./login');
}


?>
	</body>
</html>