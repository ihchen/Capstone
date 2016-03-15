<?php
$thisPage = 'Test MusicManip';
?>

<?php require_once('phpincludes/header.php'); ?>
<br></br>

<script type="text/javascript" src="scripts/MusicManip.js"></script>
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
	<button type="button" onclick="playSample()">
		Play Selected Sample
	</button>
</form>

<script type="text/javascript">
	function playSample() {
		// get stuff out of the form
		var selectedTypeAndQuality = document.getElementById("type").value.split(" "); // type is at 1, quality is at 0, as arranged by the php script
		var selectedKey = document.getElementById("key").value.split("/");
		console.log(selectedTypeAndQuality);
		console.log(selectedKey);
		// turn it into the note string
		var sampleText = makeThing(selectedTypeAndQuality[1], selectedTypeAndQuality[0], selectedKey[0].replace("#", "x"));
		sampleText = setOctave(sampleText);
		console.log(sampleText);
		// play it
		var samplePlayable = new MusicSnippet(sampleText, selectedTypeAndQuality[1]);
		samplePlayable.play();
	}
</script>

<?php require_once('phpincludes/footer.php'); ?>
