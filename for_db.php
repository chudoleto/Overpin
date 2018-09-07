<?

$out = array(
	'success' => true,
	'message' => '',
	'dbcomment' => [],
);

$name = (isset($_POST['name']) ? $_POST['name'] : '');
$comment = (isset($_POST['comment']) ? $_POST['comment'] : '');
$date = date('Y-m-d H:i:s');

if (!$name) {
	$out['message'] = 'Не заполнено имя!';
	$out['success'] = false;
}
if (!$comment) {
	$out['message'] = 'Не заполнен комментарий!!';
	$out['success'] = false;
}


if ($out['success']) {
	
	$mysqli = new Mysqli('localhost', 'mysql', 'mysql', 'bd1');
	$query = $mysqli->query("INSERT INTO dbcomment (name, date, comment) VALUES('$name', '$date', '$comment')");
	$id = $mysqli->insert_id;

	$dbcomment = [];
	$dbcomment['id'] = $id;
	$dbcomment['name'] = $name;
	$dbcomment['date'] = date('d.n.Y H:i:s', strtotime($date));
	$dbcomment['comment'] = $comment;

	$out['dbcomment'] = $dbcomment;
}

header('Content-Type: text/json; charset=utf-8');

echo json_encode($out);
