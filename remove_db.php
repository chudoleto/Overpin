<?

$out = array(
	'success' => true,
	'message' => '',
);

$id = (isset($_POST['id']) ? $_POST['id'] : '');

if (!$id) {
	$out['message'] = 'Не заполнен id!';
	$out['success'] = false;
}

if ($out['success']) {
	
	$mysqli = new Mysqli('localhost', 'mysql', 'mysql', 'bd1');
	$query = $mysqli->query("DELETE FROM dbcomment WHERE id = $id");
}

header('Content-Type: text/json; charset=utf-8');

echo json_encode($out);
