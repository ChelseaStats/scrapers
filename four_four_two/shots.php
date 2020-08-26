
<?php
		include('fourfourtwo.php');
		$fft = new fourfourtwo();
		$fft->set_headers();
		$fft->print_html('Shots');
		// we should probably validate these to make sure they are
		$group  =  isset($_POST['group'])  ? $_POST['group']  : $_POST['group'];
		$league =  isset($_POST['league']) ? $_POST['league'] : $_POST['league'];
		$year   =  isset($_POST['year'])   ? $_POST['year']   : $_POST['year'];
		$match  =  isset($_POST['match'])  ? $_POST['match']  : $_POST['match'];
		$team1  =  isset($_POST['team1'])  ? $_POST['team1']  : $_POST['team1'];
		$team2  =  isset($_POST['team2'])  ? $_POST['team2']  : $_POST['team2'];
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
		// $array = array('01','02','03','04','05','55','56','06','07','08','09','10','11','12','13','14');
		print "league,year,game,(team/player),type,half,minute,outcome,coords[4]";

		$array = array('01','07','09');
		print '<pre>';
		foreach ($array as $k => $v) {
			// foreach value in the array look through the function for the url and output
			$query_1 = $url_1 . '0_SHOT_' . $v . '#tabs-wrapper-anchor';
			// print '<hr/><p>'.$query_1.' : '.$v.'</p>';
			$fft->_shots( $query_1, $league, $year, $match, $team1, $team2, $v );
			if ( $url_2 != '' ) {
				$query_2 = $url_2 . '0_SHOT_' . $v . '#tabs-wrapper-anchor';
				// print '<hr/><p>'.$query_2.' : '.$v.'</p>';
				$fft->_shots( $query_2, $league, $year, $match, $team2, $team1, $v );
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