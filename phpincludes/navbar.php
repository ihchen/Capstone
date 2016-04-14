
<nav id = "nav" role = "navigation">
  <a href = "#nav" id = "navicon" title = "Show Menu">&#9776;</a>
  <a href = "#" id = "navicon" title = "Hide Menu">&#9776;</a>
  <ul>
    <li <?php if ($thisPage == 'Design Quiz'){echo 'id = "currentpage"';} ?>>
      <a href = "index.php" class = "menu-item" title = "Select from a list sonorities you would like to practice identifying.">
        Design Quiz</a></li>
    <li <?php if ($thisPage == 'Review'){echo 'id = "currentpage"';} ?>>
      <a href = "review.php" class = "menu-item" title="Review chords and scales.">Review</a></li>
    <li <?php if ($thisPage == 'Successive Melodic Intervals'){echo 'id = "currentpage"';} ?>>
      <a href = "successiveMelodicIntervals.php" class = "menu-item" title="Indentify melodic intervals in succession.">Successive Melodic Intervals</a></li>
  </ul>
</nav>
