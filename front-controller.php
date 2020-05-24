<?php

$uuid_cookie_name = "uuid";
if(!isset($_COOKIE[$uuid_cookie_name])) {
	$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
	setcookie($uuid_cookie_name, $uuid, $timeInSeconds + (86400 * 365), "/"); // 86400 = 1 day
}

switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
	case '/update.php':
		require 'update.php';
		break;
	case '/donate.php':
		require 'donate.php';
		break;
	case '/report-bad-upi.php':
		require 'report-bad-upi.php';
		break;
	case '/validation':
	case '/validation/':
		require 'validation/index.php';
		break;
	case '/validation/update.php':
		require 'validation/update.php';
		break;
	case '/':
		require 'index.php';
		break;
	default:
		http_response_code(404);
		exit('Not Found');
}
?>