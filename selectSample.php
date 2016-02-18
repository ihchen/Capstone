<script type="text/javascript" src="scripts/Note.js"></script>
<script type="text/javascript" src="scripts/makeMusicSnippet.js"></script>
<script type="text/javascript" src="scripts/noteToFileNum.js"></script>
<script type="text/javascript" src="scripts/MusicSnippet.js"></script>

<?php
	// load scripts/data.csv
	$datacsv = fopen("scripts/data.csv", r) or die("Unable to open data.csv!");
	// parse
	$types = array();
	$keys = array("C","D","E");

	while (!feof($datacsv)) {
		$data_entry = str_getcsv(fgets($datacsv));
		array_push($types, $data_entry[1]+" "+$data_entry[0]);
	}

	fclose($datacsv);
?>

<form>
	<select name="type">
		<?php
			foreach ($types as $type) { 
				echo "<option value='$type'>$type</option>\n";
			}
		?>
	</select>
	</select>
	<select name="key">
		<?php
			foreach ($keys as $note) {
				echo "<option value='$note'>$note</option>\n";
			}
		?>
	</select>
</form>