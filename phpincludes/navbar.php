
<nav id = "nav" role = "navigation">
  <a href = "#nav" id = "navicon" title = "Show Menu">&#9776;</a>
  <a href = "#" id = "navicon" title = "Hide Menu">&#9776;</a>
  <ul>
    <li <?php if ($thisPage == 'Make My Own Practice Quiz'){echo 'id = "currentpage"';} ?>>
      <a href = "index.php" class = "menu-item" title = "Select from a list sonorities you would like to practice identifying.">
        Make My Own Practice Quiz</a></li>
    <!-- <li <?php if ($thisPage == 'Test Octaves'){echo 'id = "currentpage"';} ?>>
      <a href = "testOctave.php" class = "menu-item">Test Octaves</a></li>
    <li <?php if ($thisPage == 'Test MusicManip'){echo 'id = "currentpage"';} ?>>
      <a href = "testMusicManip.php" class = "menu-item">Test MusicManip</a></li> -->
    <li <?php if ($thisPage == 'Review'){echo 'id = "currentpage"';} ?>>
      <a href = "selectSample.php" class = "menu-item">Review</a></li>
  </ul>
</nav>
