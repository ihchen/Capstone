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
		// fill key dropdown with all keys w/ common enharmonics - for now
		document.getElementById('key').innerHTML = 
			"<option value=''>B#/C</option>" +
			"<option value=''>C#/Db</option>" +
			"<option value=''>D</option>" +
			"<option value=''>D#/Eb</option>" +
			"<option value=''>E/Fb</option>" +

			"<option value=''>E#/F</option>" +
			"<option value=''>F#/Gb</option>" +
			"<option value=''>G</option>" +
			"<option value=''>G#/Ab</option>" +
			"<option value=''>A</option>" +
			"<option value=''>A#/Bb</option>" +
			"<option value=''>B/Cb</option>";
	}


	// plays selected thing
	function playSelected() {
		// body...
	}
</script>