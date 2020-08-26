<?php

		include('fourfourtwo.php');
		$fft = new fourfourtwo();
		$fft->set_headers();
		$fft->print_html('Passes Received');

		// we should probably validate these to make sure they are
		$group  =  isset($_POST['group'])  ? $_POST['group']  : $_POST['group'];
		$league =  isset($_POST['league']) ? $_POST['league'] : $_POST['league'];
		$year   =  isset($_POST['year'])   ? $_POST['year']   : $_POST['year'];
		$match  =  isset($_POST['match'])  ? $_POST['match']  : $_POST['match'];
		$plyr   =  isset($_POST['plyr'])   ? $_POST['plyr']   : $_POST['plyr'];

		if($group === 'team') {

			// create the urls
			$url_1 = "http://www.fourfourtwo.com/statszone/{$league}-{$year}/matches/{$match}/team-stats/{$team1}/";
			$url_2 = "http://www.fourfourtwo.com/statszone/{$league}-{$year}/matches/{$match}/team-stats/{$team2}/";

		} else {

			// create the urls
			$team1 = $plyr;
			$url_1 = "http://www.fourfourtwo.com/statszone/{$league}-{$year}/matches/{$match}/player-stats/{$team1}/";
			$url_2 ='';

		}


		if (isset($match) && $match !== '') {

			// shot type array but we only care about all of them at the moment so use 01.
			// $array = array('01');

			$array = array('07');

			print "league,year,game,(team/player),oppo team,type,half,minute,outcome,coords[4]";

			print '<pre>';

			foreach ($array as $k => $v) {

				// foreach value in the array look through the function for the url and output
				$query_1 = $url_1 . '1_PASS_' . $v . '#tabs-wrapper-anchor';
				// print '<hr/><p>'.$query_1.' : '.$v.'</p>';
				$fft->_receptions( $query_1, $league, $year, $match, $team1, $team2, $v );

				if ( $url_2 != '' ) {

					$query_2 = $url_2 . '1_PASS_' . $v . '#tabs-wrapper-anchor';
					// print '<hr/><p>'.$query_2.' : '.$v.'</p>';
					$fft->_receptions( $query_2, $league, $year, $match, $team2, $team1, $v );
				}

			}

			print '</pre>';

		} else {
			?>
			<form action="<?php $_SERVER['PHP_SELF'];?>" method="POST">

				<div class="form-group">
					<label for="group">Scrape Type:</label>
					<select name="group" id="group">
						<option value="player">Player</option>

					</select>
				</div>


				<div class="form-group">
					<label for="league">league Ref:</label>
					<select name="league" id="league">
						<option value="8">Premier League</option>
						<option value="23">La Liga</option>
						<option value="21">Serie A</option>
						<option value="22">Bundesliga</option>
						<option value="24">Ligue 1</option>

					</select>
				</div>

				<div class="form-group">
					<label for="year">year Ref:</label>
					<select name="year" id="year">

<option value="2015">2015</option>
						<option value="2014">2014</option>
						<option value="2013">2013</option>
						<option value="2012">2012</option>
						<option value="2011">2011</option>
						<option value="2010">2010</option>
						<option value="2009">2009</option>
						<option value="2008">2008</option>
						<option value="2007">2007</option>
						<option value="2006">2006</option>
						<option value="2005">2005</option>
						<option value="2004">2004</option>
					</select>
				</div>

				<div class="form-group">
					<label for="match">match ID:</label>
					<input name="match"   type="text" id="match">
				</div>

				<p><b>IDs:</b></p>

				<div class="form-group">
					<label for="plyr">Player ID:</label>
					<input name="plyr"   type="text" id="plyr">
				</div>


				<div class="form-group">
					<input type="submit" value="submit" class="btn btn-primary">
				</div>

			</form>
		<?php } ?>
</div>
<div class="clearfix"><p>&nbsp;</p></div>
</body>
</html>
