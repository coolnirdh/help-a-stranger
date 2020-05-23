<?php
//CONFIGURATION SETTINGS

//NAME OF YOUR ORGANISATION :: E.g., Home Talkies
$organisation_name = 'Covid19 India Handbook';
//$organisation_name = '';

//NAME OF YOUR GOOGLE APP
$google_app_name = 'Help A Stranger';

//GOOGLE SHEETS API JSON CREDENTIALS FILENAME - MAKE SURE THIS FILE IS IN THE 'assets' FOLDER
$google_sheets_json_filename = 'helpastranger-19c59abd4b4e.json';

//GOOGLE SHEETS API AUTH KEY
$google_sheets_api_auth_key = '19c59abd4b4e2a9f3be683038a1496e465129141';

//SPREADSHEET ID
$google_spreadsheet_ID = '1eCtI5KPupmb90ORaSXwyGh_cdtOkh5n65DGN5NTilzM';

//CHOOSE IF ENTRIES SHOWN MUST BE SEQUENTIAL OR RANDOM :: 1 SHOWS SEQUENTIAL ENTRIES, 0 SHOWS RANDOM ENTRIES
$sequential_entries = 1;


//OPENGRAPH SETTINGS FOR SOCIAL SHARING
/*
If you make any changes to the Opengraph values below, be sure to make Facebook scrape your page again so that the new values are indexed and show up when someone shares it. You can do that from here: https://developers.facebook.com/tools/debug/
*/

//PAGE TITLE - ALSO DOUBLES AS FACEBOOK OPENGRAPH TITLE
$fb_og_title = 'Help a Daily Wage Worker';

//FACEBOOK OPENGRAPH DESCRIPTION
$fb_og_desc = 'Help daily wage workers sail through the current COVID-19 pandemic.';

//FACEBOOK OPENGRAPH IMAGE
/*
Enter ONLY the filename and ensure this is placed in the 'images' folder
*/
$fb_og_img = 'fb-og.jpg';



//DO NOT EDIT AFTER THIS

$GLOBALS['organisation_name'] = $organisation_name;
$GLOBALS['google_app_name'] = $google_app_name;
$GLOBALS['google_sheets_json_filename'] = $google_sheets_json_filename;
$GLOBALS['google_sheets_api_auth_key'] = $google_sheets_api_auth_key;
$GLOBALS['google_spreadsheet_ID'] = $google_spreadsheet_ID;
$GLOBALS['fb_og_title'] = $fb_og_title;
$GLOBALS['fb_og_desc'] = $fb_og_desc;
$GLOBALS['fb_og_img'] = $fb_og_img;

$GLOBALS['seq_entries'] = $sequential_entries;


$GLOBALS['footer_links'] = '- Code by <a href="https://www.instagram.com/suryavasishta/" target="_blank">@suryavasishta</a> -<br/><a href="https://www.google.com/search?q=pay+it+forward+meaning" target="_blank">No Rights Reserved.</a>';
?>
