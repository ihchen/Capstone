<?php 
	$thisPage = 'Design Quiz';
	require_once('phpincludes/header.php'); 
?>

<script src="scripts/ParseCSV.js"></script>
<link href="style/style2.css" type="text/css" rel="stylesheet">

<script>
	/** 
	 * Checks the boxes of the given html class. If maincheck is given, all boxes are checked
	 * to how maincheck is checked. If not, then all boxes are just checked.
	 *
	 * @method checkBoxes
	 * @param {String} checkbox_class Class of checkboxes to be checked/unchecked
	 * @param {String} maincheck (Optional) Checkbox ID to determine whether or not to check/uncheck the given checkboxes
	 */
	function checkBoxes(checkbox_class, maincheck) {
		var mainbox;
		var subboxes = document.getElementsByClassName(checkbox_class);

		//If maincheck is given, grab the checkbox element
		if(maincheck != undefined) {
			mainbox = document.getElementById(maincheck);
		}
		//Loop through all checkboxes given and check accordingly
		for(var i = 0; i < subboxes.length; i++) {
			//Check by default or if mainbox is also checked
			if(mainbox == undefined || mainbox.checked) {
				subboxes[i].checked = true;
			}
			//Uncheck if mainbox is unchecked
			else {
				subboxes[i].checked = false;
			}
		}
		//When something is checked, check all chords and scales and update select all buttons
		checkCheckedChords();
		checkCheckedScales();
	}

	/**
	 * Check/uncheck all boxes in Test 1
	 *
	 * @method checkTest1
	 */
	function checkTest1() {
		checkBoxes('Test1','test1box');
	}

	/**
	 * Check/uncheck all boxes in Test 2 and Test 1
	 *
	 * @method checkTest2
	 */
	function checkTest2() {
		var test2 = document.getElementById('test2box');
		if(test2.checked)
			document.getElementById('test1box').checked = true;
		else
			document.getElementById('test1box').checked = false;
		checkBoxes('Test2', 'test2box');
		checkTest1();
	}

	/**
	 * Check/uncheck all boxes in Final, Test 2, and Test 1
	 *
	 * @method checkFinal
	 */
	function checkFinal() {
		var final = document.getElementById('finalbox');
		if(final.checked)
			document.getElementById('test2box').checked = true;
		else
			document.getElementById('test2box').checked = false;
		checkBoxes('Final', 'finalbox');
		checkTest2();
	}

	/**
	 * Uncheck the given boxes. If type not given, uncheck all boxes. Update select/deselect all
	 * buttons
	 *
	 * @method uncheckBoxes
	 * @param {String} type (Optional) If type given, only uncheck boxes of given type
	 */
	function uncheckBoxes(type) {
		var boxes;
		if(type == 'chord') {
			boxes = document.getElementsByClassName("type2chord");
			document.getElementById("chordselect").style.display = "block";
			document.getElementById("chorddeselect").style.display = "none";
		}
		else if(type == 'scale') {
			boxes = document.getElementsByClassName("type2scale");
			document.getElementById("scaleselect").style.display = "block";
			document.getElementById("scaledeselect").style.display = "none";
		}
		else {
			boxes = document.getElementsByTagName("input");
			document.getElementById("chordselect").style.display = "block";
			document.getElementById("chorddeselect").style.display = "none";
			document.getElementById("scaleselect").style.display = "block";
			document.getElementById("scaledeselect").style.display = "none";
		}
		for(var i = 0; i < boxes.length; i++) {
			if(boxes[i].type == "checkbox") {
				boxes[i].checked = false;
			}
		}
	}

	/**
	 * Checks to see if there are any checked chords. If so, use deselect all button. Otherwise
	 * use select all button
	 *
	 * @method checkCheckedChords
	 */
	function checkCheckedChords() {
		var hasChecked = false;
		var boxes = document.getElementsByClassName("type2chord");
		for(var i = 0; i < boxes.length; i++) {
			if(boxes[i].checked == true) {
				hasChecked = true;
				document.getElementById("chordselect").style.display = "none";
				document.getElementById("chorddeselect").style.display = "block";
				break;
			}
		}
		if(!hasChecked) {
			document.getElementById("chordselect").style.display = "block";
			document.getElementById("chorddeselect").style.display = "none";
		}
	}

	/**
	 * Checks to see if there are any checked scales. If so, use deselect all button, Otherwise, 
	 * use select all button.
	 */
	function checkCheckedScales() {
		var hasChecked = false;
		var boxes = document.getElementsByClassName("type2scale");
		for(var i = 0; i < boxes.length; i++) {
			if(boxes[i].checked == true) {
				hasChecked = true;
				document.getElementById("scaleselect").style.display = "none";
				document.getElementById("scaledeselect").style.display = "block";
				break;
			}
		}
		if(!hasChecked) {
			document.getElementById("scaleselect").style.display = "block";
			document.getElementById("scaledeselect").style.display = "none";
		}
	}
