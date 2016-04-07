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

	<script type="text/javascript">
		// console.log(config);
		// console.log(chosen);
	</script>

<style>
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
</style>

<script>
	var qg = new QuestionGenerator(chosen);
	var snippet = qg.getNextQuestion();
	snippet.generate();
</script>

	<center>
		<div id="loading" style="display:block;"><!-- Needs to have id "loading" -->
			Loading...
		</div>

		<br></br>
		<br></br>

		<div id="allbuttons" style="display:none;"><!-- Needs to have id "allbuttons" -->
			<button id="playbtn" class="button" onclick="play()" style="display:block;">Play</button>
			<button id="stopbtn" class="button" onclick="stop()" style="display:none;">Stop</button>
			<br><br>
			<button id="revealbutt" onclick="reveal()">Reveal Answer</button>

			<div id="revealed" style="display:none;">
				<p id="answer" style="font-size: 2em"></p>
				<button class="button" onclick="nextQuestion()">Next Question</button>
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
		document.getElementById("revealbutt").style.display = "none";
		document.getElementById("revealed").style.display = "block";
	}

	function hide() {
		document.getElementById("loading").style.display = "block";
		document.getElementById("revealbutt").style.display = "block";
		document.getElementById("revealed").style.display = "none";
		document.getElementById("playbtn").style.display = "block";
		document.getElementById("stopbtn").style.display = "none";
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
</script>
<?php require_once('phpincludes/footer.php'); ?>
