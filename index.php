
<?php
$thisPage = 'Make My Own Practice Quiz';
?>

<?php require_once('phpincludes/header.php'); ?>

	<title> <?php echo "$thisPage" ?> </title>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<!-- CSV Parsing script -->
	<script src="scripts/ParseCSV.js"></script>

	<!-- Overall CSS -->
	<link rel="stylesheet" type="text/css" href="style/style2.css">

	<script>
		/* Activate Collapsible list */
		$('.collapse').collapse();

		/* Check type2 boxes when type1 boxes are checked */
		function checkBoxes(type1class, type2class) {
			var mainbox = document.getElementById(type1class);
			var subboxes = document.getElementsByClassName(type2class);
			for(var i = 0; i < subboxes.length; i++) {
				if(mainbox.checked) {
					subboxes[i].checked = true;
				}
				else {
					subboxes[i].checked = false;
				}
			}
		}
	</script>
<!-- </head>


<center>
	<h1> UPS Ear Training </h1>
</center> -->
<body id = "content">

	<div id = "instructions">
		Click on the words "Chords" or "Scales" to expand the dropdown list. Select items you would like to practice identifying. Click "Start Training" to begin your practice quiz.
	<div>

<form name="selection" action="takeQuiz.php" onsubmit="return validateForm()" method="post">

	<div id="checkboxcontainer">
		<div id="accordion" class="panel-group">
			<!-- Type1 Checkboxes -->
			<div id="type1">
				<!-- Chord -->
				<input type="checkbox" id="type1chord" onclick="checkBoxes('type1chord','type2chord')">
				<a href="#chords" data-toggle="collapse" data-parent="#accordion">Chords</a><br/>
				<!-- Scale -->
				<input type="checkbox" id="type1scale" onclick="checkBoxes('type1scale','type2scale')">
				<a href="#scales" data-toggle="collapse" data-parent="#accordion">Scales</a><br/>
				<!-- Interval -->
				<!-- <input type="checkbox" id="type1interval" onclick="checkBoxes('type1interval','type2interval')"> -->
				<!-- <a href="#intervals" data-toggle="collapse" data-parent="#accordion">Intervals</a><br/> -->
			</div>

			<!-- Type2 Checkboxes -->
			<div class="panel" id="type2">
				<!-- Chords -->
		        <div id="chords" class="panel-collapse collapse">
		        	<!-- Generated Checkboxes go here -->
				</div>
				<!-- Scales -->
		        <div id="scales" class="panel-collapse collapse">
		        	<!-- Generated Checkboxes go here -->
				</div>
			</div>
		</div>
	</div>
	<div id = "button">
		<input class = "button" type="submit" value="Start Training">
	</div>
</form>

<script>
	/* Parse CSV file to generate selections */
	for (var i = 0; i < data.length; i++) {
		if (data[i][0] == "scale") { // if it's a scale
			var div = document.getElementById("scales");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2scale' name='" + i + "' value='num'>" + data[i][1] + " " + data[i][0] + "<br>";
		}
		else if (data[i][0] == "chord") { // if it's a chord
			var div = document.getElementById("chords");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2chord' name='" + i + "' value='num'>" + data[i][1] + " " + data[i][0] + "<br>";
		}
		else if (data[i][0] == "interval") { // if it's an interval
			var div = document.getElementById("intervals");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2interval' name='" + i + "' value='num'>" + data[i][1] + "<br>";
		}
	}

	// if the form doesn't have at least one box checked, alert and stop submission
	function validateForm() {
		var form_fields = document.forms["selection"]; // get the form
		for (var i = 0; form_fields[i.toString()] != undefined; i++) { // loop through everything in the form
			if (form_fields[i.toString()].value == "num" && form_fields[i.toString()].checked == true) { // if it's a chord/scale/interval option and is set
				return true; // submit the form
			}
		}
		// else no boxes are checked
		alert("Please select at least one scale, chord, or interval.");
		return false; // don't submit the form
	}
</script>
</body>

<?php require_once('phpincludes/footer.php'); ?>
