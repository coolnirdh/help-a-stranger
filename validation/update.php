<?php
// include configurations
require '../config.php';

// include dependencies
require_once '../libraries/google-api-php-client-2.4.0/vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName($GLOBALS['google_app_name']);
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__.'/../assets/'.$GLOBALS['google_sheets_json_filename']);
$client->setDeveloperKey($GLOBALS['google_sheets_api_auth_key']);

$service = new Google_Service_Sheets($client);

$spreadsheetID = $GLOBALS['google_spreadsheet_ID'];

$range = 'Unique Values!A:H';

$response = $service->spreadsheets_values->get($spreadsheetID, $range);

$values = $response->getValues();

if($values == NULL){
	echo 'Invalid Spreadsheet.';
	exit();
}

$mobile = $_GET['mobile'];
$verify = $_GET['verify'];

$i = 0;

while(1){
	$row = $values[$i];
	if($row[0] == $mobile){
		//Match Found. Update This Row.
		break;
	}
	else{
		$i++;
	}
}

$i = $i+1;


// UPDATION HAPPENS HERE
$service = new Google_Service_Sheets($client);


$range = 'Unique Values!H'.$i;

$values = [
	[$verify],
];

$body = new Google_Service_Sheets_ValueRange([
	'values' => $values
]);

$params = [
	'valueInputOption' => 'RAW'
];

$response = $service->spreadsheets_values->update($spreadsheetID, $range, $body, $params);

?>