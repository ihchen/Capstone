
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

		/* Checks the boxes of the given class */
		function checkBoxes(checkbox_class) {
			var mainbox;
			var subboxes = document.getElementsByClassName(checkbox_class);
			if(checkbox_class.substring(0,4) == "type") {
				mainbox = document.getElementById("type1"+checkbox_class.substring(5,checkbox_class.length));
			}
			for(var i = 0; i < subboxes.length; i++) {
				if(mainbox == undefined) {
					subboxes[i].checked = true;
				}
				else {
					if(mainbox.checked) {
						subboxes[i].checked = true;
					}
					else {
						subboxes[i].checked = false;
					}
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
	</div>

<form name="selection" action="takeQuiz.php" onsubmit="return validateForm()" method="post">
	<input class = "button" type="submit" value="Start Training">
	<br/>
	<button onclick="checkBoxes('Test1')" type="button">Test 1</button>
	<button onclick="checkBoxes('Test2')" type="button">Test 2</button>
	<button onclick="checkBoxes('Final')" type="button">Final</button>
	<div id="checkboxcontainer">
		<div id="accordion" class="panel-group">
			<!-- Type1 Checkboxes -->
			<div id="type1">
				<!-- Chord -->
				<input type="checkbox" id="type1chord" onclick="checkBoxes('type2chord')">
				<a href="#chords" data-toggle="collapse" data-parent="#accordion">Chords</a><br/>
				<!-- Scale -->
				<input type="checkbox" id="type1scale" onclick="checkBoxes('type2scale','type2scale')">
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
				<br/>
				<br/>
				<br/>
			</div>
		</div>
	</div>
</body>


	<!-- </div> -->
</form>

<script>
	/* Parse CSV file to generate selections */
	for (var i = 0; i < data.length; i++) {
		if (data[i][0] == "scale") { // if it's a scale
			var div = document.getElementById("scales");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2scale "+data[i][3]+"' name='" + i + "' value='num'>" + data[i][1] + " " + data[i][0] + "<br/>";
		}
		else if (data[i][0] == "chord") { // if it's a chord
			var div = document.getElementById("chords");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2chord "+data[i][3]+"' name='" + i + "' value='num'>" + data[i][1] + " " + data[i][0] + "<br/>";
		}
		else if (data[i][0] == "interval") { // if it's an interval
			var div = document.getElementById("intervals");
			// add a checkbox input
			div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2interval "+data[i][3]+"' name='" + i + "' value='num'>" + data[i][1] + "<br/>";
		}
	}

	// read cookie if it exists
	{
		console.log(document.cookie);
		var select = document.cookie.split('=').pop().split(',');
		console.log(select);
		var form = document.forms["selection"];
		for (var i = 0; select[i] != ""; i++) {
			console.log(select[i]);
			form[select[i]].checked = true;
		}
	}


	// if the form doesn't have at least one box checked, alert and stop submission
	function validateForm() {
		var form_fields = document.forms["selection"]; // get the form
		for (var i = 0; form_fields[i.toString()] != undefined; i++) { // loop through everything in the form
			if (form_fields[i.toString()].value == "num" && form_fields[i.toString()].checked == true) { // if it's a chord/scale option and is set
				makeCookie();
				return true; // submit the form
			}
		}
		// else no boxes are checked
		alert("Please select at least one chord or scale.");
		return false; // don't submit the form
	}

	function makeCookie() {
		var cookie_string = "selected=";
		var form_fields = document.forms["selection"];
		for (var i = 0; form_fields[i.toString()] != undefined; i++) {
			if (form_fields[i.toString()].checked == true) {
				cookie_string += i + ","; // add all selected scales/chords to the cookie
			}
		}
		var exp = new Date();
		exp.setTime(exp.getTime() + 604800000) // one week from now
		cookie_string += "; expires=" + exp.toUTCString();
		document.cookie = cookie_string;
	}
</script>

<?php require_once('phpincludes/footer.php'); ?>