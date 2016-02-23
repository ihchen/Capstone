<script type="text/javascript" src="scripts/Note.js"></script>
<script type="text/javascript" src="scripts/makeMusicSnippet.js"></script>
<script type="text/javascript" src="scripts/noteToFileNum.js"></script>
<script type="text/javascript" src="scripts/MusicSnippet.js"></script>
<script type="text/javascript" src="howler/howler.js"></script>

<?php
	// load scripts/data.csv
	$datacsv = fopen("scripts/data.csv", r) or die("Unable to open data.csv!");
	// parse
	$types = array();
	$keys = array("B#/C","C#/Db","D","D#/Eb","E/Fb","F","F#/Gb","G","G#/Ab","A","A#/Bb","B/Cb");

	while (!feof($datacsv)) {
		$data_entry = str_getcsv(fgets($datacsv));
		array_push($types, "$data_entry[1] $data_entry[0]");
	}

	fclose($datacsv);
?>

<form>
	<select id="type">
		<?php
			foreach ($types as $type) { 
				echo "<option value='$type'>$type</option>";
			}
		?>
	</select>
	<br>
	<select id="key">
		<?php
			foreach ($keys as $note) {
				echo "<option value='$note'>$note</option>";
			}
		?>
	</select>
	<br>
	<button type="button" action="playSample()">
		Play Selected Sample
	</button>
</form>

<script type="text/javascript">
	function playSample() {
		// get stuff out of the form
		var selectedTypeAndQuality = document.getElementById("type").value.split(" ");
		var selectedKey = document.getElementById("key").value.split("/");
		// turn it into the note string
		var sampleText = makeThing(selectedTypeAndQuality[0], selectedTypeAndQuality[1], selectedKey[0].replace("#", "x"));
		// play it
		var samplePlayable = new MusicSnippet(sampleText, selectedTypeAndQuality[0]);
		samplePlayable.play();
	}
</script>