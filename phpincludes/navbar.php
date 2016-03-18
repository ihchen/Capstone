
<nav id = "nav" role = "navigation">
  <a href = "#nav" title = "Show Navigation">Show</a>
  <div id = "#navicon"></div>
  <a href = "#" title = "Hide navigation">Hide</a>
  <ul>
    <li <?php if ($thisPage == 'Make My Own Practice Quiz'){echo 'id = "currentpage"';} ?>>
      <a href = "index.php">Make My Own Practice Quiz</a></li>
    <li <?php if ($thisPage == 'Test Octaves'){echo 'id = "currentpage"';} ?>>
      <a href = "testOctave.php">Test Octaves</a></li>
    <li <?php if ($thisPage == 'Test MusicManip'){echo 'id = "currentpage"';} ?>>
      <a href = "testMusicManip.php">Test MusicManip</a></li>
    <li <?php if ($thisPage == 'Review'){echo 'id = "currentpage"';} ?>>
      <a href = "selectSample.php">Review</a></li>
  </ul>
</nav>
