<?php
if (isset($_GET['action']) && $_GET['action']) {
	require_once('dialogs.api.php');

	$DialogsApi = new Intrum\DialogsApi(/* your system host */ , /* apikey */);
	switch ($_GET['action']) {
		case 'getClient':
			if (isset($_GET['dialogid']) && $_GET['dialogid']) {
				$d = $DialogsApi->createDialog(0 , array(
					'id' => $_GET['dialogid']
				));
				echo $d->id . ":" . $d->clientkey;
			} else {
				header("HTTP/1.1 400 Bad Request");
				echo "dialogid not settet";
			}
		break;
		case 'loadHistory':
			if (isset($_POST['group']) && $_POST['group'] && isset($_POST['client']) && $_POST['client']) {
				header("Content-Type: application/json");
				echo json_encode($DialogsApi->loadHistory($_POST['group'] , $_POST['client'] , (isset($_POST['date']) && $_POST['date']) ? $_POST['date'] : time()) , (isset($_POST['page']) && $_POST['page']) ? $_POST['page'] : 0 , (isset($_POST['count']) && $_POST['count']) ? $_POST['count'] : 20);
			} else {
				header("HTTP/1.1 400 Bad Request");
				echo "group or client not set";
			}
		break;
		case 'getDialog':
			if (file_exists('dialog')) {
				$dialogid = file_get_contents('dialog');
			} else {

			}
		break;
		default:
			header("HTTP/1.1 400 Bad Request");
			echo "invalid action";
		break;
	}

}
?>