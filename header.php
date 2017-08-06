
  <link rel="stylesheet" href="css/styleHeader.css">

<div id="header">
    <h2>Mobile Survey System</h2>
	<div id="log">
		<?php
			if(isset($_SESSION['user'])) {
				echo " <a href='logout.php'>logout</a> ".$_SESSION['user'];
			} else {
				echo "<a href='login.php'>Login</a>";
			}
		?>
	</div>
    <p>Make distributed survey system and manage it from one place!</p>
    <h4><span class=red>Under construction!<span></h4>
</div>
<div>
  <ul id="menu">
        <li><a href="index.php">home</a></li>
        <li><a href="new_survey.php">new survey</a></li>
        <li><a href="results.php">results</a></li>
    </ul>
</div>