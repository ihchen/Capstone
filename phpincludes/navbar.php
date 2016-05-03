<link href="style/navbar.css" type="text/css" rel="stylesheet">

<nav class="navbar">
	<!-- For mobile, make navigation box with hamburger menu icon -->
	<label id="navicon" for="navbox"><img src="images/navicon.png" alt="" id="navimage"></label>
	<input type="checkbox" id="navbox">		<!-- Hidden checkbox that shows or hides the menu -->

	<!-- Links on the menu -->
	<ul class="navlist" id="navlist">
		<!-- Design Quiz -->
		<li class="navelement" title = "Select from a list sonorities you would like to practice identifying."
			<?php
				//If this is the current page, then set id to style it
				if ($thisPage == 'Design Quiz'){echo 'id = "currentpage"';} 
			?>
		>
			<a class="navlink" href="index.php">Design Quiz</a>
		</li>
		<!-- Review -->
		<li class="navelement" title="Review chords and scales."
			<?php 
				if ($thisPage == 'Review'){echo 'id = "currentpage"';} 
			?>
		>
			<a class="navlink" href="review.php">Review</a>
		</li>
		<!-- Successive Melodic Intervals -->
		<li class="navelement" title="Indentify melodic intervals in succession."
			<?php 
				if ($thisPage == 'Successive Melodic Intervals'){echo 'id = "currentpage"';} 
			?>
		>
			<a class="navlink" href="successiveMelodicIntervals.php">Successive Melodic Intervals</a>
		</li>
	</ul>
</nav>