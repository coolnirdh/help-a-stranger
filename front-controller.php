
<?php
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