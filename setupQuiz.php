<script type="text/javascript" src="scripts/ParseCSV.js"></script>


<form action="takeQuiz.php" method="post">
	<fieldset>
		<legend>Scales</legend>
		<div id="scales"></div>
	</fieldset>
	<fieldset>
		<legend>Chords</legend>
		<div id="chords"></div>
	</fieldset>
	<fieldset>
		<legend>Intervals</legend>
		<div id="intervals"></div>
	</fieldset>
	<br><br>
	<button type="button">Start Quiz</button>
</form>


<script type="text/javascript">
	// This is the javascript that creates the checkboxes.

	// loop through everything in the data csv
	for (var i = 0; i < data.length; i++) {
		if (data[i][0] == "scale") { // if it's a scale
			var div = document.getElementById("scales");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' name='" + i + "'>" + data[i][1] + " " + data[i][0] + "<br>";
		}
		else if (data[i][0] == "chord") { // if it's a chord
			var div = document.getElementById("chords");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' name='" + i + "'>" + data[i][1] + " " + data[i][0] + "<br>";
		}
		else if (data[i][0] == "interval") { // if it's an interval
			var div = document.getElementById("intervals");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' name='" + i + "'>" + data[i][1] + "<br>";
		}
	}

	// after that, add inversion/direction options
	
</script>