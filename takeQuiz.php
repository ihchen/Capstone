<?php
$thisPage = 'Take Quiz';
?>

<?php require_once('phpincludes/header.php'); ?>
<header>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">

	<script src="howler/howler.js"></script>
	<script src="scripts/MusicSnippet.js"></script>
	<script src="scripts/noteToFileNum.js"></script>
	<script src="scripts/MusicManip.js"></script>
	<script src="scripts/QuestionGenerator.js"></script>
	<script src="scripts/ParseCSV.js"></script>

	<link rel="stylesheet" type="text/css" href="style/style2.css">

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
</header>
<?php
	// convert post vars into javascript
	foreach ($_POST as $key => $value) {
		if ($value == "opt") {
			echo "<script type='text/javascript'>config.$key = true;</script>";
		}
		else if ($value == "num") {
			echo "<script type='text/javascript'>chosen.push($key);</script>";
		}
		else {
			echo "<script type='text/javascript'>console.log(\"POST Format Error: $key, $value\");</script>";
		}
	}
?>

<style>
/* Inversion Styling */
.inversion {
  display: inline-block;
}
.inversion .fraction {
  display: inline-block;
  position: relative;
}
.inversion .fraction > div {
  position: relative;
  text-align: center;
  height: 0;
}
.inversion .baseline-fix {
  display: inline-table;
  table-layout: fixed;
}
.top {
	font-size: .65em;
	top: -.2em;
}
.bot {
	font-size: .65em;
	top: .8em;
}

/* Transition Styling */
#revealbutt {
	background: none;
	border: none;
	margin: 0;
	padding: 0;
	font-size: 1.5em;
	position: relative;
	z-index: 1000;
}
#revealbutt:after {		
    content: "";
    width:15px;
    height:15px;
    background: transparent;
    border-right: 1px solid black;
    border-bottom: 1px solid black;
	-ms-transform:rotate(45deg); /* IE 9 */
	-webkit-transform:rotate(45deg); /* Safari and Chrome */
	transform:rotate(45deg);
    position:absolute;
    bottom:-12px; 
    left: 50%;
    margin-left: -12px;
}
.reveal {
	-webkit-transition: opacity 1s, transform 1s ease;
	-moz-transition: opacity 1s, transform 1s ease;
	transition: opacity 1s, transform 1s ease;
}
#revealbutt:hover {
	color: #008B8B;
}
#revealbutt:hover:after {
	border-right: 1px solid #008B8B;
	border-bottom: 1px solid #008B8B;
}
#revealbutt::-moz-focus-inner {
	border: 0;
}
#loading {
	position: absolute;
    width: 100px;
    height: 50px;
    top: 30%;
    left: 50%;
    margin-top: -25px;
    margin-left: -50px;
    font-size: 1.5em;
}
#chosenlist {
	position: absolute;
	left: 5%;
	top: 15%;
	padding: 5px;
}
.selectbtn {				/* Select/Deselect Buttons */
	text-align: center;
	white-space: nowrap;
	background: #008B8B;
	border: none;
	margin-bottom: 5px;
}
.selectbtn:hover {
  background: #00CED1;
}
@media screen and (max-width: 900px) {
	#chosenlist {
		position: static;
	}
	#listelements{
		-webkit-column-count: 2;
		-moz-column-count: 2;
		column-count: 2;
	}
}
@media screen and (max-width: 450px) {
	#listelements {
		-webkit-column-count: 1;
		-moz-column-count: 1;
		column-count: 1;
	}
}
</style>

<script>
	var qg = new QuestionGenerator(chosen);
	var snippet = qg.getNextQuestion();
	snippet.generate();
</script>

<center>
	<div id="mainfunctions">
		<p id="loading"><!-- Needs to have id "loading" -->
			Loading...
		</p>

		<br/><br/>

		<div id="allbuttons" style="display:none;"><!-- Needs to have id "allbuttons" -->
			<button id="playbtn" class="button" onclick="play()" style="display:block;">Play</button>
			<button id="stopbtn" class="button" onclick="stop()" style="display:none;">Stop</button>
			<br/>
			<button class="reveal" id="revealbutt" onclick="reveal()">Reveal Answer</button>
			<div class="reveal" id="revealed" style="opacity: 0; position:relative; top: -50px;">
				<p id="answer" style="font-size: 1.5em;"></p>
				<button id="nxtq" class="button" onclick="nextQuestion()" disabled style="cursor: default;">Next Question</button>
			</div>
		</div>
	</div>

	<div id="chosenlist">
		<button type="button" class="selectbtn" onclick="showlist()">Show List</button><br/>
		<div id="listelements" style="visibility: hidden;">
			<!-- List elements go here -->
		</div>
	</div>
</center>

<script>
	document.getElementById("answer").innerHTML = snippet.answer();
	applyInversion(document.getElementById("answer"));

	function play() {
		snippet.play();
		document.getElementById("playbtn").style.display = "none";
		document.getElementById("stopbtn").style.display = "block";
	}

	function stop() {
		snippet.fadeOut();
		document.getElementById("stopbtn").disabled = true;
		setTimeout(function() { // delay the return of the play button until sound has faded
			document.getElementById("playbtn").style.display = "block";
			document.getElementById("stopbtn").style.display = "none";
			document.getElementById("stopbtn").disabled = false;
		}, 101);
		
	}

	function nextQuestion() {
		hide();
		snippet.fadeOut();
		snippet = qg.getNextQuestion();
		setTimeout(function() {
			snippet.generate();
			document.getElementById("answer").innerHTML = snippet.answer();
			applyInversion(document.getElementById("answer"));
		}, 101);
	}

	function reveal() {
		document.getElementById("revealbutt").style.opacity = "0";
		document.getElementById("revealbutt").style.transform = "translate(0px, 100px)";
		document.getElementById("revealed").style.opacity = "1";
		document.getElementById("nxtq").disabled = false;
		document.getElementById("nxtq").style.cursor = "pointer";
	}

	function hide() {
		document.getElementById("loading").style.display = "block";
		document.getElementById("allbuttons").style.display = "none";
		document.getElementById("revealbutt").style.opacity = "1";
		document.getElementById("revealbutt").style.transform = "translate(0px, 0px)";
		document.getElementById("revealed").style.opacity = "0";
		document.getElementById("playbtn").style.display = "block";
		document.getElementById("stopbtn").style.display = "none";
		document.getElementById("nxtq").disabled = true;
		document.getElementById("nxtq").style.cursor = "default";		
	}

	function applyInversion(answerElement) {
	    var answerString = answerElement.innerHTML;
	    var split = answerString.split("|");
	    if(split.length == 2) {
	    	var top = split[0][split[0].length-1];
	    	var bot = split[1][0];
	    	var begin = split[0].substring(0,split[0].length-1);
	    	var end = split[1].substring(1,split[1].length);

	    	answerElement.innerHTML = begin+
	    		'<span class="inversion"><span class="fraction"><div class="top">'+top+'</div><div class="bot">'+bot+'</div><span class="baseline-fix"></span></span></span>'+end;
	    }
	};

	var list = document.getElementById("listelements");
	for(var i = 0; i < chosen.length; i++) {
		list.innerHTML = list.innerHTML + data[chosen[i]][1] + " " + data[chosen[i]][0] + " <br/>";
	}

	function showlist() {
		var div = document.getElementById("listelements");
		if(div.style.visibility == "hidden") {
			div.style.visibility = "visible";
		}
		else {
			div.style.visibility = "hidden";
		}
	}
</script>
<?php require_once('phpincludes/footer.php'); ?>
