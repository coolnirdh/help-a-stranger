<?php
function get_lastShown(){
	$client = new Google_Client();
	$client->setApplicationName($GLOBALS['google_app_name']);
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(__DIR__.'/assets/'.$GLOBALS['google_sheets_json_filename']);
	$client->setDeveloperKey($GLOBALS['google_sheets_api_auth_key']);

	$service = new Google_Service_Sheets($client);

	$spreadsheetID = $GLOBALS['google_spreadsheet_ID'];

	$range = 'Last Shown!A1';
	
	try{
		$response = $service->spreadsheets_values->get($spreadsheetID, $range);

		$values = $response->getValues();
	}catch(Exception $e){}

	if(empty($values)){
			return NULL;
	}else{
		return $values[0][0];
	}
}

function update_lastShown($last_shown){
	$client = new Google_Client();
	$client->setApplicationName($GLOBALS['google_app_name']);
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(__DIR__.'/assets/'.$GLOBALS['google_sheets_json_filename']);
	$client->setDeveloperKey($GLOBALS['google_sheets_api_auth_key']);

	$service = new Google_Service_Sheets($client);

	$spreadsheetID = $GLOBALS['google_spreadsheet_ID'];

	$range = 'Last Shown!A1';
	
	$last_shown++;
	
	$values = [
		[$last_shown],
	];

	$body = new Google_Service_Sheets_ValueRange([
		'values' => $values
	]);

	$params = [
		'valueInputOption' => 'RAW'
	];

	try{
	$response = $service->spreadsheets_values->update($spreadsheetID, $range, $body, $params);
	} catch(Exception $e){}
	
}

function recordPageVisit($timestamp, $pageVisitId, $uuid, $userAgent, $mobile) {
	$client = new Google_Client();
	$client->setApplicationName($GLOBALS['google_app_name']);
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(__DIR__.'/assets/'.$GLOBALS['google_sheets_json_filename']);
	$client->setDeveloperKey($GLOBALS['google_sheets_api_auth_key']);

	$service = new Google_Service_Sheets($client);

	$spreadsheetID = $GLOBALS['google_spreadsheet_ID'];

	$range = 'Page Visits';

	$values = [
		["=$timestamp/86400 + DATE(1970,1,1)", $pageVisitId, $uuid, $userAgent, $mobile],
	];

	$body = new Google_Service_Sheets_ValueRange([
		'values' => $values
	]);

	$params = [
		'valueInputOption' => 'USER_ENTERED'
	];

	$insert = [
		"insertData" => "INSERT_ROWS"
	];

	try{
		$response = $service->spreadsheets_values->append($spreadsheetID, $range, $body, $params, $insert);
	}catch(Exception $e){}
}

function get_entries(){
	$client = new Google_Client();
	$client->setApplicationName($GLOBALS['google_app_name']);
	$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
	$client->setAccessType('offline');
	$client->setAuthConfig(__DIR__.'/assets/'.$GLOBALS['google_sheets_json_filename']);
	$client->setDeveloperKey($GLOBALS['google_sheets_api_auth_key']);

	$service = new Google_Service_Sheets($client);

	$spreadsheetID = $GLOBALS['google_spreadsheet_ID'];

	$range = 'Unique Values!A:H';
	
	try{
		$response = $service->spreadsheets_values->get($spreadsheetID, $range);
		$values = $response->getValues();
	} catch(Exception $e){}

	if(empty($values)){
			return NULL;
	}else{
		return $values;
	}

}

?>