<html>
<body>
<h1>CornerStat's ExG WhoScored Scraper</h1>
<?php
$HEADERexg	= 1.13;
$CROSSexg	= 0.97;
$REGULARexg	= 0.85;
$CROSSHEADERexg = 0.65;
$PENALTYexg	= 0.85;
$OWNGOALexg	= 0.85;
$DRIBBLEexg	= 1.11;


//$address = 829742;
	$address = $_POST['address'];

	if(isset($address) && $address !='') {

		ob_start(); // ensures anything dumped out will be caught
//url
		$text = file_get_contents('http://www.whoscored.com/Matches/'.$address.'/Live');

//team names
		$alpha = strpos($text,'data-text=');
		$omega = strpos($text,': Live player ratings and detailed ');
		$len = $omega-$alpha;
		$names = substr($text, $alpha+11, $len-11);
		$names = explode("vs",$names);
		echo trim($names[0])." vs ".trim($names[1]);
		echo "</br>";

//home team ids
		$hteamstart = strpos($text,'teamId');
		$hteamend = strpos($text,'formations');
		$hlength = $hteamend-$hteamstart;
		$hteam = substr($text, $hteamstart+8, $hlength-10);

//away team ids
		$ateamstart = strpos($text,'away');
		$ateamend = strpos($text,'formations',$ateamstart+1);
		$alength = $ateamend-$ateamstart;
		$ateam = substr($text, $ateamstart+16, $alength-18);

//date
		$datestart = strpos($text,'startTime');
		$dateend = strpos($text,'startDate');
		$datelen = $dateend-$datestart;
		$date = substr($text, $datestart+12, $datelen-23);
		echo $date." (US format)";
		echo "</br>";

//score
		$scorestart = strpos($text,'ftScore');
		$scoreend = strpos($text,'etScore');
		$scorelen = $scoreend-$scorestart;
		$score = substr($text, $scorestart+10, $scorelen-13);


//min by min
		$start = strpos($text,"expandedMaxMinute");
		$end = strpos($text,"PostGame");
		$text = substr($text, $start, $end-$start);
		$sections = explode('id',$text);
		$events = count($sections);

		function multiexplode ($delimiters,$string) {
			$ready = str_replace($delimiters, $delimiters[0], $string);
			$launch = explode($delimiters[0], $ready);
			return  $launch;
		}

		$t=0; $n=0;
		while($t <= $events) {

			$small = multiexplode(array('":',',"'),$sections[$t]);

//team id
			$smallcount = count($small);
			while ($n <= $smallcount){
				if( strpos($small[$n], 'teamId') !== FALSE)
				{$team= $small[$n+1];}
				if($team == $hteam){$team=trim($names[0]);}
				if($team == $ateam){$team=trim($names[1]);}

//clock
				if( strpos($small[$n], 'minute') !== FALSE)
				{$min= $small[$n+1];}
				if( strpos($small[$n], 'second') !== FALSE)
				{$sec= $small[$n+1];}

//x&y
				if( $small[$n] == 'x')
				{$x= $small[$n+1];}
				if( $small[$n] == 'y')
				{$y= $small[$n+1];}

//angle
				if($y>=50){$opposite = $y-50;}
				if($y<50){$opposite = 50-$y;}

//make x 'correct' if own goal
				if( strpos($small[$n],'Goal') !== FALSE && strpos($sections[$t],'isOwnGoal') !== FALSE) {$x=100-$x;}

				$adjacient = 100-$x;

//account for OG x value!!!!
				$angle = 180-90-round(57.296*atan($opposite/$adjacient),1);
				$distance = round(sqrt(pow($opposite,2)+pow($adjacient,2)),1);
//channel 6 yards wide, same distance back 53.96 46.04
				if($y>=50){$channel = 53.96;}
				if($y<50){$channel = 46.04;}

				if($y>=50){$ccopposite = $channel-50;}
				if($y<50){$ccopposite = 50-$channel;}
				$cc = 180-90-round(57.296*atan($ccopposite/$adjacient),1);
				$ratio = round(($angle/$cc),1);
//Adjusted Distance to Goal = (Distance from end line) / (relative angle ^ 1.32)
				$adjdistance = round((100-$x)/(pow($ratio,1.32)),1);

// if shot, then print
				if( strpos($small[$n],'Shot') !== FALSE  && strpos($small[$n],'Goal') == FALSE && strpos($small[$n],'ShotAssist') == FALSE && strpos($small[$n],'isOwnGoal')== FALSE )
				{echo $min."mins, ".$sec."secs, ".str_replace(array('{','}',',','type','"'),'',$small[$n]).", ".$team;
					echo ", x=".$x.", y=".$y." angle=".$angle." dist=".$distance." ratio=".$ratio." adjdist=".$adjdistance;
				}
				$tally=0;
				if( strpos($small[$n],'Shot') !== FALSE  && strpos($small[$n],'Goal') == FALSE && strpos($small[$n],'ShotAssist') == FALSE && strpos($small[$n],'isOwnGoal')== FALSE)
				{if( strpos($sections[$t],'FastBreak') !== FALSE) {$type1='DRIBBLE'; $tally++;}
					if( strpos($sections[$t],'SetPiece') !== FALSE) {$type2='CROSS';$tally++;}
					if( strpos($sections[$t],'FromCorner') !== FALSE) {$type3='CROSS';$tally++;}
					if( strpos($sections[$t],'Freekick') !== FALSE) {$type4='CROSS';$tally++;}
					if( strpos($sections[$t],'Corner situation') !== FALSE) {$type5='CROSS';$tally++;}
					if( strpos($sections[$t],'directFree') !== FALSE) {$type6='CROSS';$tally++;}
					if( strpos($sections[$t],'Head') !== FALSE) {$type7='HEADER';$tally++;}
					if( strpos($sections[$t],'Penalty') !== FALSE) {$type8='PENALTY';$tally++;}
					if( strpos($sections[$t],'isOwnGoal') !== FALSE) {$type9='OWNGOAL';$tally++;}
					$concatenate1 = $type1.$type2.$type3.$type4.$type5.$type6.$type7.$type8.$type9;
					if ($tally == 0){$concatenate4 = "REGULAR";}
					$concatenatea = $concatenate1.$concatenate4;
					echo " ".$concatenatea;
//calc exG
					$equation = $concatenatea.$concatenateb;
					if($equation == 'HEADER')	{$exg = $HEADERexg*exp(-0.27* $adjdistance);}
					if($equation == 'CROSS')	{$exg = $CROSSexg*exp(-0.19* $adjdistance);}
					if($equation == 'REGULAR')	{$exg = $REGULARexg*exp(-0.13* $adjdistance);}
					if($equation == 'CROSSHEADER')	{$exg = $CROSSHEADERexg*exp(-0.21* $adjdistance);}
					if($equation == 'PENALTY')	{$exg = $PENALTYexg;}
					if($equation == 'OWNGOAL')	{$exg = $OWNGOALexg*exp(-0.13* $adjdistance); $og =1;}
					if($equation == 'DRIBBLE')	{$exg = $DRIBBLEexg*exp(-0.10* $adjdistance);}
					echo " exG = ".round($exg,2);
					if($og==1){
						if($team == trim($names[1])){$totexghome = $totexghome + $exg;}
						if($team == trim($names[0])){$totexgaway = $totexgaway + $exg;}
					}
					if($og !==1){
						if($team == trim($names[0])){$totexghome = $totexghome + $exg;}
						if($team == trim($names[1])){$totexgaway = $totexgaway + $exg;}
					}
					echo "</br>"; $og='';
				}


// if GOAL, then print
				if( strpos($small[$n],'Goal') !== FALSE  && strpos($small[$n],'Goalkeeper') == FALSE && strpos($small[$n],'LeadingToGoal') == FALSE && strpos($small[$n],'Assist') == FALSE &&  strpos($small[$n],'Kick') == FALSE &&  strpos($small[$n],'Disallowed') == FALSE &&  strpos($small[$n],'Mouth') == FALSE && strpos($small[$n],'OwnGoal')== FALSE)
				{echo $min."mins, ".$sec."secs, ".str_replace(array('{','}',',','type','"'),'',$small[$n]).", ".$team;
					echo ", x=".$x.", y=".$y." angle=".$angle." dist=".$distance." ratio=".$ratio." adjdist=".$adjdistance;
				}

				if( strpos($small[$n],'Goal') !== FALSE  && strpos($small[$n],'Goalkeeper') == FALSE && strpos($small[$n],'LeadingToGoal') == FALSE && strpos($small[$n],'Assist') == FALSE &&  strpos($small[$n],'Kick') == FALSE &&  strpos($small[$n],'Disallowed') == FALSE &&  strpos($small[$n],'Mouth') == FALSE && strpos($small[$n],'OwnGoal')== FALSE )
				{if( strpos($sections[$t],'FastBreak') !== FALSE) {$typea1='DRIBBLE';$tally++;}
					if( strpos($sections[$t],'SetPiece') !== FALSE) {$typea2='CROSS';$tally++;}
					if( strpos($sections[$t],'FromCorner') !== FALSE) {$typea3='CROSS';$tally++;}
					if( strpos($sections[$t],'Freekick') !== FALSE) {$typea4='CROSS';$tally++;}
					if( strpos($sections[$t],'Corner situation') !== FALSE) {$typea5='CROSS';$tally++;}
					if( strpos($sections[$t],'directFree') !== FALSE) {$typea6='CROSS';$tally++;}
					if( strpos($sections[$t],'Head') !== FALSE) {$typea7='HEADER';$tally++;}
					if( strpos($sections[$t],'Penalty') !== FALSE) {$typea8='PENALTY';$tally++;}
					if( strpos($sections[$t],'isOwnGoal') !== FALSE) {$typea9='OWNGOAL';$tally++;}
					$concatenate2 = $typea1.$typea2.$typea3.$typea4.$typea5.$typea6.$typea7.$typea8.$typea9;
					if ($tally == 0){$concatenate3= "REGULAR";}
					$concatenateb = trim($concatenate2.$concatenate3);
					echo " ".$concatenateb;
//calc exG
					$equation = $concatenatea.$concatenateb;
					if($equation == 'HEADER')		{$exg = $HEADERexg*exp(-0.27* $adjdistance);}
					if($equation == 'CROSS')		{$exg = $CROSSexg*exp(-0.19* $adjdistance);}
					if($equation == 'REGULAR')		{$exg = $REGULARexg*exp(-0.13* $adjdistance);}
					if($equation == 'CROSSHEADER')		{$exg = $CROSSHEADERexg*exp(-0.21* $adjdistance);}
					if($equation == 'PENALTY')		{$exg = $PENALTYexg;}
					if($equation == 'OWNGOAL')		{$exg = $OWNGOALexg*exp(-0.13* $adjdistance); $og =1;}
					if($equation == 'DRIBBLE')		{$exg = $DRIBBLEexg*exp(-0.10* $adjdistance);}
					echo " exG = ".round($exg,2);
					if($og==1){
						if($team == trim($names[1])){$totexghome = $totexghome + $exg;}
						if($team == trim($names[0])){$totexgaway = $totexgaway + $exg;}
					}
					if($og !==1){
						if($team == trim($names[0])){$totexghome = $totexghome + $exg;}
						if($team == trim($names[1])){$totexgaway = $totexgaway + $exg;}
					}
					echo "</br>"; $og='';
				}


				$tally=0;



//header or not
//cross or not
//dribble or not
//regular 
/// HEADER & CROSS
///HEADER, NO CROSS
///NO HEADER, CROSS
/// NO HEADER, NO CROSS
///DRIBBLE

// RegularPlay (not set piece) (ignore)    
//Assisted (ignore)
//Fast break DRIBBLE 
//Set piece CROSS
//From corner CROSS
//Free kick CROSS
//Corner situation CROSS
//Direct free CROSS
//Scramble (ignore) 
//Throw-in set piece  (ignore) 
//Intentional assist (ignore) 
//Head HEADER
//Left footed (ignore) 
//Right footed (ignore) 
//Other body part (ignore) 

				$equation = '';
				$concatenatea = '';$concatenateb = '';$concatenate1 = '';$concatenate2 = '';
				$concatenate3 = '';$concatenate4 = '';
				$n++;
				$type1='';$type2='';$type3='';$type4='';$type5='';$type6='';$type7='';$type8='';$type9='';
				$typea1='';$typea2='';$typea3='';$typea4='';$typea5='';$typea6='';$typea7='';$typea8='';$typea9='';
			}
			$n=0;$t++; }

		echo "******************************************************************************************************";
		echo "</br>";
		echo "Score ".$score;
		echo "</br>";
		echo "ExG: ".round($totexghome,1)." : ".round($totexgaway,1);
		$totexghome=''; $totexgaway=''; $exg='';

	}
	else {
		?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<label>WS match id. 2014/15 starts at 829513:
					<input type="number" name="address" id="address">
				</label>
			<br/>
				<input type="submit" value="ExG" name="submit">
			<br />
		</form>
		<br />
	<?php } ?>
</body>
</html> 
