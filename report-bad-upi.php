<?php
// include configurations
require 'config.php';

// include dependencies
require_once 'libraries/google-api-php-client-2.4.0/vendor/autoload.php';

$mobile = $_GET['mobile'];
$name = $_GET['name'];
$upi = $_GET['upi'];

$client = new Google_Client();
$client->setApplicationName($GLOBALS['google_app_name']);
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__.'/assets/'.$GLOBALS['google_sheets_json_filename']);
$client->setDeveloperKey($GLOBALS['google_sheets_api_auth_key']);

$service = new Google_Service_Sheets($client);

$spreadsheetID = $GLOBALS['google_spreadsheet_ID'];

$range = 'Reported UPIs';

$values = [
	[$mobile, $name, $upi],
];

$body = new Google_Service_Sheets_ValueRange([
	'values' => $values
]);

$params = [
	'valueInputOption' => 'RAW'
];

$insert = [
	"insertData" => "INSERT_ROWS"
];

try{
	$response = $service->spreadsheets_values->append($spreadsheetID, $range, $body, $params, $insert);
}catch(Exception $e){}
?>