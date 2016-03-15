<?php
$thisPage = 'Test MusicManip';
?>

<?php require_once('phpincludes/header.php'); ?>

<link rel="stylesheet" type="text/css" href="style/style2.css">

<?php require_once('phpincludes/navbar.php'); ?>
<br></br>
<br></br>

<script src = "scripts/MusicManip.js"></script>
<script src = "scripts/ParseCSV.js"></script>
<script src = "scripts/MusicSnippet.js"></script>
<script src = "scripts/QuestionGenerator.js"></script>
<script src = "scripts/noteToFileNum.js"></script>
<script src = "howler/howler.js"></script>

<script>

var userSelection = [0, 3];
var qGen = new QuestionGenerator(userSelection);
var musicSnippet = qGen.getNextQuestion();

document.write(musicSnippet.answer());
document.write("<br>");
document.write(musicSnippet.answer());
</script>

<?php require_once('phpincludes/footer.php'); ?>
