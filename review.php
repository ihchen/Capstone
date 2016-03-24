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

		// clear quality and key dropdowns
		quality.innerHTML = "<option value=''>-----</option>";
		document.getElementById('key').innerHTML = "<option value=''>-----</option>";

		if (type.value == "scale") {
			// fill quality with possible scale qualities
			for (var i = 0; i < scale_opt.length; i++) {
				quality.innerHTML = quality.innerHTML + "<option value='"+scale_opt[i]+"'>"+scale_opt[i]+"</option>";
			}
		}
		else if (type.value == "chord") {
			// fill quality with possible chord qualities
			for (var i = 0; i < chord_opt.length; i++) {
				quality.innerHTML = quality.innerHTML + "<option value='"+chord_opt[i]+"'>"+chord_opt[i]+"</option>";
			}
		}
	}

	function updateKey() {
		var quality = document.getElementById('quality');
		var key = document.getElementById('key');

		// if quality deselected, reset key
		if (quality.value == "") {
			key.innerHTML = "<option value=''>-----</option>";
		}
		else {
			// fill key dropdown with all keys w/ common enharmonics - for now
			key.innerHTML = 
				"<option value='B#/C'>B#/C</option>" +
				"<option value='C#/Db'>C#/Db</option>" +
				"<option value='D'>D</option>" +
				"<option value='D#/Eb'>D#/Eb</option>" +
				"<option value='E/Fb'>E/Fb</option>" +

				"<option value='E#/F'>E#/F</option>" +
				"<option value='F#/Gb'>F#/Gb</option>" +
				"<option value='G'>G</option>" +
				"<option value='G#/Ab'>G#/Ab</option>" +
				"<option value='A'>A</option>" +
				"<option value='A#/Bb'>A#/Bb</option>" +
				"<option value='B/Cb'>B/Cb</option>";
		}
		
		
	}


	// plays selected thing
	function playSelected() {
		document.getElementById("loading").style.display = "block"; // display loading message
		var type = document.getElementById('type').value;
		var quality = document.getElementById('quality').value;
		var key = document.getElementById('key').value;

		// console.log("Playing a " + quality + " " + type + " in " + key);

		// find csv index of selected
		var i;
		for (i = 0; i < data.length; i++) {
			if (data[i][0] == type && data[i][1] == quality) {
				break;
			}
		}

		// generate snippet with selected type, quality, and key
		var snippet = new MusicSnippet(type, quality, data[i][2], data[i][3]);
		snippet.generate(); // TODO: figure out how to set key

		// wait for files to load, then play snippet
		var intervalID;
		intervalID = setInterval(loadCheck, 1000, intervalID, snippet);
	}

	// ends the setInterval when files are loaded
	function loadCheck(intervalID, snippet) {
		if (document.getElementById("loading").style.display == "none") {
			snippet.play(); // requires "loading" and "allbuttons" elements
			clearInterval(intervalID);
		}
	}
</script>

<div id="loading" style="display: none">Loading...</div>
<br id="allbuttons">

<?php require_once("phpincludes/footer.php"); ?>