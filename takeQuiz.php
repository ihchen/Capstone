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
	<!-- Styling for this page -->
	<link href="style/takeQuiz.css" type="text/css" rel="stylesheet">

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

<center>
	<br/><br/>
	<!-- Buttons -->
	<button id="playbtn" class="button" onclick="play()" style="display:none;">Play</button>
	<button id="stopbtn" class="button" onclick="stop()" style="display:none;">Stop</button>
	<button id="loadbtn" class="button inactive" style="display:block;">Loading...</button>
	<br/>
	<!-- Button for revealing answer -->
	<button class="reveal" id="revealbutt" onclick="reveal()">Reveal Answer</button>
	<!-- Contents that display after Reveal Answer is clicked (initially hidden)-->
	<div class="reveal" id="revealed">
		<!-- Answer goes in here -->
		<p id="answer"></p>
		<!-- Next question button (disabled by default so you can't click pre-reveal) -->
		<button id="nxtq" class="button" onclick="nextQuestion()" disabled>Next Question</button>
	</div>

	<!-- List of musical elements chosen by user to quiz on -->
	<div id="chosenlist">
		<button type="button" class="smallbtn" onclick="showlist()">Show List</button><br/>
		<div id="listelements">
			<!-- List elements go here -->
		</div>
	</div>
</center>

<script>
	/**
	 * What happens once the files finishes loading
	 */
	function loadFunc() {
		document.getElementById("loadbtn").style.display = "none";
		document.getElementById("playbtn").style.display = "block";	
		document.getElementById("stopbtn").style.display = "none";
		//Get the answer and display
		document.getElementById("answer").innerHTML = snippet.answer();
		applyInversion(document.getElementById("answer"));
	}

	/* Load first question */
	var qg = new QuestionGenerator(chosen);		//Create question generator
	var snippet = qg.getNextQuestion();			//Get Next Question
	snippet.generate(loadFunc);

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
	 * Plays the audio and changes play button to stop button
	 */
	function play() {
		snippet.play();
		document.getElementById("playbtn").style.display = "none";
		document.getElementById("stopbtn").style.display = "block";
		// Change class of stopbtn so hovering works
		document.getElementById("stopbtn").className = "button";
	}

	/**
	 * Fades out audio quickly. Waits till after the fadeout to change the stop button back
	 * to the play button
	 */
	function stop() {
		snippet.fadeOut();
		//Disable button and make it un-hoverable (inactive class)
		document.getElementById("stopbtn").disabled = true;
		document.getElementById("stopbtn").className = "button inactive";

		setTimeout(function() { // delay the return of the play button until sound has faded
			document.getElementById("playbtn").style.display = "block";
			document.getElementById("stopbtn").style.display = "none";
			document.getElementById("stopbtn").disabled = false;
		}, FADE_ALL_LENGTH+400);		//FADE_ALL_LENGTH constant can be found in scripts/MusicSnippet.js	
	}

	/**
	 * Hides the answer contents and displays the loading button. Gets the next question
	 * and generates the corresponding audio (after fading finishes).
	 */
	function nextQuestion() {
		hide();
		snippet.fadeOut();				//Stop current sound
		snippet = qg.getNextQuestion();	//Get the next MusicSnippet object
		setTimeout(function() {
			snippet.generate(loadFunc);
		}, FADE_ALL_LENGTH+1);		//FADE_ALL_LENGTH constant can be found in scripts/MusicSnippet.js	
	}

	/**
	 * Reveals the answer (with transition)
	 */
	function reveal() {
		document.getElementById("revealbutt").style.opacity = "0";	//Hide button
		document.getElementById("revealbutt").style.transform = "translate(0px, 100px)";	//Move button down
		document.getElementById("revealed").className = "reveal";	//Reveal class does transitions
		document.getElementById("revealed").style.opacity = "1";	//Reveal answer
		document.getElementById("nxtq").disabled = false;			//Enable next question button
		document.getElementById("nxtq").style.cursor = "pointer";
	}

	/**
	 * Hides answer and resets buttons with no transition. and displays loading button. 
	 * Must eventually be followed by snippet.generate(). 
	 */
	function hide() {
		//Do button stuff
		document.getElementById("loadbtn").style.display = "block";
		document.getElementById("playbtn").style.display = "none";
		document.getElementById("stopbtn").style.display = "none";
		//Reset everything
		document.getElementById("revealbutt").style.opacity = "1";	//Reveal button
		document.getElementById("revealbutt").style.transform = "translate(0px, 0px)";	//Move it back up
		document.getElementById("revealed").className += " notrans";//notrans class does no transitions
		document.getElementById("revealed").style.opacity = "0";	//Hide answer content
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
