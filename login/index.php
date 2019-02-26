<?php 
	include_once '../php/action.php';
    $pdo = connect_db();
	
	// нельзя напрямую обращаться к POST-переменным
	$data = $_POST;
	if (isset($data['log_in'])) {
		$user = $data['usr'];
		if (find_user($pdo, $user)) {
			//логин существует
			if ($data['pswrd'] == find_password($pdo, $user)) {
				//если пароль совпадает, то нужно авторизовать пользователя
				$_SESSION['logged_user'] = $user;
				if (!isset($_SESSION['portion_size'])) {
					$_SESSION['portion_size'] = 20;
				}
				session_write_close();
				header('Location: ../passport/index.php');
				exit();
			} else {
				$errors[] = 'Неверный пароль!';
			}
		} else {
			$errors[] = 'Пользователь не найден!';
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Вход</title>
		<link rel="stylesheet" href="../css/login.css">
	</head>

	<body>

		<form id="login" action="index.php" method="post">
		    <h1>Вход в систему</h1>
			<?php
				if (!empty($errors)) {
					echo "<p style = \"color: red; font-size: 14pt; margin: 0; padding: 0\">" . array_shift($errors);
				}
			?>
			<fieldset id="inputs">
		        <input id="username" name="usr" type="text" placeholder="Логин" autofocus required>
		        <input id="password" name="pswrd" type="password" placeholder="Пароль" required>
		    </fieldset>
		    <fieldset id="actions">
		        <input id="submit" type="submit" name="log_in" value="Войти">
		    </fieldset>
		</form>
	</body>
</html>
