<?php
require_once('functions.php');
// check_upgrade_by_time($_REQUEST['host']);
switch ($_REQUEST['action']) {
	case 'sendMessage':
		$tel_text = "Star Working";
		send_message($tel_text);
		break;
	
	default:
		// code...
		break;
}
?>