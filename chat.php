<?php
	function checkIfNotBot() {
		
		$secret = '6Le3eG4UAAAAAOlrDQc6rG9ppWakf2vlwHh3C1RE';
		$recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
		
		// ...
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = http_build_query ([
			'secret' => $secret,
			'response' => $recaptcha_response,
		]);
		
		$options = [
			'http' => [
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => $data,
			]
		];
		
		$context = stream_context_create($options);
		$response = file_get_contents($url, false, $context);
		
		$captcha_result = json_decode($response);
		if ($captcha_result->success === false) {
			return false;
		}
		return true;
	}
	
	$mysqli = new Mysqli('localhost', 'mysql', 'mysql', 'bd1');
	$msg_list = $mysqli->query("SELECT * FROM `dbcomment`")->fetch_all(MYSQLI_ASSOC);
	
	$result_message = '';
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (checkIfNotBot()) {
			$result_message .= '';
		} else {
			$result_message .= '';
		}
	}
	
	
?>


<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script>
			var captchaCallback = function() {
				document.getElementById('submit').disabled = false;
			}
		</script>
    <title>Comment</title>
</head>



<body>
    <header>
        <nav class="nav-menu">
            <a href="#">Главная</a>
        </nav>
    </header>
    <main class="container">
		<div class="image-wrap">
			<div class = "image">
				<img class = "image1 img-responsive" src = "/images/IMG_5562.jpg"> 
			</div>
	  
			<ul class="rows">
				<?php if ($msg_list) { ?>
					<?php foreach ($msg_list as $msg_item) { ?>
						<li>
							<div class="msg-row">
								<div class="msg-hdr msg-name"><?php  echo $msg_item['name'] ?></div>
								<div class="msg-hdr msg-date text-right"><?php  echo date('d.n.Y H:i:s', strtotime($msg_item['date'])) ?></div>
							</div>
							<div class="msg-row">
								<div class="msg-comment"><?php  echo $msg_item['comment'] ?></div>
								<div class="msg-button text-right" title = "Удалить"><button data-role="delete" class = "btn-remove" data-record-id="<?php  echo $msg_item['id'] ?>">X</button></div>
							</div>
							<hr>
						</li>
					<?php }?>
				<?php }?>
			</ul>
			
			<div class = "forms">
				<form name="form1" method="post" action="">
					<input type="text" name="name" id="nameField" placeholder="Введите имя">
					<p><textarea type="text" name="comment" id="commentField" placeholder="Введите комментарий"></textarea></p>
					<button type="submit" name="submit"  class = "btn-add" id ="submit" disabled>Добавить</button>
					<div class="g-recaptcha" data-sitekey="6Le3eG4UAAAAAHLgjrZFOLaGMX-o6PsYbolUB5sU" data-callback="captchaCallback"></div>
				</form>
			</div>
			
		</div>
    </main>

    <footer>
 
    </footer>

	<script>
	
	var onDeleteButtonClick = function() {
		var rec_id = $(this).data('record-id');
		var block = $(this).closest('li');
		
		$.ajax({
			url: "remove_db.php",
			type: "POST",
			data: {id:rec_id },
			dataType: "json",
			success: function(result) {
				if (result.success){
					block.remove();
				}else{
					alert(result.message);
				}
				return false;
			},
			error: function(result) {
				alert(result.status + ": " + result.statusText);
			},
		});

	};
	
	$(document).ready(function() {
		
		
		$("#submit").bind("click", function() {

			var name = $('#nameField').val();
			var date = $('#dateField').val();
			var comment = $('#commentField').val();
			
			$('#nameField').val('');
			$('#dateField').val('');
			$('#commentField').val('');
			
			$.ajax({
				url: "for_db.php",
				type: "POST",
				data: {name:name, date:date, comment:comment}, 
				dataType: "json",
				success: function(result) {
					if (result.success){
						$('.rows').append(
							'<li>' +
							'<div class="msg-row">'+
								'<div class="msg-hdr msg-name">' + result.dbcomment.name + '</div>' +  
								'<div class="msg-hdr msg-date text-right">' + result.dbcomment.date + '</div>' + 
							'</div>'+
							'<div class="msg-row">'+
								'<div class="msg-comment">' + result.dbcomment.comment + '</div>' +
								'<div class="msg-button text-right"><button data-role="delete" data-record-id="' + result.dbcomment.id + '">X</button></div>' +
							'</div>'+
							'<hr>' +
							'</li>'
						);
						$('[data-role="delete"][data-record-id="'+result.dbcomment.id+'"]').click(onDeleteButtonClick);
						console.log(result);
					}else{
						alert(result.message);
					}
					return false;
				},
				error: function(result) {
					alert(result.status + ": " + result.statusText);
				},
			});
			return false;
		});
		
		$('[data-role="delete"]').click(onDeleteButtonClick);
	});
	</script>
	
</body>

</html>