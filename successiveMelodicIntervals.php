<?php
$thisPage = 'Successive Melodic Intervals';
?>

<?php require_once('phpincludes/header.php'); ?>

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
  var smi = new SuccessiveMelodicIntervals();
  var answers = smi.getAnswers();
  document.write(answers);
  var snippet = new MusicSnippet(smi.getNotes());
  snippet.generate();
  var bpm = 80;
  snippet.setBPM(bpm);

</script>
<div id ="smi">
  <div id="instructions" class="">
    Indentify the interval between each note. Select your answers from the drop down menus.
  </div>

<button id="playbtn" class="button" onclick="play()" style="display:block;">Play</button>
<button id="stopbtn" class="button" onclick="stop()" style="display:none;">Stop</button>

<form method="post" name="intervals" onSubmit="window.location.reload()">
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

  <span id="answers"> </span>
  <br></br>
  <button id="checkanswers" class="button" onclick = "return checkAnswers()">Check Answers</button>
  <input type = "submit" id="nextquestion" value = "Next Question" class="button" style="display:none;" action=""></input>


</form>

</div>
</body>

<script type="text/javascript">

  function play() {
    document.getElementById("playbtn").style.display = "none";
    document.getElementById("stopbtn").style.display = "block";
    snippet.play();
  }

  function stop() {
    snippet.fadeOut();
    document.getElementById("playbtn").style.display = "block";
    document.getElementById("stopbtn").style.display = "none";
  }

  function checkAnswers() {
    var userAnswers = [];
    // Make sure the user has selected answers.
    for (var i = 0; i < answers.length; i++) {
      userAnswers.push(document.getElementById(i).value);
      if (userAnswers[i] == "--") {
        document.getElementById("instructions").innerHTML = "Please select your answers from the drop down menus!";
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

    // document.getElementById("asdf").disabled = true;
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
      // if (i > result.length-1) {
      //   resultstring += "&nbsp";
      // }
      resultstring += "</li>";

    }
    resultstring += "</ul>";
    document.getElementById("answers").innerHTML = resultstring;
    document.getElementById("checkanswers").style.display = "none";
    document.getElementById("nextquestion").style.display = "block";


    return false;

  }

</script>

<?php require_once('phpincludes/footer.php'); ?>
