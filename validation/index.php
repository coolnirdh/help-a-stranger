<?php
// include configurations
require __DIR__.'/../config.php';

// include dependencies
require_once __DIR__.'/../libraries/google-api-php-client-2.4.0/vendor/autoload.php';

if(!isset($_COOKIE['SkipValue'])){
	setcookie('SkipValue',0);
}


function get_entries($union_name){
	$client = new Google_Client();
	$client->setApplicationName($GLOBALS['google_app_name']);
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(__DIR__.'/../assets/'.$GLOBALS['google_sheets_json_filename']);
	$client->setDeveloperKey($GLOBALS['google_sheets_api_auth_key']);

	$service = new Google_Service_Sheets($client);

	$spreadsheetID = $GLOBALS['google_spreadsheet_ID'];

	$range = 'Union Names!A:B';

	try{
		$response = $service->spreadsheets_values->get($spreadsheetID, $range);

		$values = $response->getValues();
	}catch(Exception $e){}
	
	$found = FALSE;

	if(empty($values)){
			return NULL;
	}else{
		foreach($values as $row){
			if($row[1] == $union_name){
				//Union Match Found. Break Out of Loop and Continue.
				$union_name_string = $row[0];
				$found = TRUE;
				break;
			}
		}
	}
	
	if(!$found){
		$GLOBALS['union_not_recognised'] = TRUE;
		return NULL;
	}
	
	$service = new Google_Service_Sheets($client);

	$range = 'Unique Values!A:H';
	
	$response = $service->spreadsheets_values->get($spreadsheetID, $range);

	$values = $response->getValues();
	
	$final_values = NULL;
	
	if(empty($values)){
			return NULL;
	}else{
		$i = 0;
		foreach($values as $row){
			if($row[3] == $union_name_string){
				//Member of Chosen Union. Must be Appended to Return Value.
				$final_values[$i] = $row;
				$i++;
			}
		}
	}
	
	return $final_values;

}

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $GLOBALS['fb_og_title']; ?> | VALIDATION CHAMBER</title>
<link rel="shortcut icon" href="../images/has-favicon.png" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/validationchamber_stylesheet.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../libraries/js-cookie-1.5.1/src/js.cookie.js"></script>


</head>

<body>
	<div class="approved-overlay-div">
		<div class="overlay-sub-div">
			<h1 style="font-size: 50px;"><?php echo strtoupper($row[1]); ?> APPROVED.</h1>
			<div style="font-size: 25px;">Loading Next Entry...</div>
		</div>
	</div>
	<div class="skip-overlay-div">
		<div class="overlay-sub-div">
			<div style="font-size: 25px;">Loading Next Entry...</div>
		</div>
	</div>
	<div class="rejected-overlay-div">
		<div class="overlay-sub-div">
			<h1 style="font-size: 50px;">SURE?</h1>
			<div class="reject-sure" style="padding: 15px; border: 5px solid #fff; color: #fff; cursor: pointer;"><h1>YES</h1></div>
			<div class="reject-not-sure" style="padding: 15px; border: 5px solid #fff; color: #fff; cursor: pointer; margin-top: 25px;"><h1>BACK</h1></div>
		</div>
	</div>
	<div class="rejected-final-overlay-div">
		<div class="overlay-sub-div">
			<h1 style="font-size: 50px;"><?php echo strtoupper($row[1]); ?> REJECTED.</h1>
			<div style="font-size: 25px;">Loading Next Entry...</div>
		</div>
	</div>
	<div class="content-div">
	<?php
		
		//Main Code Begins

		$union_not_recognised = FALSE;
		$GLOBALS['union_not_found'] = FALSE;
		if(isset($_GET['Union'])){
			$union = $_GET['Union'];
		}else{
			$union = NULL;
		}

		if(!$union){
			echo '<img src="../images/crying-trex.png" style="width: 250px; margin-top: 25px;"/><h1>ERROR: UNION NOT RECOGNISED.</h1>';
			exit();
		}
		$values = get_entries($union);

		if(!$values){
		echo '<img src="../images/crying-trex.png" style="width: 250px; margin-top: 25px;"/><h1>NO ENTRIES FOUND.</h1>Sometimes, this could be if you have spelt the Union Name wrong in the URL. Please crosscheck your spelling.';
		exit();
		}

		$count = sizeof($values);

		$rcount = 0;
		$nothing = 0;

		if(isset($_COOKIE['SkipValue'])){
			$skip = $_COOKIE['SkipValue'];
		}else{
			$skip = 0;
		}
		$i = 0;

		while(1){
			if($rcount == $count){

				$nothing = TRUE;
				break;

			}

			$row = $values[$rcount];

			if(isset($row[0]) && !isset($row[7])){

				if($skip > 0 && $i < $skip){
					$i ++;
					$rcount ++;
					if($rcount == $count){
						$rcount = 0;
						unset($_COOKIE['SkipValue']);
					}
					continue;
				}

				break;

			}else{

				$rcount++;

				continue;

			}
		}

		$getvariables = 'update.php?mobile='.$row[0].'&verify=';
		
		echo '<h1>'.$row[3].'</h1>';

		if(!$nothing){
			echo $row[1].'<br/>';
			echo 'Age: '.$row[2].' Yrs<br/>Worked for '.$row[5].' Yrs<br/>';
			echo '<a class="telephone" href="tel: '.$row[0].'">&#9990;&nbsp;&nbsp;'.$row[0].'</a><br/>';

			if($row[4] == '' || $row[4] == '0000'){
				$row[4] = 'N/A';
			}

			echo 'Union No: '.$row[4].'<br/><br/>';

			echo '<div class="approve">APPROVE</div>';
			echo '<div class="show-another">DECIDE LATER</div>';
			echo '<div class="disapprove">REJECT</div>';
		}else{
			echo '<div style="padding: 50px; border-radius: 100px; color: #fff; background-color: #72c56c; font-size: 75px; width: 100px; margin: 0 auto; margin-top: 75px; margin-bottom: 40px;">&#x2714;</div>';
			echo 'WELL DONE!<br/>ALL ENTRIES VERIFIED.<br/><br/>';
			echo '<span style="font-size: 16px;">Please check back after some time for new entries.</span>';
		}
	?>
	</div>
	<div style="font-size: 13px;"><?php echo $GLOBALS['footer_links']; ?></div>
</body>
</html>
<script>
	$(window).on('beforeunload', function() {
		$(window).scrollTop(0);
	});
	$(document).ready(function(){
		
		//$.removeCookie('SkipValue');
		
	  $(".approve").click(function(){
		$(".approved-overlay-div").fadeIn();
		$.get("<?php echo $getvariables.'Good'; ?>", setTimeout(location.reload.bind(location), 3000));
	  });
	  
	  $(".disapprove").click(function(){
		$(".rejected-overlay-div").fadeIn();
	  });
		
	  $(".reject-not-sure").click(function(){
		$(".rejected-overlay-div").fadeOut();
	  });
		
	  $(".reject-sure").click(function(){
		$(".rejected-overlay-div").hide();
		$(".rejected-final-overlay-div").show();
		  $.get("<?php echo $getvariables.'Bad'; ?>", setTimeout(location.reload.bind(location), 3000));
	  });
		
	  $(".show-another").click(function(){
		
		$(".skip-overlay-div").fadeIn();
		
		if(Cookies.get('SkipValue')){
			var skipValue = parseInt(Cookies.get('SkipValue')) + 1;
			Cookies.set('SkipValue', skipValue);
		}else{
			Cookies.set('SkipValue',1);
		}
		setTimeout(location.reload.bind(location));
		  
	  });

	});
</script>