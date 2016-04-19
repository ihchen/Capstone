<?php
	$thisPage = 'Take Quiz';
	require_once('phpincludes/header.php'); 
?>
<header>
	<!-- Files to play audio -->
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
#loading {						/* Loading text */
    font-size: 1.5em;
    margin-bottom: 157px;		/* Makes the show list button stay in around the same place */
}
#element{						/* Each list element */
	display: inline-block;
	white-space: nowrap;
}

/* Inversion Styling */
.fraction {						/* Make inversion inline with text */		
  display: inline-block;
  position: relative;
}
.fraction > div {				/* Styling the top and bottom elements of fraction */
  position: relative;
  text-align: center;
  height: 0;
}
.baseline-fix {					/* Adjusting fraction a little */
  display: inline-table;
  table-layout: fixed;
}
.top {							/* Top element of fraction */
	font-size: .65em;
	top: -.2em;
}
.bot {							/* Bottom element of fraction */
	font-size: .65em;
	top: .8em;
}

/* Button styling */
#revealbutt {					/* Reveal Answer button */
	background: none;
	border: none;
	margin: 0;
	padding: 0;
	font-size: 1.5em;
	position: relative;
	z-index: 1;					/* Make sure it's in front of the answer */
}
#revealbutt:after { 			/* Down arrow below reveal answer button (It's half of a rotated square) */	
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
#revealbutt:hover {
	color: #008B8B;
}
#revealbutt:hover:after {
	border-right: 1px solid #008B8B;
	border-bottom: 1px solid #008B8B;
}
#revealbutt::-moz-focus-inner {		/* (Mozilla) Prevent Dotted border after clicking */
	border: 0;
}
#revealbutt:focus {					/* (Chrome) Prevent Dotted border after clicking */
	outline: none;
}
.selectbtn {						/* Button for show list */
	text-align: center;
	white-space: nowrap;
	background: #008B8B;
	border: none;
	margin-bottom: 5px;
}
.selectbtn:hover {
  background: #00CED1;
}

/* Transitions */
.reveal {
	-webkit-transition: opacity 1s, transform 1s ease;
	-moz-transition: opacity 1s, transform 1s ease;
	transition: opacity 1s, transform 1s ease;
}
</style>

<script>
	var qg = new QuestionGenerator(chosen);		//Create question generator
	var snippet = qg.getNextQuestion();			//Get Next Question
	snippet.generate();							//Generate audio
</script>

<center>
	<br/><br/>
	<p id="loading"><!-- Needs to have id "loading" -->
		Loading...
	</p>

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

	<div id="chosenlist">
		<button type="button" class="selectbtn" onclick="showlist()">Show List</button><br/>
		<div id="listelements" style="display: none;">
			<!-- List elements go here -->
		</div>
	</div>
</center>

<script>
	//Get the answer and display
	document.getElementById("answer").innerHTML = snippet.answer();
	applyInversion(document.getElementById("answer"));

	/**
	 * Plays the audio and changes play button to stop button
	 */
	function play() {
		snippet.play();
		document.getElementById("playbtn").style.display = "none";
		document.getElementById("stopbtn").style.display = "block";
	}

	/**
	 * Fades out audio quickly. Waits till after the fadeout to change the stop button back
	 * to the play button
	 */
	function stop() {
		snippet.fadeOut();
		document.getElementById("stopbtn").disabled = true;
		setTimeout(function() { // delay the return of the play button until sound has faded
			document.getElementById("playbtn").style.display = "block";
			document.getElementById("stopbtn").style.display = "none";
			document.getElementById("stopbtn").disabled = false;
		}, 101);
		
	}

	/**
	 * Gets the next question. Hides the buttons and displays the Loading text. Waits until the fade out
	 * ends to generate the new audio and answer.
	 */
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

	/**
	 * Reveals the answer (with transition)
	 */
	function reveal() {
		document.getElementById("revealbutt").style.opacity = "0";
		document.getElementById("revealbutt").style.transform = "translate(0px, 100px)";
		document.getElementById("revealed").style.opacity = "1";
		document.getElementById("nxtq").disabled = false;
		document.getElementById("nxtq").style.cursor = "pointer";
	}

	/**
	 * Hides the quizzing functionality and displays the loading sign. Must eventually be followed by
	 * snippet.generate(). 
	 */
	function hide() {
		document.getElementById("loading").style.display = "block";
		document.getElementById("allbuttons").style.display = "none";
		//Reset everything
		document.getElementById("revealbutt").style.opacity = "1";
		document.getElementById("revealbutt").style.transform = "translate(0px, 0px)";
		document.getElementById("revealed").style.opacity = "0";
		document.getElementById("playbtn").style.display = "block";
		document.getElementById("stopbtn").style.display = "none";
		document.getElementById("nxtq").disabled = true;			//Disable next question button so you can't click on it until revealed
		document.getElementById("nxtq").style.cursor = "default";	//Don't show hand pointer either
	}

	/**
	 * For 7th chords only (MusicSnippet for 7th chords will have a '|' in their answer);
	 */
	function applyInversion(answerElement) {
	    var answerString = answerElement.innerHTML;
	    var split = answerString.split("|");		//Split by pipe
	    //If was a 7th chord
	    if(split.length == 2) {
	    	//Extract the top and bottom parts of the fraction
	    	var top = split[0][split[0].length-1];
	    	var bot = split[1][0];
	    	var begin = split[0].substring(0,split[0].length-1);	//Text before fraction
	    	var end = split[1].substring(1,split[1].length);		//Text after fraction

	    	//Update answer
	    	answerElement.innerHTML = begin+
    			'<span class="fraction">'+
    				'<div class="top">'+top+'</div>'+
    				'<div class="bot">'+bot+'</div>'+
    				'<span class="baseline-fix"></span></span>'+end;
	    }
	};

	/* Populate the list of user chosen elements */
	var list = document.getElementById("listelements");
	//Loop through all chosen elements
	for(var i = 0; i < chosen.length; i++) {
		//Add them into the list
		list.innerHTML = list.innerHTML + 
			"<div id='element'>" + data[chosen[i]][1] + " " + data[chosen[i]][0] + 
			"</div>";
		//Seperate each element by white space and a pipe (except the last one)
		if(i < chosen.length-1) {
			list.innerHTML = list.innerHTML + "&nbsp|&nbsp";
		}
	}

	/**
	 * Shows and hides the list of chosen elements
	 */
	function showlist() {
		var div = document.getElementById("listelements");
		//If not currently displayed, display
		if(div.style.display == "none") {
			div.style.display = "block";
		}
		//If currently displayed, hide
		else {
			div.style.display = "none";
		}
	}
</script>
<?php require_once('phpincludes/footer.php'); ?>
