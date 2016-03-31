<?php 
	$thisPage = 'Review';
	require_once("phpincludes/header.php");
	echo "<title>$thisPage</title>";
?>

<script src="howler/howler.js"></script>
<script src="scripts/MusicSnippet.js"></script>
<script src="scripts/noteToFileNum.js"></script>
<script src="scripts/MusicManip.js"></script>
<script src="scripts/QuestionGenerator.js"></script>
<script src="scripts/ParseCSV.js"></script>

<body>

<form>
	<select id="type" onchange="updateQuality()">
		<option value="">-----</option>
		<option value="scale">Scale</option>
		<option value="chord">Chord</option>
	</select>
	<br>
	<select id="quality" onchange="updateKey()">
		<option value="">-----</option>
		<!-- Fill with JavaScript -->
	</select>
	<br>
	<select id="key">
		<option value="">-----</option>
		<!-- Fill with JavaScript -->
	</select>
	<br>
	<select id="opt">
		<option value="">-----</option>
		<!-- Fill with JavaScript -->
	</select>
	<br>
	<input type="range" id="tempo" min="40" max="200" step="1" value="80" onchange="updateTempo()"><span id="tempoDisplay">80 BPM</span>
	<br>
	<button type="button" onclick="playSelected()">
		Play Selected
	</button>
</form>

<script type="text/javascript">
	// prep arrays for dropdowns
	var scale_opt = [];
	var chord_opt = [];
	// scan data
	for (var i = 0; i < data.length; i++) {
		if (data[i][0] == "scale") {
			scale_opt.push(data[i][1]);
		}
		else if (data[i][0] == "chord") {
			chord_opt.push(data[i][1]);
		}
	}

	// make dropdowns change options when an option is selected
	function updateQuality() {
		var type = document.getElementById('type');
		var quality = document.getElementById('quality');
		var opt = document.getElementById('opt');

		// clear quality and key dropdowns
		quality.innerHTML = "<option value=''>-----</option>";
		document.getElementById('key').innerHTML = "<option value=''>-----</option>";
		opt.innerHTML = "<option value=''>-----</option>";

		if (type.value == "scale") {
			// fill quality with possible scale qualities
			for (var i = 0; i < scale_opt.length; i++) {
				quality.innerHTML = quality.innerHTML + "<option value='"+scale_opt[i]+"'>"+scale_opt[i]+"</option>";
			}
			// fill opt with scale options
			opt.innerHTML = 
				"<option value='asc'>Ascending</option>\
				<option value='desc'>Descending</option>";
		}
		else if (type.value == "chord") {
			// fill quality with possible chord qualities
			for (var i = 0; i < chord_opt.length; i++) {
				quality.innerHTML = quality.innerHTML + "<option value='"+chord_opt[i]+"'>"+chord_opt[i]+"</option>";
			}
			// fill opt with chord options
			opt.innerHTML = 
				"<option value='root'>Root Position</option>\
				<option value='first'>First Inversion</option>\
				<option value='second'>Second Inversion</option>\
				<option value='third'>Third Inversion</option>";
		}
	}

	function updateKey() {
		var type = document.getElementById('type');
		var quality = document.getElementById('quality');
		var key = document.getElementById('key');

		// if quality deselected, reset key
		if (quality.value == "") {
			key.innerHTML = "<option value=''>-----</option>";
		}
		else {
			// find first note of selected thing
			var first;
			for (var i = 0; i < data.length; i++) {
				if (data[i][0] == type.value && data[i][1] == quality.value) {
					first = data[i][2][0];
					break;
				}
			}

			// find index in NOTES
			var first_ord = ordinal(first);

			// put 6 notes before and after into list
			var keys = [];
			for (var i = -6; i < 6; i++) {
				keys.push(NOTES[first_ord+i]);
			}

			// alphabetize list
			keys.sort();

			// convert list to option tags
			key.innerHTML = "";
			for (var i = 0; i < keys.length; i++) {
				key.innerHTML = key.innerHTML + "<option value='" + keys[i] + "'>" + keys[i] + "</option>";
			}
		}
	}

	function updateTempo() {
		document.getElementById("tempoDisplay").innerHTML = document.getElementById("tempo").value + " BPM";
	}

	/**
	 When we play the selected chord or scale, we want to wait for the files to load first.  If we use a busy wait, the browser will complain about an unresponsive script, and the user must allow the script to continue.  Javascript has no sleep capability, so we use setInterval instead to simulate the effect.  We want setInterval to stop once the files are loaded, but only the function called by setInterval knows when that is.  Javascript is entirely pass-by-value, so in order for loadCheck to know which interval to clear, the variable must be visible to both setInterval and the function it calls.

	 Of course, we only need to do this if we load the sounds in generate(), not play()
	 */
	var load_wait_intervalID;

	// plays selected thing
	function playSelected() {
		document.getElementById("loading").style.display = "block"; // display loading message
		var type = document.getElementById('type').value;
		var quality = document.getElementById('quality').value;
		var key = document.getElementById('key').value;
		var opt = document.getElementById('opt').value;

		// console.log("Playing a " + quality + " " + type + " in " + key);
		
		// checking inputs
		if (key == "") { // key is only not blank when everything else is selected
			document.getElementById("loading").style.display = "none"; // clear the loading message
			alert("Please select a scale or chord.");
			return false;
		}

		// find csv index of selected
		var i;
		for (i = 0; i < data.length; i++) {
			if (data[i][0] == type && data[i][1] == quality) {
				break;
			}
		}

		// generate snippet with selected type, quality, and key
		var snippet = new MusicSnippet(type, quality, data[i][2], data[i][3]);
		// inversions
		if (opt == "first") {
			snippet.generate(key, 1);
		}
		else if (opt == "second") {
			snippet.generate(key, 2);
		}
		else if (opt == "third") {
			snippet.generate(key, 3);
		}
		else {
			snippet.generate(key, 0);
		}

		snippet.setBPM(document.getElementById("tempo").value);

		// wait for files to load, then play snippet
		load_wait_intervalID = setInterval(loadCheck, 1000, snippet, opt);

		// if generate() doesn't load the sound files, we can play() immediately
		// snippet.play();
	}

	// ends the setInterval when files are loaded
	function loadCheck(snippet, opt) {
		if (document.getElementById("loading").style.display == "none") {
			clearInterval(load_wait_intervalID); // stop waiting/looping
			snippet.play(opt);
		}
	}
</script>

<div id="loading" style="display: none">Loading...</div>
<br id="allbuttons">

</body>

<?php require_once("phpincludes/footer.php"); ?>