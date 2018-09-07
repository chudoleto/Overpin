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


// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);
