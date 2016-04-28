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

<style type="text/css">
	select.dropdown {
		width: 175px;
	}
	div.label {
		display: inline-block;
		width: 175px;
		text-align: right;
		margin-right: 5px;
	}
</style>

<body id="content">

<!-- Spacing for mobile / small screen -->
<br><br>

<div>
	<div class="label">Type: </div><select id="type" class="dropdown" onchange="updateQuality()">
		<option value="">-----</option>
		<option value="scale">Scale</option>
		<option value="chord">Chord</option>
	</select>
	<br>
	<div class="label">Quality: </div><select id="quality" class="dropdown" onchange="updateKey()">
		<option value="">-----</option>
		<!-- Fill with JavaScript -->
	</select>
	<br>
	<div class="label">Key: </div><select id="key" class="dropdown" onchange="updateOctave()">
		<option value="">-----</option>
		<!-- Fill with JavaScript -->
	</select>
	<br>
	<div class="label">Octave: </div><select id="octave" class="dropdown">
		<option value="">-----</option>
		<!-- Fill with JavaScript -->
	</select>
	<br>
	<div class="label">Direction / Inversion: </div><select id="opt" class="dropdown" onchange="updateOctave()">
		<option value="">-----</option>
		<!-- Fill with JavaScript -->
	</select>
	<br>
	<div class="label">Tempo: </div><input type="range" id="tempo" class="slider" min="40" max="200" step="1" value="80" oninput="updateTempoSlider()"><input type="text" id="tempoDisplay" min="40" max="200" step="1" value="80" maxlength="3" size="3" onchange="updateTempoDisplay()"> BPM <!-- min, max, and step have no effect when type=text, but will apply when type=number -->
	<br>
	<button type="button" class="button" id="playbtn" onclick="playSelected()">
		Play
	</button>
	<button type="button" class="button loading" id="loadbtn" disabled="true" style="display: none; color: black">
		Loading...
	</button>
	<button type="button" class="button" id="stopbtn" style="display: none" onclick="stop()">
		Stop
	</button>
</div>

