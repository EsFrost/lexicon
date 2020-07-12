const search = document.getElementById('search');
const matchList = document.getElementById('match-list');

// Search the dictionary.json and filter it
const searchEntries = async searchText => {
	const res = await fetch('./dictionary.json');
	const entries = await res.json();

	// Get matches to current text input
	let matches = entries.filter(entry => {

		// Skips the first entry of json file
		if (entry.uid == 1) {
			return '';
		}

		const regex = new RegExp(`^${searchText}`, 'gi');

		// Check what language is selected to show the right results
		if (document.getElementById('both').checked) {
			return entry.wordphrase.match(regex);
		}
		if (document.getElementById('norwegian').checked) {
			if (entry.language == 'Norwegian') {
				return entry.wordphrase.match(regex);
			}
			else {
				return '';
			}
		}
		if (document.getElementById('swedish').checked) {
			if (entry.language == 'Swedish') {
				return entry.wordphrase.match(regex);
			}
			else {
				return '';
			}
		}

	});

	if(searchText.length === 0) {
		matches = [];
		matchList.innerHTML = '';
	}

	outputHtml(matches);
};

// Show results in html
const outputHtml = matches => {
	if(matches.length > 0) {
		const html = matches.map(match => {
			
			(match.preposition == '-') ? match.preposition = '' : match.preposition = '('+match.preposition+')';
			return `
			<div class="card">
				<div class="card-body">
					<h3 class="card-title">Word or phrase: ${match.preposition} ${match.wordphrase}</h3>
					<h4 class="card-text">Translation: ${match.translation}</h4>
					<p class="card-subtitle">Language: ${match.language} / Gender: ${match.gender}</p>
				</div>
			</div>
			<hr>
		`}).join('');

		matchList.innerHTML = html;
	}
};

search.addEventListener('input', () => searchEntries(search.value));