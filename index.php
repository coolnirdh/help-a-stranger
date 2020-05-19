<?php
// include configurations
require 'config.php';

// include dependencies
require_once 'libraries/google-api-php-client-2.4.0/vendor/autoload.php';
require_once 'functions.php';

if($GLOBALS['organisation_name'] == '' || $GLOBALS['google_app_name'] == '' || $GLOBALS['google_sheets_json_filename'] == '' || $GLOBALS['google_sheets_api_auth_key'] == '' || $GLOBALS['google_spreadsheet_ID'] == ''){
	echo '<br/><div style="text-align: center; font-family: Arial;"><img src="images/crying-trex.png" style="width: 250px;"/><h1>SETUP INCOMPLETE.</h1><p>Check everything in config.php.<br/>Meanwhile, it\'s best the code is not run. We\'re scooting!</p></div>';
	exit();
}

$values = get_entries();

$count = 0;

if(empty($values)){
	echo '<br/><div style="text-align: center; font-family: Arial;"><img src="images/crying-trex.png" style="width: 250px;"/><h1>SOMETHING AIN\'T RIGHT.</h1><p>Scooting!</p></div>';
	exit();
}
else{
	foreach($values as $row){
		if($row[0] != NULL){
			$count++;
		}
	}

}

if($GLOBALS['seq_entries'] == 1){
	//Show Sequentially
	$last_shown = get_lastShown();

	$last_shown = $last_shown - 2;

	if(($last_shown+1) == $count){
		$last_shown = -1;
	}

	while(1){
		$row = $values[$last_shown+1];
		if(isset($row[7]) && $row[7] == 'Good'){
			break;
		}
		else{
			$last_shown++;
			if(($last_shown+1) == $count){
				$last_shown = -1;
			}		
		}
	}

	update_lastShown($last_shown+2);

}else{
	//Select a Random Entry to Show
	while(1){
		$rcount = mt_rand(1, $count-1);
		$row = $values[$rcount];
		if(isset($row[7]) && $row[7] == 'Good')
			break;
		else
			continue;
	}
}

$mobile = $row[0];
$name = $row[1];
$upi = $row[6];
$form_url = 'report-bad-upi.php?mobile='.$mobile.'&name='.$name.'&upi='.$upi;
$copy_form_url = 'update.php?mobile='.$mobile;

update_displayCount($mobile);

?>
<!doctype html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $GLOBALS['fb_og_title']; ?></title>

<meta property="og:title" content="<?php echo $GLOBALS['fb_og_title']; ?>" />
<meta property="og:type" content="website" />
<meta property="og:image" content="<?php echo __DIR__.'/images/'.$GLOBALS['fb_og_img']; ?>" />
<meta property="og:description" content="<?php echo $GLOBALS['fb_og_desc']; ?>" />
	
<link rel="shortcut icon" href="images/has-favicon.png" />

<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	
<!-- BOOTSTRAP CSS -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="css/stylesheet.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
// Copies a string to the clipboard. Must be called from within an
// event handler such as click. May return false if it failed, but
// this is not always possible. Browser support for Chrome 43+,
// Firefox 42+, Safari 10+, Edge and Internet Explorer 10+.
// Internet Explorer: The clipboard feature may be disabled by
// an administrator. By default a prompt is shown the first
// time the clipboard is used (per session).
function copyToClipboard(text) {
    if (window.clipboardData && window.clipboardData.setData) {
        // Internet Explorer-specific code path to prevent textarea being shown while dialog is visible.
        return clipboardData.setData("Text", text);

    }
    else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
        var textarea = document.createElement("textarea");
        textarea.textContent = text;
        textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in Microsoft Edge.
        document.body.appendChild(textarea);
        textarea.select();
        try {
            return document.execCommand("copy");  // Security exception may be thrown by some browsers.
        }
        catch (ex) {
            console.warn("Copy to clipboard failed.", ex);
            return false;
        }
        finally {
            document.body.removeChild(textarea);
        }
    }
}
</script>

<script>
	$(window).on('beforeunload', function() {
		$(window).scrollTop(0);
	});
	$(document).ready(function(){
		
	  $(".Tip-Div").delay(3000).slideDown();
		
	  $(".Tx-Fail-Div").click(function(){
		$('#AccountInfo').addClass("badAccInfo");
		$('.reload-overlay-div').fadeIn();
		$(".Bad-Upi-Report").fadeIn().delay(5000).fadeOut();
		$.get("<?php echo $form_url; ?>", setTimeout(location.reload.bind(location), 3000));
	  });
	  
	  $(".upi-div:last").click(function(){
		$.get("<?php echo $copy_form_url; ?>"); 
		$(this).find('.copy-btn').html('COPIED').addClass("copied");
	  });
	  
	  $(".upi-div").click(function(){
		$(this).find('.copy-btn').html('COPIED').addClass("copied");
	  });

	});
</script>
	
</head>

<body>

<div class="Bad-Upi-Report">The Entry has been Reported. Reloading Page...</div>
<div class="reload-overlay-div"></div>
<div class="content-div container">
	
	<h1 style="margin-top: -10px; text-align: center; margin-bottom: 40px;">HELP DAILY WAGE WORKERS</h1>

<?php
	
	date_default_timezone_set("Asia/Kolkata");
	$lockdown_begin = date_create("2020-03-25");
	$today = date('Y-m-d');
	$today = date_create($today);
	$diff=date_diff($lockdown_begin,$today);
	$diff = $diff->format("%a");
	$total_loss = $count * $diff * 1000;
	
