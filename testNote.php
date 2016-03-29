<?php
$thisPage = 'Test Note';
?>

<?php require_once('phpincludes/header.php'); ?>

<body>
<br></br>
<script src = "scripts/MusicManip.js"> </script>
<script src = "scripts/Note.js"> </script>
<script src = "scripts/noteToFileNum.js"> </script>

<script>

var c4 = new Note("C", 4);
var b3 = new Note("B", 3);
var b4 = new Note("B", 4);
var bxx3 = new Note("Bxx", 3);
var g4 = new Note("G", 4);
var a4 = new Note("A", 4);
var dbb = new Note("Dbb", 4);
var dbb3 = new Note("Dbb", 3);

// document.write(c4.compareTo(b3) + "<br>");
// document.write(c4.compareTo(b4) + "<br>");
// document.write(c4.compareTo(bxx3) + "<br>");
// document.write(g4.compareTo(a4) + "<br>");
// document.write(c4.compareTo(dbb) + "<br>");
// document.write(c4.compareTo(dbb3) + "<br>");

// document.write(c4.getInterval(b3) + "<br>");
// document.write(c4.getInterval(b4) + "<br>");
// document.write(c4.getInterval(bxx3) + "<br>");
// document.write(g4.getInterval(a4) + "<br>");
// document.write(c4.getInterval(dbb) + "<br>");
// document.write(c4.getInterval(dbb3) + "<br>");

document.write(c4.toString() + " m2 " + c4.getNextNote("m2", false) + "<br>");
document.write(c4.toString() + " M6 " + c4.getNextNote("M6", true) + "<br>");
document.write(c4.toString() + " u " + c4.getNextNote("unison", true) + "<br>");
document.write(g4.toString() + " u " + g4.getNextNote("unison", false) + "<br>");
document.write(c4.toString() + " M7 " + c4.getNextNote("M7", false) + "<br>");
document.write(c4.toString() + " p5 " + c4.getNextNote("p5", true) + "<br>");

var palette = 





</script>
</body>
<?php require_once('phpincludes/footer.php'); ?>
