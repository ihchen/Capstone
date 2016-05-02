<?php 
	$thisPage = 'Design Quiz';
	require_once('phpincludes/header.php'); 
?>

<!-- Import styling -->
<link href="style/designQuiz.css" type="text/css" rel="stylesheet">
<!-- Parse the CSV file for data -->
<script src="scripts/ParseCSV.js"></script>

<!-- Functions to be used on the page -->
<script>
	/** 
	 * Checks the boxes of the given html class. If maincheck is given, all boxes are checked
	 * to how maincheck is checked. If not, then all boxes are just checked.
	 *
	 * @method checkBoxes
	 * @param {String} checkbox_class Class of checkboxes to be checked/unchecked
	 * @param {String} maincheck (Optional) Checkbox ID to determine whether or not to check/uncheck the given checkboxes
	 */
	function checkBoxes(checkbox_class, mainclass) {
		//If no arguments given, check all boxes
		if(checkbox_class == undefined) {
			//Loop through all elements with input tag
			var allboxes = document.getElementsByTagName("input");
			for(var i = 0; i < allboxes.length; i++) {
				//Check all checkboxes
				if(boxes[i].type == "checkbox") {
					boxes[i].checked = true;
				}
			}
		}
		else {
			var mainbox;
			var subboxes = document.getElementsByClassName(checkbox_class);	//Get all elements of the given class

			//If main class is given, grab it
			if(mainclass != undefined) {
				mainbox = document.getElementById(mainclass);
			}
			//Loop through all checkboxes of given class
			for(var i = 0; i < subboxes.length; i++) {
				//Check boxes by default. If mainclass given, check/uncheck boxes based on if mainbox was checked
				if(mainbox == undefined || mainbox.checked) {
					subboxes[i].checked = true;
				}
				else {
					subboxes[i].checked = false;
				}
			}
		}
		//Update all select/deselect all buttons
		updateButtons();
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
	function uncheckBoxes(checkbox_class) {
		//If no parameter given, uncheck all boxes
		if(checkbox_class == undefined) {
			//Loop through all elements with input tag
			var allboxes = document.getElementsByTagName("input");
			for(var i = 0; i < allboxes.length; i++) {
				//Uncheck all checkboxes
				if(allboxes[i].type == "checkbox") {
					allboxes[i].checked = false;
				}
			}
		}
		else {
			//Get all elements of given class
			var boxes = document.getElementsByClassName(checkbox_class);
			//Uncheck them all
			for(var i = 0; i < boxes.length; i++) {
				boxes[i].checked = false;
			}
		}
		//Update select/deselect buttons
		updateButtons();
	}

	/**
	 * Checks all checkboxes to see which buttons need to be displayed
	 *
	 * @method updateButtons
	 */
	function updateButtons() {
		var checkedChord = false;	//Whether or not a chord was checked
		var checkedScale = false;	//Whether or not a scale was checkd
		var checkedCategory;		//Whether the current category had a check
		var checkboxes;				//Checkboxes of current category
		var className;				//Class name of current category

		//Loop through all categories
		for(var i = 0; i < categories.length; i++) {
			checkedCategory = false;
			className = categories[i][0]+categories[i][2]	//Class of elements in a category is [category][title]
			checkboxes = document.getElementsByClassName(className);

			//Loop through elements in category
			for(var j = 0; j < checkboxes.length; j++) {
				if(checkboxes[j].checked) {
					//Mark if chord or scale
					if(categories[i][2] == 'chord')
						checkedChord = true;
					else
						checkedScale = true;
					//Mark the category
					checkedCategory = true;
					break;
				}
			}
			//If something in category was checked, display deselect all button
			if(checkedCategory) {
				document.getElementById(className+"select").style.display = "none";
				document.getElementById(className+"deselect").style.display = "block";
			}
			//Otherwise display select all button
			else {
				document.getElementById(className+"select").style.display = "block";
				document.getElementById(className+"deselect").style.display = "none";
			}
		}
		//If a chord was checked, display chord's deselect all button
		if(checkedChord) {
			document.getElementById("chordselect").style.display = "none";
			document.getElementById("chorddeselect").style.display = "block";
		}
		else {
			document.getElementById("chordselect").style.display = "block";
			document.getElementById("chorddeselect").style.display = "none";
		}
		//If a scale was checked, display scale's deselect all button
		if(checkedScale) {
			document.getElementById("scaleselect").style.display = "none";
			document.getElementById("scaledeselect").style.display = "block";
		}
		else {
			document.getElementById("scaleselect").style.display = "block";
			document.getElementById("scaledeselect").style.display = "none";
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
</script>

<body id = "content">
	<div id = "instructions">
		Select items you would like to practice identifying. Click "Start Training" to begin your practice quiz.
	</div>

	<form name="selection" action="takeQuiz.php" onsubmit="return validateForm()" method="post">
		<!-- Deselect all button -->
		<button type="button" class="button" onclick="uncheckBoxes()">Deselect All</button>
		<br/>
		<!-- Test checkboxes -->
		<div class="testboxes">
			<input type="checkbox" id="test1box" onclick="checkTest1()"><div class="testnames"> Test 1 </div>
			<input type="checkbox" id="test2box" onclick="checkTest2()"><div class="testnames"> Test 2 </div>
			<input type="checkbox" id="finalbox" onclick="checkFinal()"><div class="testnames"> Final </div>
		</div>		

		<div class="rows">
			<!-- Column Space -->
			<div class="colspace">&nbsp</div>

			<!-- Chords column -->
			<div class="maincolumn">
				<!-- Chords header -->
				<div class="typehead">
					Chords
					<!-- Buttons -->
					<div class="selectdeselectbtns">
						<button id="chordselect" type="button" class="smallbtn" onclick="checkBoxes('chord')">Select All</button>
						<button id="chorddeselect" type="button" class="smallbtn" onclick="uncheckBoxes('chord')">Deselect All</button>
					</div>
				</div>
				<!-- Chords content -->
				<div class="type2list" id="chord">
					<!-- <div class="category"> -->
						<!-- <div class="categorytitle">[category] -->
							<!-- <div class="selectdeselectbtns"> -->
								<!-- <button id="chordselect" type="button" class="smallbtn" onclick="checkBoxes('[category][type]')">Select All</button> -->
								<!-- <button id="chorddeselect" type="button" class="smallbtn" onclick="uncheckBoxes('[category][type]')">Deselect All</button> -->
							<!-- </div> -->
						<!-- <br/></div> -->
						<!-- <div class="type2" id="[category][type]"> -->
							<!-- Generated scales go here -->
						<!-- </div> -->
					<!-- </div> -->
					<!-- ... -->
				</div>
			</div>

			<!-- Scales column -->
			<div class="maincolumn">
				<!-- Scales header -->
				<div class="typehead">
					Scales
					<!-- Buttons -->
					<div class="selectdeselectbtns">
						<button id="scaleselect" type="button" class="smallbtn" onclick="checkBoxes('scale')">Select All</button>
						<button id="scaledeselect" type="button" class="smallbtn" onclick="uncheckBoxes('scale')">Deselect All</button>
					</div>
				</div>
				<!-- Scales content -->
				<div class="type2list" id="scale">
					<!-- <div class="category"> -->
						<!-- <div class="categorytitle">[category] -->
							<!-- <div class="selectdeselectbtns"> -->
								<!-- <button id="chordselect" type="button" class="smallbtn" onclick="checkBoxes('[category][type]')">Select All</button> -->
								<!-- <button id="chorddeselect" type="button" class="smallbtn" onclick="uncheckBoxes('[category][type]')">Deselect All</button> -->
							<!-- </div> -->
						<!-- <br/></div> -->
						<!-- <div class="type2" id="[category][type]"> -->
							<!-- Generated chords go here -->
						<!-- </div> -->
					<!-- </div> -->
					<!-- ... -->
				</div>
			</div>

			<!-- Column Space -->
			<div class="colspace">&nbsp</div>
		</div>
		<br/>
		<!-- Submit button -->
		<button class = "button" type="submit" value="Start Training">Start Training</button>
	</form>
</body>

<!-- Javascript that runs immediately -->
<script>
	/* Find all categories and save a tuple containing (category, occurence, type) */
	var categories = [];		//variable is also used in updateButton()
	//Loop through all data in csv file
	for (var i = 0; i < data.length; i++) {
		var found = false;
		//Loop through all categories currently found
		for (var j = 0; j < categories.length; j++) {
			//Check if we already have category
			if (categories[j][0] == data[i][4] && categories[j][2] == data[i][0]) {
				found = true;
				break;
			}
		}
		//If we already had it, increment its occurence
		if(found) {
			categories[j][1]++;
		}
		//If not, add it into our array
		else {
			categories.push([data[i][4], 1, data[i][0]]);
		}
	}

	/* Sort all categories by occurence so we can display them nicely (shortest to longest) */
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
	bubbleSort(categories);

	/* For all categories, create divs. ID to put elements in is '[category][type]'. */
	//Loop through all categories
	for (var i = 0; i < categories.length; i++) {
		var div = document.getElementById(categories[i][2]);
		var className = categories[i][0]+categories[i][2];
		//Add divs to contain categories and their elements
		div.innerHTML = div.innerHTML +
			"<div class='category'>"+														//Div to contain everything in category
			"<div class='categoryhead'>"+categories[i][0]+									//Category title
				"<div class='selectdeselectbtns'>"+											//Select/deselect buttons
					"<button id='"+className+"select' type='button' class='smallbtn' onclick='checkBoxes(\""+className+"\")'>Select All</button>"+
					"<button id='"+className+"deselect' type='button' class='smallbtn' onclick='uncheckBoxes(\""+className+"\")'>Deselect All</button>"+
				"</div>"+
			"<br/></div>"+					//Div to contain the title of category
			"<div class='type2' id='"+className+"'></div></div>";	//Div to contain the elements
	}

	/* Put every element in csv file into the corresponding divs */
	//Loop through all elements in csv file
	for (var i = 0; i < data.length; i++) {
		//Get the div it's suppose to go in: ID = '[category][type]'
		var div = document.getElementById(data[i][4]+data[i][0]);
		//If it's a chord, use checkCheckedChords() for onclick
		if(data[i][0] == 'chord') {
			var fn = "checkCheckedChords()";
		}
		//If it's a chord, use checkCheckedScales() for onclick
		else {
			var fn = "checkCheckedScales()";
		}
		//Add a checkbox with class='[type] [test] [category][type]' name='[index in csv file]'.
		div.innerHTML = div.innerHTML + 
			"<input type='checkbox' class='"+data[i][0]+" "+data[i][3]+" "+data[i][4]+data[i][0]+"' name='" + i + "' value='num' onclick="+fn+">" + displayQuality(data[i][1]) + "<br/>";
	}

	// read cookie if it exists
	{
		// console.log(document.cookie);
		var select = document.cookie.split('=').pop().split(',');
		// console.log(select);
		var form = document.forms["selection"];
		for (var i = 0; select[i] != ""; i++) {
			// console.log(select[i]);
			form[select[i]].checked = true;
		}
	}

	function displayQuality(quality) {
		quality = quality.replace(/\(b/, "(&#9837;");
		quality = quality.replace(/\/b/, "/&#9837;");
		quality = quality.replace(/\(x/, "(&#9839;");
		quality = quality.replace(/\/x/, "/&#9839;");
		return quality;
	}

	//Update select/deselect all buttons
	updateButtons();
</script>

<?php require_once('phpincludes/footer.php'); ?>