<script type="text/javascript">
	// Make config object
	var config = {
		scaleUP:false, 
		scaleDOWN:false, 
		
		root:false, 
		first:false, 
		second:false, 
		third:false, 
		
		intervalUP:false, 
		intervalDOWN:false
	};

	// Make an integer array to hold selected types of things to be tested on
	var chosen = [];
</script>

<?php
// convert post vars into javascript
foreach ($_POST as $key => $value) {
	if ($key == "scaleUP" ||
		$key == "scaleDOWN" ||
		$key == "root" ||
		$key == "first" ||
		$key == "second" ||
		$key == "third" ||
		$key == "intervalUP" ||
		$key == "intervalDOWN") {
		echo "<script type="text/javascript">config.$key = true;</script>";
	}
	else {
		echo "<script type="text/javascript">chosen.push($key);</script>";
	}
}
?>
