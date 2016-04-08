
<?php
$thisPage = 'Make My Own Practice Quiz';
?>

<?php require_once('phpincludes/header.php'); ?>

	<title> <?php echo "$thisPage" ?> </title>

	<!-- Bootstrap -->
	<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script> -->
	<!-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->

	<!-- CSV Parsing script -->
	<script src="scripts/ParseCSV.js"></script>

	<link href="style/style2.css" type="text/css" rel="stylesheet">

	<script>
		/* Activate Collapsible list */
		// $('.collapse').collapse();

		/* Checks the boxes of the given class */
		function checkBoxes(checkbox_class, mainclass) {
			var mainbox;
			var subboxes = document.getElementsByClassName(checkbox_class);
			if(mainclass != undefined) {
				mainbox = document.getElementById(mainclass);
			}
			for(var i = 0; i < subboxes.length; i++) {
				if(mainbox == undefined || mainbox.checked) {
					subboxes[i].checked = true;
				}
				else {
					subboxes[i].checked = false;
				}
			}
			checkCheckedChords();
			checkCheckedScales();
		}

		function checkTest2() {
			var test2 = document.getElementById('test2box');
			if(test2.checked) 
				document.getElementById('test1box').checked = true;
			else
				document.getElementById('test1box').checked = false;
			checkBoxes('Test2', 'test2box');
			checkBoxes('Test1', 'test1box');
		}

		function checkFinal() {
			var final = document.getElementById('finalbox');
			if(final.checked)
				document.getElementById('test2box').checked = true;
			else
				document.getElementById('test2box').checked = false;
			checkTest2();			
			checkBoxes('Final', 'finalbox');
		}

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

		function checkCheckedChords() {
			var hasChecked = false;
			var boxes = document.getElementsByClassName("type2chord");
			for(var i = 0; i < boxes.length; i++) {
				if(boxes[i].checked == true) {
					hasChecked = true;
					document.getElementById("chordselect").style.display = "none";
					document.getElementById("chorddeselect").style.display = "block";
				}
			}
			if(!hasChecked) {
				document.getElementById("chordselect").style.display = "block";
				document.getElementById("chorddeselect").style.display = "none";
			}
		}

		function checkCheckedScales() {
			var hasChecked = false;
			var boxes = document.getElementsByClassName("type2scale");
			for(var i = 0; i < boxes.length; i++) {
				if(boxes[i].checked == true) {
					hasChecked = true;
					document.getElementById("scaleselect").style.display = "none";
					document.getElementById("scaledeselect").style.display = "block";
				}
			}
			if(!hasChecked) {
				document.getElementById("scaleselect").style.display = "block";
				document.getElementById("scaledeselect").style.display = "none";
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
	position:relative;
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
.testnames {
	font-size: 1.3em;
	display: inline-block;
	padding-left: 5px;
	padding-right: 20px;
}
.selectbtn {
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
</style>
<body id = "content">

	<div id = "instructions">
		Click on the words "Chords" or "Scales" to expand the dropdown list. Select items you would like to practice identifying. Click "Start Training" to begin your practice quiz.
	</div>

<form name="selection" action="takeQuiz.php" onsubmit="return validateForm()" method="post">
	<input type="checkbox" id="test1box" onclick="checkBoxes('Test1','test1box')"><div class="testnames"> Test 1 </div>
	<input type="checkbox" id="test2box" onclick="checkTest2()"><div class="testnames"> Test 2 </div>
	<input type="checkbox" id="finalbox" onclick="checkFinal()"><div class="testnames"> Final </div>
	<br/>
	<button type="button" class="button" onclick="uncheckBoxes()">Deselect All</button>
	<br/>
	
	<div class="rows">
		<div class="colspace">&nbsp</div>
		<div class="maincolumn">
			<div class="typehead">
				Chords
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
		<div class="maincolumn">
			<div class="typehead">
				Scales
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
</body>


</form>

<script>
checkCheckedChords();
checkCheckedScales();
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
