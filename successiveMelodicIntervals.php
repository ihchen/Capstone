<?php
$thisPage = 'Successive Melodic Intervals';
?>

<?php require_once('phpincludes/header.php'); ?>

<body>
<br></br>
<script src = "scripts/MusicManip.js"> </script>
<script src = "scripts/Note.js"> </script>
<script src = "scripts/noteToFileNum.js"> </script>
<script src = "scripts/SuccessiveMelodicIntervals.js"> </script>
<script src = "scripts/ValidateSMI.js"> </script>
<script src="//cdnjs.cloudflare.com/ajax/libs/seedrandom/2.4.0/seedrandom.min.js"></script>

<div id ="content">
Please enter the number of SMIs you would like to generate:
<form name="asdf">
  <input id="num" type="number" name="numtogenerate" value ="10" min="1" max="50" required>
  </input>
  <br></br>
  <button id="asdf" type ="button" class="button" action="#" onclick="generateSMIs(document.asdf.numtogenerate.value)">
    Generate SMIs
  </button>
</form>
  <p id="stuff"></p>

</div>


<script type="text/javascript">

  function generateSMIs(num) {
    // var num = document.getElementById('num').value;
    var text = ""
    // e.preventDefault();
    for (var i = 0; i < num; i++) {
      var smi = new SuccessiveMelodicIntervals();
      text+=smi.getNotes() + "<br>";

    }
    // document.getElementById("asdf").disabled = true;
    document.getElementById("stuff").innerHTML = text;

    return false;

  }
</script>
</body>
<?php require_once('phpincludes/footer.php'); ?>
