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
			for ($i=0; $i < count($types); $i++) { 
				echo "<option value='$types[i]'>$types[i]</option>\n";
			}
		?>
	</select>
	</select>
	<select name="key">
		<?php
			for ($i=0; $i < count($keys); $i++) { 
				echo "<option value='$keys[i]'>$keys[i]</option>\n";
			}
		?>
	</select>
</form>