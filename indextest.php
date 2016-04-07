
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
<style>
* {
	box-sizing: border-box;
}
.rows {
	display: flex;
}
.colspace { 
	width: 16.66%; 
	float: left;
}
.maincolumn { 
	width: 33.33%; 
	float: left;
	padding: 5px;
	text-align: left;
	border: 1px solid #008B8B;
}
.typehead {
	font-size: 1.2em;
	font-weight: bold;
	border-bottom: 2px solid #008B8B;
}
.type2list {
	-webkit-column-count: 2;
	-moz-column-count: 2;
	column-count: 2;
}
.category {
	-webkit-column-break-inside: avoid; /* Chrome, Safari */
    page-break-inside: avoid;           /* Theoretically FF 20+ */
    break-inside: avoid-column;         /* IE 11 */
    display:table;                      /* Actually FF 20+ */
}
.categorytitle {
	font-size: 1.1em;
	text-decoration: underline;
}
</style>
<body id = "content">

	<div id = "instructions">
		Click on the words "Chords" or "Scales" to expand the dropdown list. Select items you would like to practice identifying. Click "Start Training" to begin your practice quiz.
	</div>

<form name="selection" action="takeQuiz.php" onsubmit="return validateForm()" method="post">
	<input class = "button" type="submit" value="Start Training">
	<br/>
	<button class = "button" onclick="checkBoxes('Test1')" type="button">Test 1</button>
	<button class = "button" onclick="checkBoxes('Test2')" type="button">Test 2</button>
	<button class = "button" onclick="checkBoxes('Final')" type="button">Final</button>
	<br/>
	
	<div class="rows">
		<div class="colspace">&nbsp</div>
		<div class="maincolumn">
			<div class="typehead">
				Chords
			</div>
			<div class="type2list" id="chord">
				<!-- <div class="category"> -->
					<!-- <div class="categorytitle">[category]</div> -->
					<!-- <div class="type2" id="[category]"> -->
						<!-- Generated scales go here -->
					<!-- </div> -->	
				<!-- </div> -->
				<!-- ... -->
			</div>
		</div>
		<div class="maincolumn">
			<div class="typehead">
				Scales
			</div>
			<div class="type2list" id="scale">
				<!-- <div class="category"> -->
					<!-- <div class="categorytitle">[category]</div> -->
					<!-- <div class="type2" id="[category]"> -->
						<!-- Generated chords go here -->
					<!-- </div> -->	
				<!-- </div> -->
				<!-- ... -->
			</div>
		</div>
		<div class="colspace">&nbsp</div>
	</div>
</body>


	<!-- </div> -->
</form>

<script>
	var categories = [];

	for (var i = 0; i < data.length; i++) {
		var found = false;
		for (var j = 0; j < categories.length; j++) {
			if (categories[j][0] == data[i][4]) {
				found = true;
				break;
			}
		}
		if(found) {
			categories[j][1]++;
		}
		else {
			categories.push([data[i][4], 1, data[i][0]]);
		}
	}

	function bubbleSort(a) {
	    var swapped;
	    do {
	        swapped = false;
	        for (var i=0; i < a.length-1; i++) {
	            if (a[i][1] > a[i+1][1]) {
	                var temp = a[i];
	                a[i] = a[i+1];
	                a[i+1] = temp;
	                swapped = true;
	            }
	        }
	    } while (swapped);
	}
	
	bubbleSort(categories);
		
	for (var i = 0; i < categories.length; i++) {
		var div = document.getElementById(categories[i][2]);
		div.innerHTML = div.innerHTML +
			"<div class='category'>"+
			"<div class='categorytitle'>"+categories[i][0]+"<br/></div>"+
			"<div class='type2' id='"+categories[i][0]+"'></div></div>";
	}

	for (var i = 0; i < data.length; i++) {
		var div = document.getElementById(data[i][4]);
		div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2"+data[i][0]+" "+data[i][3]+"' name='" + i + "' value='num'>" + data[i][1] + " " + data[i][0] + "<br/>";
	}

	/* Parse CSV file to generate selections */
	// for (var i = 0; i < data.length; i++) {
	// 	if (data[i][0] == "scale") { // if it's a scale
	// 		var div = document.getElementById("scales");
	// 		// add a checkbox input
	// 		div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2scale "+data[i][3]+"' name='" + i + "' value='num'>" + data[i][1] + " " + data[i][0] + "<br/>";
	// 	}
	// 	else if (data[i][0] == "chord") { // if it's a chord
	// 		var div = document.getElementById("chords");
	// 		// add a checkbox input
	// 		div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2chord "+data[i][3]+"' name='" + i + "' value='num'>" + data[i][1] + " " + data[i][0] + "<br/>";
	// 	}
	// 	else if (data[i][0] == "interval") { // if it's an interval
	// 		var div = document.getElementById("intervals");
	// 		// add a checkbox input
	// 		div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2interval "+data[i][3]+"' name='" + i + "' value='num'>" + data[i][1] + "<br/>";
	// 	}
	// }

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

<?php require_once('phpincludes/footer.php'); ?>
