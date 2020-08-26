<?php

include('fourfourtwo.php');
$fft = new fourfourtwo();
$fft->set_headers();

$fft->print_html('Hello');

?>
<div class="container">
	<ul>
		<li><a href='shots.php'>Shots</a></li>
		<li><a href='assists.php'>Chances/Assists</a></li>
		<li><a href='passes.php'>(player) All Passes</a></li>
		<li><a href='crosses.php'>(player) All Passes + crosses</a></li>
		<li><a href='receptions.php'>(player) Passes received</a></li>
		<li><a href='players.php'>Get player IDs for a game</a></li>
		
	</ul>
</div>

