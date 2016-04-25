<?php
$thisPage = 'Successive Melodic Intervals';
// header("Location: successiveMelodicIntervals.php", true, 303);
?>

<?php require_once('phpincludes/header.php'); ?>

<style>
#smi {
	text-align: center;
	padding: 1em;
	margin-left: 0;
	margin-right: 0;
	margin-top: 0;
	margin-bottom: 0;
	height: 260px;
}

#nextquestion {
	text-align: center;
	margin: 0 auto;
	margin-top: 1em;
	margin-bottom: 1em;
}

#checkanswers {
	text-align: center;
	margin: 0 auto;
	margin-top: 1em;
	margin-bottom: 1em;
	margin-left: auto;
	margin-right: auto;

}

#answers {
	font-size: 1em;
	text-align: center;
	/*margin: 0, auto;*/
}

#answers li {
	text-align: center;
	display: inline-block;
	min-width: 66px;
	/*padding-left: 1.5em;
	padding-right: 1.5em;*/
	/*margin: 1em, 1em, 1em, 1em;*/
  /*position:inherit;*/
}

#answers > ul {
	list-style: none;
	margin: 0;
	padding: 0;
	/*position:sticky;*/
}

#answers > ul > li {
	/*background: #008B8B;*/
	/*border: 1px solid #008080;*/
	float:none;
	/*padding: 1em 0em;*/
}

</style>

<body>
<br></br>
<script src = "scripts/MusicManip.js"> </script>
<script src = "scripts/MusicSnippet.js"> </script>
<script src = "howler/howler.js"> </script>
<script src = "scripts/Note.js"> </script>
<script src = "scripts/noteToFileNum.js"> </script>
<script src = "scripts/SuccessiveMelodicIntervals.js"> </script>
<script src = "scripts/ValidateSMI.js"> </script>
<script src="//cdnjs.cloudflare.com/ajax/libs/seedrandom/2.4.0/seedrandom.min.js"></script>

<script>
  var on_load = function() {
    document.getElementById("loadbtn").style.display = "none";
    document.getElementById("playbtn").style.display = "block";
  }

  var n = 4;
  var smi = new SuccessiveMelodicIntervals(n);
  var answers = smi.getAnswers();
  // document.write(answers);
  var snippet = new MusicSnippet(smi.getNotes());
  snippet.generate(on_load);
  var bpm = 80;
  snippet.setBPM(bpm);
  var inst = "Identify the interval between each pair of consecutive notes. " + n +" notes will be played. Select your answers from the drop down menus."

</script>
<div id ="smi">
  <div id="instructions" class="">
    <script>
      document.write(inst);
    </script>
  </div>

  <button id="playbtn" class="button" onclick="play()" style="display:none;">Play</button>
  <button id="stopbtn" class="button" onclick="stop()" style="display:none;">Stop</button>
  <button id="loadbtn" class="button inactive" disabled="true"  style="display:block; color:black">Loading...</button>


  <div id="intervals">
    <script>
    var intervals = ["--", "m2", "M2", "m3", "M3", "P4", "tritone", "P5", "m6", "M6", "m7", "M7"];
    for (var i = 0; i < answers.length; i++) {
      document.write("<select id=\"" + i + "\">");
      for (var j = 0; j < intervals.length; j++) {
        document.write("<option value=\"" + intervals[j] + "\">" + intervals[j] + "</option>");
      }
      document.write("</select>");
    }
    </script>
    <br></br>

    <span id="alert" display="none">
    </span>

    <span id="answers"> </span>
    <br></br>
    </div>
  </div>

    <button id="checkanswers" class="button" style="display:block;" onclick="return checkAnswers()">Check Answers</button>
    <button id="nextquestion" class="button" style="display:none;" onclick="return nextQuestion()">Next Question</button>


</body>

<script type="text/javascript">

  function play() {
    snippet.play();
		document.getElementById("playbtn").style.display = "none";
		document.getElementById("stopbtn").style.display = "block";
    document.getElementById("stopbtn").className = "button";

  }

  function stop() {
    snippet.fadeOut();
		document.getElementById("stopbtn").disabled = true;
    document.getElementById("stopbtn").className = "button inactive";

		setTimeout(function() { // delay the return of the play button until sound has faded
			document.getElementById("playbtn").style.display = "block";
			document.getElementById("stopbtn").style.display = "none";
			document.getElementById("stopbtn").disabled = false;
		}, FADE_ALL_LENGTH+600);
  }

  function checkAnswers() {
    // document.getElementById("checkanswers").style.background= "#008B8B";
    // document.getElementById("checkanswers").style.border= "1px solid #008080";
    document.getElementById("alert").innerHTML = "";
    document.getElementById("alert").style.display = "none";


    var userAnswers = [];
    // Make sure the user has selected answers.
    for (var i = 0; i < answers.length; i++) {
      userAnswers.push(document.getElementById(i).value);
      if (userAnswers[i] == "--") {
        document.getElementById("alert").style.display = "inline-block";
        document.getElementById("alert").innerHTML = "Please select your answers from the drop down menus!";
        document.getElementById("alert").style.opacity = "0";
        document.getElementById("alert").style.transform = "translate(0px, 100px)";
        document.getElementById("alert").style.opacity = "1";
        return false;
      }
    }

    var result = [];

    for (var i = 0; i < answers.length; i++) {
      if (userAnswers[i] == answers[i]) {
        result.push(true);
      } else {
        result.push(false);
      }
    }

    var resultstring = "<ul list-style=none>";
    for (var i = 0; i < result.length; i++) {
      // resultstring += "<span style=\"color:#";
      resultstring += "<li position=relative style=\"color:#";


      if (result[i]) {
        // Checkmark character
        resultstring += "00b300\">&#10003";
      } else {
        // "Ballot x" character
        // resultstring += "FF0000\">&#10007";
        resultstring += "FF0000\">" + answers[i];
      }

      resultstring += "</li>";

    }
    resultstring += "</ul>";
    document.getElementById("answers").innerHTML = resultstring;
    document.getElementById("checkanswers").style.display = "none";
    document.getElementById("nextquestion").style.display = "block";


    return false;

  }

  function nextQuestion() {
    // display loading button
    document.getElementById("loadbtn").style.display = "block";
    document.getElementById("playbtn").style.display = "none";

    document.getElementById("answers").innerHTML = "";
    document.getElementById("checkanswers").style.display = "block";
    document.getElementById("nextquestion").style.display = "none";
    document.getElementById("instructions").innerHTML = inst;


    // reset drop down menus
    for (var i = 0; i < answers.length; i++) {
      document.getElementById(i).selectedIndex = 0;
    }

    var smi = new SuccessiveMelodicIntervals();
    answers = smi.getAnswers();
    // document.write(answers);
    snippet = new MusicSnippet(smi.getNotes());
    snippet.generate(on_load);
    var bpm = 80;
    snippet.setBPM(bpm);
    return false;
  }

</script>

<?php require_once('phpincludes/footer.php'); ?>
