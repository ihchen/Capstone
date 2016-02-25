<?php
	// Make an integer array in JavaScript
	echo "<script type='text/javascript'>";
	echo "const chosen = [";

	foreach ($_POST as $key => $value) {
		if ($value == ) {
			echo $key+", ";
		}
	}

	echo "];";
	echo "</script>";
?>