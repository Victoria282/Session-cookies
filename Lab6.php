<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$host = 'localhost'; 
	$user = 'id13894961_victory'; 
	$password = 'J?i@r+OWWrx_gw17'; 
	$db_name = 'id13894961_laba6'; 
	$link = mysqli_connect($host, $user, $password, $db_name);	
	$result = ['default' => 'default'];
	

	if (!isset($_SESSION['stage']) and stripos($_SERVER['HTTP_REFERER'], 'Registration.html')) {
		$_SESSION['stage'] = 'test';
		for ($i = 0; $i < 10; $i++) { 
			$_SESSION['mult'][$i]['first'] = random_int(1, 20);
			$_SESSION['mult'][$i]['second'] = random_int(1, 20);
			$_SESSION['mult'][$i]['result'] = $_SESSION['mult'][$i]['first'] * $_SESSION['mult'][$i]['second'];
		}
		$_SESSION['user'] = $_POST['surname'] . ' ' . $_POST['name'] . ' ' . $_POST['patronymic'];
		$_SESSION['date'] = $_POST['date'];
		$_SESSION['mail'] = $_POST['mail'];
	} 


	else  if($_SESSION['stage'] == 'test' and stripos($_SERVER['HTTP_REFERER'], 'Test.html')) {
		if(isset($_POST['answers'])) {
			$_SESSION['answers'] = 0;
			for ($i = 0; $i < 10; $i++) {
				if($_POST[$i.''] == $_SESSION['mult'][$i]['result'])
					$_SESSION['answers']++;
			}
			$_SESSION['stage'] = 'end';
			$_SESSION['time'] = time() - $_SESSION['time'] - 1;
		} 

		else {
			if(!isset($_SESSION['time'])) 
				$_SESSION['time'] = time();
			

			$result = $_SESSION['mult'];
		}
	} 

	else  if($_SESSION['stage'] == 'end' and stripos($_SERVER['HTTP_REFERER'], 'Results.html')) {
		mysqli_query($link, "INSERT INTO Lab6 (user, day, mail, result, timer) VALUES ('".$_SESSION['user']."', '".$_SESSION['date']."', '".$_SESSION['mail']."', '".$_SESSION['answers'].'/10'."', '".$_SESSION['time']."')") or die(mysqli_error($link));
		$result = [
			'%' => $_SESSION['answers'] . '/10',
			'time' => $_SESSION['time'],
			'user' => $_SESSION['user']
		];
		
		session_unset();
	}

	echo json_encode($result);
?>