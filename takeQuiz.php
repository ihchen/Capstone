<?php
	// convert post vars into javascript
	echo "<script type='text/javascript'>";

	// Make config object
	echo "var config = {
		scaleUP:false, 
		scaleDOWN:false, 
		
		root:false, 
		first:false, 
		second:false, 
		third:false, 
		
		intervalUP:false, 
		intervalDOWN:false};";
	
	// Make an integer array in JavaScript
	echo "var chosen = [];";

	foreach ($_POST as $key => $value) {
		if ($key == "scaleUP" ||
			$key == "scaleDOWN" ||

			$key == "root" ||
			$key == "first" ||
			$key == "second" ||
			$key == "third" ||

			$key == "intervalUP" ||
			$key == "intervalDOWN") {
			echo "config.$key = true;";
		}
		else {
			echo "chosen.push($key);";
		}
	}

	echo "</script>";
?>