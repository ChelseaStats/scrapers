<?php

		include('fourfourtwo.php');
		$fft = new fourfourtwo();
		$fft->set_headers();
		$fft->print_html('Passes + Crosses');


		// we should probably validate these to make sure they are what they should be
		$group  =  isset($_POST['group'])  ? $_POST['group']  : $_POST['group'];
		$league =  isset($_POST['league']) ? $_POST['league'] : $_POST['league'];
		$year   =  isset($_POST['year'])   ? $_POST['year']   : $_POST['year'];
		$match  =  isset($_POST['match'])  ? $_POST['match']  : $_POST['match'];
		$team1  =  isset($_POST['team1'])  ? $_POST['team1']  : $_POST['team1'];
		$team2  =  isset($_POST['team2'])  ? $_POST['team2']  : $_POST['team2'];
		$plyr   =  isset($_POST['plyr'])   ? $_POST['plyr']   : $_POST['plyr'];

		if($group === 'team') {

			// create the urls for look at team level data
			$url_1  = "http://www.fourfourtwo.com/statszone/{$league}-{$year}/matches/{$match}/team-stats/{$team1}/";
			$url_2  = "http://www.fourfourtwo.com/statszone/{$league}-{$year}/matches/{$match}/team-stats/{$team2}/";

		} else {

			// create the urls for looking at player level data
			$team1  = $plyr;
			$url_1  = "http://www.fourfourtwo.com/statszone/{$league}-{$year}/matches/{$match}/player-stats/{$team1}/";
			$url_2  = '';
			$option = 'player';
		}


		if (isset($match) && $match !== '') {

			// event type array but we only care about all of them at the moment so use 01.
			// $array = array('01');
			// to find others, look at the menu and spot the numbers 1_PASS_XX

			$array = array('01');

			if(isset($option) && $option == 'player') {

				print '<h6>Single player passing</h6>';
				print "league,year,game,player,type,half,minute,outcome,coords[4]";

			} else {
				print '<h6>Team level passing</h6>';
				print "league,year,game,team,oppo,type,half,minute,outcome,coords[4]";

			}

			print '<pre>';

			foreach ($array as $k => $v) {

				if(isset($option) && $option == 'player') {

					// foreach value in the array look through the function for the url and output
					$query_1 = $url_1 . '1_PASS_' . $v . '#tabs-wrapper-anchor';
					// print '<hr/><p>'.$query_1.' : '.$v.'</p>';
					$fft->_crosses( $query_1, $league, $year, $match, $v, $team1 );
					
					// run it again for crosses
					$query_1 = $url_1 . '2_ATTACK_'. $v .'#tabs-wrapper-anchor';
					$fft->_crosses( $query_1, $league, $year, $match, $v, $team1 );

				} else {

					// foreach value in the array look through the function for the url and output
					$query_1 = $url_1 . '1_PASS_' . $v . '#tabs-wrapper-anchor';
					// print '<hr/><p>'.$query_1.' : '.$v.'</p>';
					$fft->_crosses( $query_1, $league, $year, $match, $v, $team1, $team2);

					// run it again for crosses
					$query_1 = $url_1 . '2_ATTACK_' . $v . '#tabs-wrapper-anchor';
					$fft->_crosses( $query_1, $league, $year, $match, $v, $team1, $team2);

					if ( $url_2 != '' ) {

						// two teams, so do it again, but swap team1 and team 2.
						
						$query_2 = $url_2 . '1_PASS_' . $v . '#tabs-wrapper-anchor';
						// print '<hr/><p>'.$query_2.' : '.$v.'</p>';
						$fft->_crosses( $query_2, $league, $year, $match, $v, $team2, $team1  );

						// run it again for crosses
						$query_2 = $url_2 . '2_ATTACK_' . $v . '#tabs-wrapper-anchor';
						$fft->_crosses( $query_2, $league, $year, $match, $v, $team2, $team1  );
					}

				}

			}

			print '</pre>';

		} else {

			print $fft->_getForm();

		}
?>
</div>
<div class="clearfix"><p>&nbsp;</p></div>
</body>
</html>
