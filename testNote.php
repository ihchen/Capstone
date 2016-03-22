<?php
$thisPage = 'Test Note';
?>

<?php require_once('phpincludes/header.php'); ?>

<body>
<br></br>
<script src = "scripts/MusicManip.js"> </script>
<script src = "scripts/Note.js"> </script>
<script>

var c4 = new Note("C", 4);
var b3 = new Note("B", 3);
var b4 = new Note("B", 4);
var bxx3 = new Note("Bxx", 3);
var g4 = new Note("G", 4);
var a4 = new Note("A", 4);

document.write(c4.compareTo(b3));
document.write(c4.compareTo(b4));
document.write(c4.compareTo(bxx3));
document.write(c4.compareTo(b3));
   




</script>
</body>
<?php require_once('phpincludes/footer.php'); ?>