?>
	
		<div class="row" style="text-align: justify;">
			<div class="col-md-2 col-sm-2 col-xs-4 pull-right date-box">
				<div class="date-box-date"><?php echo $diff; ?></div>
				<div class="date-box-content">DAYS SINCE<br/>LOCKDOWN<br/>BEGAN</div>
			</div>
			<div class="col-md-9">
				<!-- YOU CAN EDIT THE CONTENT TO BE DISPLAYED HERE. KEEP IT SHORT THOUGH. -->
		Due to the nation wide lockdown, many daily wage workers have not earned anything. They don't know where their next meal is coming from, or even if they have such a thing as a next meal. While nation-wide initiatives are a good thing and it is inspiring to see how we have risen to the occasion as a country, centralised funds of any form need time to reach that last person - who often is the one that needs it immediately. Hence, this little effort.
			</div>
		</div>
	<br style="clear: both;"/>
<div id="AccountInfoParentDiv">
	<div class="AccountInfoHeadline">
		Here's a worker who hasn't earned in <?php echo $diff; ?> days.
	</div>
	<div class="clearfix"></div>
<div id="AccountInfo">
	<?php if($row[4] == 'i2c-b1') : ?>
	<div class="col-md-12 col-sm-12 col-xs-12">
	<table style="border: none; text-align: left;">
		<tr>
			<td>Name: </td>
			<td><?php echo $row[1]; ?></td>
		</tr>
		<tr>
			<td>Story: </td>
			<td><?php echo $row[3]; ?></td>
		</tr>
		<tr>
			<td>IFSC: </td>
			<?php $to_copy = $row[5]; ?>
			<td><div class="upi-div" style="cursor: pointer; border-radius: 3px; padding-left: 10px;"onClick="javascript: copyToClipboard('<?php echo $to_copy; ?>');"><?php echo ($row[5]); ?> <div class="copy-btn">COPY</div></div></td>

		</tr>
		<tr>
			<td>Account: </td>
			<?php $to_copy = $row[6]; ?>
			<td><div class="upi-div" style="cursor: pointer; border-radius: 3px; padding-left: 10px;"onClick="javascript: copyToClipboard('<?php echo $to_copy; ?>');"><?php echo ($row[6]); ?> <div class="copy-btn">COPY</div></div></td>
		</tr>
	</table>
	</div>
	<?php else : ?>
	<div class="qr-div col-md-3 col-sm-12 col-xs-12">
		<img src="https://upiqr.in/api/qr/?name=<?php echo $row[1]; ?>&vpa=<?php echo $row[6]; ?>"/>
	</div>
	<div class="col-md-9 col-sm-12 col-xs-12">
 	<table style="border: none; text-align: left;">
		<tr>
			<td>Name: </td>
			<td><?php echo strtoupper($row[1]); ?></td>
		</tr>
		<tr>
			<td>Union: </td>
			<td><?php echo strtoupper($row[3]); ?></td>
		</tr>
		<tr>
			<td>Age: </td>
			<td><?php echo strtoupper($row[2]).' YRS'; ?></td>
		</tr>
		<tr>
			<td>Worked For: </td>
			<?php
				if($row[5] == NULL)
					$row[5] = 'N/A';
				else
					$row[5] = $row[5].' Yrs';
			?>
			<td><?php echo strtoupper($row[5]); ?></td>
		</tr>
		<tr>
			<td>UPI ID: </td>
			<?php $to_copy = $row[6]; ?>
			<td><div class="upi-div" style="cursor: pointer; border-radius: 3px; padding-left: 10px;"onClick="javascript: copyToClipboard('<?php echo $to_copy; ?>');"><?php echo ($row[6]); ?> <div class="copy-btn">COPY</div></div></td>
		</tr>
	</table>
	</div>
	<?php endif; ?>
	<div class="clearfix"></div>
</div>
	<div class="Tip-Div" style="text-align: center;">
		<div>TIP: Visit this page everyday and share as little as Rs. 50/- with the worker you see on your screen. It feeds them for the day.</div>
	</div>
</div>
	
	
<div style="padding: 7px; text-align: left;">
	<br/>
	<ul>
		<li>Copy the UPI ID and transfer money from your own UPI app to <?php echo $row[1]; ?>'s bank account.</li>
		<li>You can send any amount of money. It is upto you.</li>
		<li>If your transaction fails, please <span class="Tx-Fail-Div" style="color: #000; text-decoration: underline;">click here</span> to report the faulty ID to us.</li>
		<li>Thank you for your generosity. Hope you continue to help more workers.</li>
	</ul>
	<br/>
	<h3 style="margin-top: 0px; margin-bottom: 0px;">
		<div onClick="window.location.reload();" class="help-another-btn">Click Here to Help Another Worker &nbsp; &#x21E8;</div>
	</h3>
	<br/>
	Disclaimer:
	<ul>
		<li>The details are submitted to "<?php echo $GLOBALS['organisation_name']; ?>" by the workers themselves.</li>
		<li><?php echo $GLOBALS['organisation_name']; ?> doesn't take any money from the workers for this service.</li>
		<li>It's free for everyone.</li>
	</ul>
</div>
	
</div>


<div class="footer-content">
	<?php echo $GLOBALS['footer_links']; ?>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</body>
</html>