</script>

<style>
* {
	box-sizing: border-box;
}
.testboxes {
	font-size: .8em;
	margin-bottom: 1em;
}
.rows {
	display: flex;			/* Line bottoms of columns up */
}
.colspace {					/* Blank space (input &nbsp in divs with this class) */
	width: 16.66%;
	float: left;
}
.maincolumn {				/* Column that contains information */
	width: 33.33%;
	float: left;
	padding: 5px;
	text-align: left;
	border: 1px solid #008B8B;
}
.typehead {					/* Type title (chord/scale) */
	font-size: 1.2em;
	font-weight: bold;
	border-bottom: 2px solid #008B8B;
	position:relative;
	margin-bottom: .5em;
}							/* List of chords/scales */
.type2list {
	-webkit-column-count: 2;
	-moz-column-count: 2;
	column-count: 2;
}
.category {					/* Class for each category */
	-webkit-column-break-inside: avoid; /* Chrome, Safari */
    page-break-inside: avoid;           /* Theoretically FF 20+ */
    break-inside: avoid-column;         /* IE 11 */
    display:table;                      /* Actually FF 20+ */
    padding-bottom: 1em;
}
.categorytitle {			/* Title of category */
	font-size: 1.1em;
	font-style: italic;
}
.type2 {					/* Every element */
	font-weight: 100;
}
.testnames {				/* Test 1, Test 2, Final */
	font-size: 1.3em;
	display: inline-block;
	padding-left: 5px;
	padding-right: 20px;
}
.selectbtn {				/* Select/Deselect Buttons */
	text-align: center;
	white-space: nowrap;
	background: #008B8B;
	font-family: Verdana, Geneva, sans-serif;
	font-size: .7em;
	border: none;
	margin-bottom: 5px;
	top: 5px;
	position:absolute;
	top: 0%;
	right: 0%;
}
.selectbtn:hover {
  background: #00CED1;
}

/* Resizing and Scalability */
@media screen and (max-width: 1420px) {
	.colspace {
		width: 8.33%;
	}
	.maincolumn {
		width: 41.66%;
	}
}
@media screen and (max-width: 1135px) {
	.colspace {
		width: 1%;
	}
	.maincolumn {
		width: 49%;
	}
}
@media screen and (max-width: 954px) {
	.colspace {
		width: 16.66%;
	}
	.maincolumn {
		width: 33.33%;
	}
	.type2list {
		-webkit-column-count: 1;
		-moz-column-count: 1;
		column-count: 1;
	}
}
@media screen and (max-width: 710px) {
	.colspace {
		width: 8.33%;
	}
	.maincolumn {
		width: 41.66%;
	}
}
@media screen and (max-width: 565px) {
	.colspace {
		width: 1%;
	}
	.maincolumn {
		width: 49%;
	}
}
@media screen and (max-width: 480px) {
	.colspace {
		width: 5%;
		height: 200%;
	}
	.maincolumn {
		width: 90%;
		clear: left;
		margin-left: 5%;
	}
	.rows {
		display: block;
	}
}
</style>

