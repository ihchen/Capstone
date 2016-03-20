<?php
$thisPage = 'Test Octaves';
?>

<?php require_once('phpincludes/header.php'); ?>

<!-- <h1>Testing octave errors with weird accidentals</h1> -->

<body>

  <script src = "scripts/MusicManip.js"> </script>
  <script>

  var cmaj = ["C", "D", "E", "F", "G", "A", "B", "C"];

  for (var i = -14; i < 15; i++) {
    notesarray = transpose(cmaj, i);
    notesarray = setOctave(notesarray);
    document.write("<br>" + notesarray);
  }

  var amin = ["A", "C", "E"];

  for (var i = -14; i < 15; i++) {
    notesarray = transpose(amin, i);
    notesarray = setOctave(notesarray);
    document.write("<br>" + notesarray);
  }

  </script>
</body>
<?php require_once('phpincludes/footer.php'); ?>
