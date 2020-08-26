<?php

	class fourfourtwo {

		/**
		 * @param $title
		 */
		function print_html($title) {

		$dollar = '
			<!DOCTYPE html>
			<html>
			  <head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width; initial-scale=1.0;">
			<meta name="apple-mobile-web-app-capable" content="yes">
			<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
			<meta name="HandheldFriendly" content="True">
			<meta name="MobileOptimized" content="320"/>
			<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
			<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.2/cerulean/bootstrap.min.css">
			<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
			<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css">
			</head>
			<body>
			<header class="container"><h3>'. $title .'</h3></header>';

			print $dollar;
		}

		/**
		 *
		 */
		function print_footer() {

			$dollar ='<div class="clearfix"><p>&nbsp;</p></div></body></html>';
			print $dollar;

		}

		/**
		 *
		 */
		function set_headers() {

				header("X-Frame-Options: SAMEORIGIN");
				header("X-XSS-Protection: 0");
				header("X-Content-Type-Options: nosniff");
				header("strict-transport-security: max-age=31536000; includeSubdomains");
				header("X-Powered-By: Celery");
				header("X-Turbo-Charged-By: Celery");
				header("x-cf-powered-by: Celery");
				header("Server: Celery");
				error_reporting(E_ERROR);
				ini_set('user_agent', 'Mozilla/5.0');
				ini_set('xdebug.var_display_max_depth', 100000);
				ini_set('xdebug.var_display_max_children', 512000);
				ini_set('xdebug.var_display_max_data', 10240000);
		}

		/**
		 * @param $url
		 * @return mixed
		 */
		function file_get_contents_curl($url) {
			$ch = curl_init();
			// you might need proxy settings added here if it times out.
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
			curl_setopt($ch, CURLOPT_URL, $url);

			$raw = curl_exec($ch);
			curl_close($ch);

			return $raw;
		}

		/**
		 * @param $title
		 * @param $var
		 */
		function _debug($title,$var) {

			print '<pre>';
			print '<h4>'.$title.'</h4>';
			print_r($var);
			print '</pre>';

		}

		/**
		 * @param $table
		 * @return mixed
		 */
		function _remove_one($table) {

			$table = str_replace('<svg',"\n",$table);
			$table = str_replace('<img',"\n",$table);
			$table = str_replace('<defs>'," ",$table);
			$table = str_replace('</def>'," ",$table);
			$table = str_replace('/><line class="pitch-object',"\n",$table);


			return $table;
		}

		/**
		 * @param $url
		 * @param $league
		 * @param $year
		 * @param $match
		 * @param $team
		 * @param $other
		 * @param $var
		 */
		function _shots($url,$league,$year,$match,$team,$other,$var) {


			$raw = $this->file_get_contents_curl($url) or die('could not select');

			$start=strpos($raw,'<h2>Shots - All attempts</h2>');
			$end = strpos($raw,'</svg>');

			$table = substr($raw,$start,$end-$start);

			$table = $this->_remove_one($table);

			$table = str_replace('marker-end="url(#smallblue)"','',$table);
			$table = str_replace('style="stroke:blue;stroke-width:3"','',$table);
			$table = str_replace('style="stroke:red;stroke-width:3"','',$table);
			$table = str_replace('marker-end="url(#smallred)"','',$table);
			$table = str_replace('marker-end="url(#smalldeepskyblue)"','',$table);
			$table = str_replace('style="stroke:deepskyblue;stroke-width:3"','',$table);
			$table = str_replace('<image x="0" y="0" width="760" height="529" xlink:href="/sites/fourfourtwo.com/modules/custom/statzone/files/statszone_football_pitch.png"','',$table);
			$table = str_replace('<','',$table);
			$table = str_replace('>','',$table);
			$table = str_replace('style="stroke:yellow;stroke-width:3"','',$table);

			$start =  'xlink:href="/sites/fourfourtwo.com/modules/custom/statzone/files/statszone_football_pitch_shot.png"';

			$table = explode($start,$table);
			// split the code from the start point above and keep the second part 1, of 0/1
			$table = $table['1'];

			// remove some more stuff so we are left with basic info
			$table = str_replace('marker-start="url(#'," ",$table);
			$table = str_replace('marker-end="url(#bigyellow)"'," ",$table);
			$table = str_replace('marker-end="url(#bigred)"'," ",$table);
			$table = str_replace('marker-end="url(#bigblue)"'," ",$table);
			$table = str_replace('marker-end="url(#bigdarkgrey)"'," ",$table);
			$table = str_replace(')"'," ",$table);
			$table = str_replace('style="stroke:darkgrey;stroke-width:3"',"",$table);

			$table = str_replace(' x',',x',$table);
			$table = str_replace(' y',',y',$table);
			$table = str_replace('" ','"',$table);
			$table = str_replace('big',',',$table);
			$table = str_replace('end','',$table);
			$table = str_replace('"','',$table);
			$table = str_replace('-',', ',$table);
			$table = str_replace('timer','',$table);

			$table = str_replace('x1=','',$table);
			$table = str_replace('x2=','',$table);
			$table = str_replace('y1=','',$table);
			$table = str_replace('y2=','',$table);

			// replace colours with shot type
			$table = str_replace('yellow','Goal',$table);
			$table = str_replace('blue','Save',$table);
			$table = str_replace('red','Wide',$table);
			$table = str_replace('darkgrey','Block',$table);

			// tidy up the output a little.
			$table = str_replace('   /',"\n",$table);
			$table = str_replace(' ,',',',$table);
			$table = str_replace('  ,',',',$table);
			$table = str_replace(', ',',',$table);
			$table = str_replace("/","",$table);
			$table = rtrim($table);

			$prefix = $league .",".$year.",".$match.",".$team .",". $other .",". $var;
			$newlines=array("\t","\n","\r","\x20\x20","\0","\x0B");
			$content=str_replace($newlines, '<br/>'. $prefix, html_entity_decode($table));

			// print what is left to screen.
			print $content;

		}

		/**
		 * @param $url
		 * @param $league
		 * @param $year
		 * @param $match
		 * @param $team
		 * @param $other
		 * @param $var
		 */
		function _assists($url,$league,$year,$match,$team,$other,$var) {

			$raw= $this->file_get_contents_curl($url) or die('could not select');

			$start=strpos($raw,'<image x="0" y="0" width="740" height="529" xlink:href="/sites/fourfourtwo.com/modules/custom/statzone/files/statszone_football_pitch.png" />');
			$end = strpos($raw,'</svg>');

			$table = substr($raw,$start,$end-$start);

			$table = $this->_remove_one($table);

			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('style="stroke:deepskyblue;stroke-width:3"','',$table);

			$table = str_replace('<','',$table);
			$table = str_replace('>','',$table);
			$table = str_replace(')"'," ",$table);
			$table = str_replace('style="stroke:yellow;stroke-width:3"','',$table);

			// remove some more stuff so we are left with basic info
			$table = str_replace('marker-start="url(#'," ",$table);


			$table = str_replace('success','',$table);

			$table = str_replace(' x',',x',$table);
			$table = str_replace(' y',',y',$table);
			$table = str_replace('" ','"',$table);
			$table = str_replace('big',',',$table);
			$table = str_replace('marker','',$table);
			$table = str_replace('end','',$table);
			$table = str_replace('"','',$table);
			$table = str_replace('-',', ',$table);
			$table = str_replace('timer','',$table);
			$table = str_replace('url','',$table);


			$table = str_replace('(#smalldeepskyblue','Chance',$table);
			$table = str_replace('(#smallyellow','Assist',$table);


			$table = str_replace('x1=','',$table);
			$table = str_replace('x2=','',$table);
			$table = str_replace('y1=','',$table);
			$table = str_replace('y2=','',$table);

			// tidy up the output a little.
			$table = str_replace('   /',"\n",$table);
			$table = str_replace(' ,',',',$table);
			$table = str_replace('  ,',',',$table);
			$table = str_replace(', ',',',$table);
			$table = str_replace("/","",$table);
			$table = rtrim($table);

			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('=','',$table);

			$prefix = $league .",".$year.",".$match.",".$team .",". $other .",". $var;
			$newlines=array("\t","\n","\r","\x20\x20","\0","\x0B");
			$content=str_replace($newlines, '<br/>'. $prefix, html_entity_decode($table));

			// print what is left to screen.
			print $content;

		}

		/**
		 * @param      $url
		 * @param      $league
		 * @param      $year
		 * @param      $match
		 * @param      $var
		 * @param      $team
		 * @param null $other
		 */
		function _crosses($url, $league, $year, $match, $var, $team, $other = null) {

			$raw= $this->file_get_contents_curl($url) or die('could not select');

			$start=strpos($raw,'<image x="0" y="0" width="740" height="529" xlink:href="/sites/fourfourtwo.com/modules/custom/statzone/files/statszone_football_pitch.png" />');
			$end = strpos($raw,'</svg>');

			$table = substr($raw,$start,$end-$start);

			$table = $this->_remove_one($table);

			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('style="stroke:deepskyblue;stroke-width:3"','',$table);

			$table = str_replace('<','',$table);
			$table = str_replace('>','',$table);
			$table = str_replace(')"'," ",$table);
			$table = str_replace('style="stroke:yellow;stroke-width:3"','',$table);

			// remove some more stuff so we are left with basic info
			$table = str_replace('marker-start="url(#'," ",$table);


			$table = str_replace('success','',$table);

			$table = str_replace(' x',',x',$table);
			$table = str_replace(' y',',y',$table);
			$table = str_replace('" ','"',$table);
			$table = str_replace('big',',',$table);
			$table = str_replace('marker','',$table);
			$table = str_replace('end','',$table);
			$table = str_replace('"','',$table);
			$table = str_replace('-',', ',$table);
			$table = str_replace('timer','',$table);
			$table = str_replace('url','',$table);

			// decipher the html into event types
			$table = str_replace('(#smalldeepskyblue','Chance',$table);
			$table = str_replace('(#smallyellow','Assist',$table);
			$table = str_replace('(#smallblue','Completed',$table);
			$table = str_replace('(#smallred','',$table);
			$table = str_replace('(#smalldarkgrey','Blocked Cross',$table);

			// carry on removing junk
			$table = str_replace('x1=','',$table);
			$table = str_replace('x2=','',$table);
			$table = str_replace('y1=','',$table);
			$table = str_replace('y2=','',$table);

			$table = str_replace('stroke:blue;stroke','',$table);
			$table = str_replace('stroke:red;stroke','',$table);
			$table = str_replace('stroke:darkgrey;stroke','',$table);

			$table = str_replace('style','',$table);
			$table = str_replace('width:3','',$table);

			// tidy up the output a little.
			$table = str_replace('   /',"\n",$table);
			$table = str_replace(' ,',',',$table);
			$table = str_replace('  ,',',',$table);
			$table = str_replace(', ',',',$table);
			$table = str_replace("/","",$table);
			$table = rtrim($table);

			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('=','',$table);

			// tidy up a bunch of commas
			$table = str_replace(",,",",",$table);

			// prefix to explain the order on the output
			$prefix = $league .",".$year.",".$match.",".$team .",". $other .",". $var;

			// remove all newline characters with a proper linebreak.
			$newlines=array("\t","\n","\r","\x20\x20","\0","\x0B");
			$content=str_replace($newlines, '<br/>'. $prefix, html_entity_decode($table));

			// tidy up a bunch of commas (again)
			$content = str_replace(",,",",",$content);

			// print what is left to screen.
			print $content;

		}

		/**
		 * @param      $url
		 * @param      $league
		 * @param      $year
		 * @param      $match
		 * @param      $var
		 * @param      $team
		 * @param null $other
		 */
		function _passes($url, $league, $year, $match, $var, $team, $other = null) {

			$raw= $this->file_get_contents_curl($url) or die('could not select');

			$start=strpos($raw,'<image x="0" y="0" width="740" height="529" xlink:href="/sites/fourfourtwo.com/modules/custom/statzone/files/statszone_football_pitch.png" />');
			$end = strpos($raw,'</svg>');

			$table = substr($raw,$start,$end-$start);

			$table = $this->_remove_one($table);

			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('style="stroke:deepskyblue;stroke-width:3"','',$table);

			$table = str_replace('<','',$table);
			$table = str_replace('>','',$table);
			$table = str_replace(')"'," ",$table);
			$table = str_replace('style="stroke:yellow;stroke-width:3"','',$table);

			// remove some more stuff so we are left with basic info
			$table = str_replace('marker-start="url(#'," ",$table);


			$table = str_replace('success','',$table);

			$table = str_replace(' x',',x',$table);
			$table = str_replace(' y',',y',$table);
			$table = str_replace('" ','"',$table);
			$table = str_replace('big',',',$table);
			$table = str_replace('marker','',$table);
			$table = str_replace('end','',$table);
			$table = str_replace('"','',$table);
			$table = str_replace('-',', ',$table);
			$table = str_replace('timer','',$table);
			$table = str_replace('url','',$table);


			$table = str_replace('(#smalldeepskyblue','Chance',$table);
			$table = str_replace('(#smallyellow','Assist',$table);
			$table = str_replace('(#smallblue','Completed',$table);
			$table = str_replace('(#smallred','',$table);

			$table = str_replace('x1=','',$table);
			$table = str_replace('x2=','',$table);
			$table = str_replace('y1=','',$table);
			$table = str_replace('y2=','',$table);

			$table = str_replace('stroke:blue;stroke','',$table);
			$table = str_replace('stroke:red;stroke','',$table);
			$table = str_replace('style','',$table);
			$table = str_replace('width:3','',$table);



			// tidy up the output a little.
			$table = str_replace('   /',"\n",$table);
			$table = str_replace(' ,',',',$table);
			$table = str_replace('  ,',',',$table);
			$table = str_replace(', ',',',$table);
			$table = str_replace("/","",$table);
			$table = rtrim($table);

			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('=','',$table);

			$table = str_replace(",,",",",$table);

			$prefix = $league .",".$year.",".$match.",".$team .",". $other .",". $var;
			$newlines=array("\t","\n","\r","\x20\x20","\0","\x0B");
			$content=str_replace($newlines, '<br/>'. $prefix, html_entity_decode($table));


			$content = str_replace(",,",",",$content);
			// print what is left to screen.
			print $content;

		}

		/**
		 * @param $url
		 * @param $league
		 * @param $year
		 * @param $match
		 * @param $var
		 * @return string
		 */
		function _players($url,$league,$year,$match,$var) {

			$raw= $this->file_get_contents_curl($url) or die('could not select');

			$start=strpos($raw,'<h1>Click on the player to view their stats</h1>');
			$end = strpos($raw,'<!-- /#content -->');

			$table = substr($raw,$start,$end-$start);


			$table = str_replace("<h1>Click on the player to view their stats</h1>","",$table);
			$table = str_replace('<div class="lineup ','',$table);
			$table = str_replace('<h2>Substitutions</h2>','',$table);
			$table = str_replace('<span><a href="/statszone/',',',$table);
			$table = str_replace('</a>','',$table);
			$table = str_replace('<span>','',$table);
			$table = str_replace('</span>','',$table);
			$table = str_replace('</div>','',$table);
			$table = str_replace("player-stats","",$table);
			$table = str_replace("matches","",$table);
			$table = str_replace('OVERALL_02#tabs-wrapper-anchor"',"",$table);

			$table = str_replace('left: 416.4px;',"",$table);
			$table = str_replace('left: 635.36px;',"",$table);
			$table = str_replace('left: 513px;',"",$table);
			$table = str_replace('left: 593.5px;',"",$table);
			$table = str_replace('left: 113.5px;',"",$table);
			$table = str_replace('left: 194px;',"",$table);
			$table = str_replace('left: 258.4px;',"",$table);
			$table = str_replace('left: 71.64px;',"",$table);
			$table = str_replace('left: 306.7px;',"",$table);
			$table = str_replace('left: 194px;',"",$table);

			$table = str_replace('top: 246.5px;',"",$table);
			$table = str_replace('top: 118.4px;',"",$table);
			$table = str_replace('top: 126.94px;',"",$table);
			$table = str_replace('top: 169.64px;',"",$table);
			$table = str_replace('top: 289.2px;',"",$table);
			$table = str_replace('top: 203.8px;',"",$table);
			$table = str_replace('top: 374.6px; ',"",$table);
			$table = str_replace('top: 323.36px;',"",$table);
			$table = str_replace('top: 366.06px;',"",$table);
			$table = str_replace('top: 249.5px;',"",$table);
			$table = str_replace('top: 121.4px;',"",$table);

			$table = str_replace('top: 377.6px;',"",$table);
			$table = str_replace('top: 292.2px;',"",$table);
			$table = str_replace('top: 206.8px;',"",$table);
			$table = str_replace('top: 176.91px;',"",$table);

			$table = str_replace('top: 369.06px;',"",$table);
			$table = str_replace('top: 322.09px;',"",$table);
			$table = str_replace('top: 228.15px;',"",$table);
			$table = str_replace('top: 129.94px;',"",$table);
			$table = str_replace('top: 279.39px;',"",$table);

			$table = str_replace('style',"",$table);


			$table = str_replace('<',"",$table);
			$table = str_replace('>',"",$table);
			$table = str_replace('"',"",$table);
			$table = str_replace('  ',"",$table);
			$table = str_replace('a href',"",$table);
			$table = str_replace('ahref,',"",$table);
			$table = str_replace('statszone',"",$table);
			$table = str_replace('div',"",$table);
			$table = str_replace('class',"",$table);
			$table = str_replace('li',"",$table);
			$table = str_replace('ul',"",$table);
			$table = str_replace('id',"",$table);
			$table = trim($table);

			// tidy up the output a little.
			$table = str_replace('   /',"\n",$table);
			$table = str_replace(' ,',',',$table);
			$table = str_replace('  ,',',',$table);
			$table = str_replace(', ',',',$table);
			$table = str_replace("/",",",$table);
			$table = str_replace("-",",",$table);
			$table = str_replace("=",",",$table);

			$table = str_replace(" ","",$table);
			$table = str_replace("<br>","",$table);



			$table = rtrim($table);

			$table = str_replace('imgtypeoffoaf:Imagesrchttp:,images.cdn.fourfourtwo.com,sites,fourfourtwo.com,modules,custom,statzone,files,statszone_football_pitch.pngwidth740height529alt,',"",$table);


			$table = str_replace(',,',',',$table);

			$prefix = $league .",".$year.",".$match.",". $team .",". $other .",". $var;

			$prefix = 1;

			$newlines=array("\t","\n","\r","\x20\x20","\0","\x0B");
			$table=str_replace($newlines, '', html_entity_decode($table));

			$table = str_replace('home',"<br/>",$table);
			$table = str_replace('away',"<br/>",$table);
			$table = str_replace('first',"<br/>",$table);
			$table = str_replace('last',"<br/>",$table);

			$table = str_replace(",substitutes","<br/>|<br/>",$table);
			$table = str_replace(",,item,st,","",$table);
			$table = str_replace(",clearfix,item,st,","",$table);
			$table = str_replace("Homesubs,","",$table);
			$table = str_replace("Awaysubs,","",$table);
			$table = str_replace(",item,st,","",$table);
			$table = str_replace(",item,st,","",$table);
			$table = str_replace("subs,","",$table);
			$table = str_replace(",,",",",$table);

			$table = str_replace("<br/><br/>","<br/>",$table);
			$table = str_replace("<br/><br/>","<br/>",$table);


			$table = str_replace(",,",",",$table);
			// print what is left to screen.

			$table = str_replace('imgtypeof,foaf:Imagesrc,http:,images.cdn.fourfourtwo.com,sites,fourfourtwo.com,modes,custom,statzone,files,_football_pitch.pngwth,740height,529alt,',"",$table);

			// print $table;

			$parts = explode('|',$table);

			$starters = $parts['0'];
			$subs = $parts['1'];

			$starters = explode('<br/>',$starters);
			$subs = explode('<br/>',$subs);
			$output  ='';
			$output1 ='';
			$output2 ='';

			foreach ($starters as $row) :

				$data1 = explode(',',$row);

				if(isset($data1['5']) && $data1['5'] !='') :
					$output1 .= $data1['4'].','.$data1['5'].','.$data1['6'].'<br/>';
				endif;

			endforeach;

			foreach ($subs as $row) :

				$data2 = explode(',',$row);

				if(isset($data2['4']) && $data2['4'] !='') :
					$output2 .= $data2['3'].','.$data2['4'].','.$data2['5'].'<br/>';
				endif;


			endforeach;


			print '<hr/>';

			$dollar =  $output1;
			$dollar .= $output2;

			$dollar = explode ($dollar,'<br>');
			$dollar = array_unique($dollar);

			foreach ($dollar as $row) :
				$data = explode(',',$row);
				if(isset($data['0']) && $data['0'] !='') :
					$output .= $data['0'].','.$data['1'].','.$data['2'].'<br/>';
				endif;
			endforeach;

			return $output;


		}

		/**
		 * @param $url
		 * @param $league
		 * @param $year
		 * @param $match
		 * @param $team
		 * @param $other
		 * @param $var
		 */
		function _receptions($url,$league,$year,$match,$team,$other,$var) {

			$raw= $this->file_get_contents_curl($url) or die('could not select');

			$start=strpos($raw,'<image x="0" y="0" width="740" height="529" xlink:href="/sites/fourfourtwo.com/modules/custom/statzone/files/statszone_football_pitch.png" />');
			$end = strpos($raw,'</svg>');

			$table = substr($raw,$start,$end-$start);

			$table = $this->_remove_one($table);

			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('style="stroke:deepskyblue;stroke-width:3"','',$table);

			$table = str_replace('<','',$table);
			$table = str_replace('>','',$table);
			$table = str_replace(')"'," ",$table);
			$table = str_replace('style="stroke:yellow;stroke-width:3"','',$table);

			// remove some more stuff so we are left with basic info
			$table = str_replace('marker-start="url(#'," ",$table);


			$table = str_replace('success','',$table);

			$table = str_replace(' x',',x',$table);
			$table = str_replace(' y',',y',$table);
			$table = str_replace('" ','"',$table);
			$table = str_replace('big',',',$table);
			$table = str_replace('marker','',$table);
			$table = str_replace('end','',$table);
			$table = str_replace('"','',$table);
			$table = str_replace('-',', ',$table);
			$table = str_replace('timer','',$table);
			$table = str_replace('url','',$table);


			$table = str_replace('(#smalldeepskyblue','Chance',$table);
			$table = str_replace('(#smallyellow','Assist',$table);
			$table = str_replace('(#smallblue','Pass',$table);

			$table = str_replace('x1=','',$table);
			$table = str_replace('x2=','',$table);
			$table = str_replace('y1=','',$table);
			$table = str_replace('y2=','',$table);

			$table = str_replace('   /',"\n",$table);
			$table = str_replace(' ,',',',$table);
			$table = str_replace('  ,',',',$table);
			$table = str_replace(', ',',',$table);
			$table = str_replace("/","",$table);
			$table = rtrim($table);

			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('image,x=0,y=0width=740height=529,xlink:href=sitesfourfourtwo.commodulescustomstatzonefilesstatszone_football_pitch.png','',$table);
			$table = str_replace('stroke:blue;stroke,width:3','',$table);
			$table = str_replace('style','',$table);
			$table = str_replace('=','',$table);

			$prefix = $league .",".$year.",".$match.",".$team .",". $other .",". $var;
			$newlines=array("\t","\n","\r","\x20\x20","\0","\x0B");
			$content=str_replace($newlines, '<br/>'. $prefix, html_entity_decode($table));

			// print what is left to screen.
			print $content;

		}

		/**
		 * @return string
		 */
		function _getForm() {

			$string='<form action="'. $_SERVER['PHP_SELF'].'" method="POST">

				<div class="form-group">
					<label for="group">Scrape Type:</label>
					<select name="group" id="group">
						<option value="team">Team</option>
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
						<option value="3">Euro 2016</option>

					</select>
				</div>

				<div class="form-group">
					<label for="year">year Ref:</label>
					<select name="year" id="year">
					    <option value="2016">2016</option>
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
					<label for="team1">Home Team ID:</label>
					<input name="team1"   type="text" id="team1">
				</div>

				<div class="form-group">
					<label for="team2">Away Team ID:</label>
					<input name="team2"   type="text" id="team2">
				</div>

				<p><b>OR:</b></p>

				<div class="form-group">
					<label for="plyr">Player ID:</label>
					<input name="plyr"   type="text" id="plyr">
				</div>


				<div class="form-group">
					<input type="submit" value="submit" class="btn btn-primary">
				</div>

			</form>';

			return $string;
		}

		public function checkVars() {

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


		}
	}