<body id = "content">
	<div id = "instructions">
		Select items you would like to practice identifying. Click "Start Training" to begin your practice quiz.
	</div>

	<form name="selection" action="takeQuiz.php" onsubmit="return validateForm()" method="post">
		<!-- Test checkboxes -->
		<button type="button" class="button" onclick="uncheckBoxes()">Deselect All</button>
		<br/>
		<div class="testboxes">
			<input type="checkbox" id="test1box" onclick="checkTest1()"><div class="testnames"> Test 1 </div>
			<input type="checkbox" id="test2box" onclick="checkTest2()"><div class="testnames"> Test 2 </div>
			<input type="checkbox" id="finalbox" onclick="checkFinal()"><div class="testnames"> Final </div>
		</div>		

		<div class="rows">
			<div class="colspace">&nbsp</div>

			<!-- Chords column -->
			<div class="maincolumn">
				<div class="typehead">
					Chords
					<!-- Buttons -->
					<button id="chordselect" type="button" class="selectbtn" onclick="checkBoxes('type2chord')">Select All</button>
					<button id="chorddeselect" type="button" class="selectbtn" onclick="uncheckBoxes('type2chord')">Deselect All</button>
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

			<!-- Scales column -->
			<div class="maincolumn">
				<div class="typehead">
					Scales
					<!-- Buttons -->
					<button id="scaleselect" type="button" class="selectbtn" onclick="checkBoxes('type2scale')">Select All</button>
					<button id="scaledeselect" type="button" class="selectbtn" onclick="uncheckBoxes('type2scale')">Deselect All</button>
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
		<br/>
		<input class = "button" type="submit" value="Start Training">
	</form>
</body>

<script>
	// Find all categories and save a tuple containing (category, occurence, type)
	var categories = [];

	for (var i = 0; i < data.length; i++) {
		var found = false;
		for (var j = 0; j < categories.length; j++) {
			if (categories[j][0] == data[i][4] && categories[j][2] == data[i][0]) {
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

	/**
	 * http://www.stoimen.com/blog/2010/07/09/friday-algorithms-javascript-bubble-sort/
	 * Didn't feel like coding bubblesort...
	 *
	 * @method bubbleSort
	 * @param {2D Array} a Sorts by the second element in each subarray.
	 */
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

	//Sort categories so that we can display shortest ones first (looks nicer on UI)
	bubbleSort(categories);

	//For all categories, create divs. Id to put elements in is '[category][type]'.
	for (var i = 0; i < categories.length; i++) {
		var div = document.getElementById(categories[i][2]);
		div.innerHTML = div.innerHTML +
			"<div class='category'>"+
			"<div class='categorytitle'>"+categories[i][0]+"<br/></div>"+
			"<div class='type2' id='"+categories[i][0]+categories[i][2]+"'></div></div>";
	}

	//Put every element in csv file into the corresponding divs
	for (var i = 0; i < data.length; i++) {
		var div = document.getElementById(data[i][4]+data[i][0]);
		if(data[i][0] == 'chord') {
			var fn = "checkCheckedChords()";
		}
		else {
			var fn = "checkCheckedScales()";
		}
		div.innerHTML = div.innerHTML + "<input type='checkbox' class='type2"+data[i][0]+" "+data[i][3]+"' name='" + i + "' value='num' onclick="+fn+">" + data[i][1] + "<br/>";
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

	/**
	 * If the form doesn't have at least one box checked, alert and stop submission
	 *
	 * @method validateForm
	 */
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

	/**
	 * Make cookies to remember what the user selected
	 *
	 * @method makeCookie
	 */
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

	//Check checked chords and scales in case cookies were saved or user hit refresh
	checkCheckedChords();
	checkCheckedScales();
</script>

<?php require_once('phpincludes/footer.php'); ?>