<script type="text/javascript">
	const NULL_OPTION = "<option value=''>-----</option>";

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
		quality.innerHTML = NULL_OPTION;
		document.getElementById("key").innerHTML = NULL_OPTION;
		opt.innerHTML = NULL_OPTION;
		document.getElementById("octave").innerHTML = NULL_OPTION;

		if (type.value == "scale") {
			// fill quality with possible scale qualities
			for (var i = 0; i < scale_opt.length; i++) {
				quality.innerHTML = quality.innerHTML + "<option value='"+scale_opt[i]+"'>"+displayQuality(scale_opt[i])+"</option>";
			}
			// fill opt with scale options
			opt.innerHTML =
				"<option value='asc'>Ascending</option>\
				<option value='desc'>Descending</option>";
		}
		else if (type.value == "chord") {
			// fill quality with possible chord qualities
			for (var i = 0; i < chord_opt.length; i++) {
				quality.innerHTML = quality.innerHTML + "<option value='"+chord_opt[i]+"'>"+displayQuality(chord_opt[i])+"</option>";
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
		var type = document.getElementById("type");
		var quality = document.getElementById("quality");
		var key = document.getElementById("key");

		// always clear octave when we change available keys
		document.getElementById("octave").innerHTML = NULL_OPTION;

		// if quality deselected, reset key
		if (quality.value == "") {
			key.innerHTML = NULL_OPTION;
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
			key.innerHTML = NULL_OPTION;
			for (var i = 0; i < keys.length; i++) {
				key.innerHTML = key.innerHTML + "<option value='" + keys[i] + "'>" + displayNote(keys[i]) + "</option>";
			}
		}
	}

	function updateOctave() {
		var type = document.getElementById("type");
		var quality = document.getElementById("quality");
		var key = document.getElementById("key");
		var octave = document.getElementById("octave");
		var opt = document.getElementById("opt");

		// on key deselect, deselect octave
		if (key.value == "") {
			octave.innerHTML = NULL_OPTION;
		}
		else {
			// get notes
			var notes;
			for (var i = 0; i < data.length; i++) {
				if (data[i][0] == type.value && data[i][1] == quality.value) {
					notes = data[i][2];
					break;
				}
			}

			// transpose notes
			var newOrdinal = ordinal(key.value);
			var origOrdinal = ordinal(notes[0]);
			notes = transpose(notes, newOrdinal - origOrdinal);

			// apply inversion
			if (opt.value == "first") {
				notes = setInversion(notes, 1);
			}
			else if (opt.value == "second") {
				notes = setInversion(notes, 2);
			}
			else if (opt.value == "third") {
				notes = setInversion(notes, 3);
			}
			

			// get valid octaves
			var octaves = getOctaveLocations(notes);

			// display the octaves
			if (octaves == null) {
				alert("Please select a different key.");
				return false;
			}

			octave.innerHTML = "";
			for (var i = 0; i < octaves.length; i++) {
				octave.innerHTML = octave.innerHTML + "<option value='" + octaves[i] + "'>" + octaves[i] + "</option>";
			}
		}
	}

	// 3rd word is which one new input is from
	function updateTempoSlider() {
		document.getElementById("tempoDisplay").value = document.getElementById("tempo").value;
	}
	function updateTempoDisplay() {
		var tempoDisplay = document.getElementById("tempoDisplay");
		tempoDisplay.value = Math.max(40, Math.min(200, parseInt(tempoDisplay.value))); // limit range of values
		document.getElementById("tempo").value = tempoDisplay.value;
	}

	var snippet; // this needs to be visible to playSelected() and stop()

	// plays selected thing
	function playSelected() {
		document.getElementById("playbtn").style.display = "none"; // hide the play button
		document.getElementById("loadbtn").style.display = "block"; // display loading message

		var type = document.getElementById("type").value;
		var quality = document.getElementById("quality").value;
		var key = document.getElementById("key").value;
		var octave = document.getElementById("octave").value;
		var opt = document.getElementById("opt").value;

		// console.log("Playing a " + quality + " " + type + " in " + key);

		// checking inputs
		if (octave == "") { // octave is only not blank when everything else is selected
			// reset buttons
			document.getElementById("loadbtn").style.display = "none";
			document.getElementById("playbtn").style.display = "block";
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
		snippet = new MusicSnippet(data[i][2], type, quality, data[i][3]);

		// set snippet tempo
		snippet.setBPM(document.getElementById("tempo").value);

		// on-load function to pass to the generate function
		var on_load = function() {
			document.getElementById("loadbtn").style.display = "none"; // change buttons
			document.getElementById("stopbtn").style.display = "block";
			snippet.play(opt);
		}

		// inversions
		if (opt == "first") {
			snippet.generate(on_load, key, 1, octave);
		}
		else if (opt == "second") {
			snippet.generate(on_load, key, 2, octave);
		}
		else if (opt == "third") {
			snippet.generate(on_load, key, 3, octave);
		}
		else {
			snippet.generate(on_load, key, 0, octave);
		}
	}

	function stop() {
		snippet.fadeOut();
		document.getElementById("stopbtn").disabled = true;
		setTimeout(function() { // delay the return of the play button until sound has faded
			document.getElementById("playbtn").style.display = "block";
			document.getElementById("stopbtn").style.display = "none";
			document.getElementById("stopbtn").disabled = false;
		}, FADE_ALL_LENGTH+1);
	}

	// changes sharps and flats in a note name from internal representation to the actual unicode characters
	function displayNote(note) {
		note = note.replace("bb", "&#9837;&#9837;");
		note = note.replace("b", "&#9837;");
		note = note.replace("xx", "&#9839;&#9839;");
		note = note.replace("x", "&#9839;");
		return note;
	}

	function displayQuality(quality) {
		quality = quality.replace(/\(b/, "(&#9837;");
		quality = quality.replace(/\/b/, "/&#9837;");
		quality = quality.replace(/\(x/, "(&#9839;");
		quality = quality.replace(/\/x/, "/&#9839;");
		return quality;
	}
</script>

</body>

<?php require_once("phpincludes/footer.php"); ?>
