<?php
include_once 'php/action.php';
if (isset($_POST['log_out'])) {
	unset($_SESSION['logged_user']);
	header('Location: login/index.php');
}
if (isset($_SESSION['logged_user'])) {
	$pdo = connect_db();
?>
<!DOCTYPE html>
<html>

<!--Головушка-->

<head>
	<meta charset="utf-8">
	<!--Тип Кодировки-->
	<title>Паспорт</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="css/bootstrap.css">
	<style>
		input {
			min-width: 60%;
			font-size: 16pt !important;
		}
	</style>
	<script type="text/javascript" src="script/jquery.js"></script>
	<script type="text/javascript">
		function set_block() {
			var x = $.ajax({
				type: 'POST',
				url: 'php/ajaxData.php',
				async: false,
				data: {
					get_permissions: ''},
				dataType: "json",
				success: function(data){
					if (data != 0) {
						document.getElementById('new_user').setAttribute('disabled', 'disabled');
						document.getElementById('users_list').setAttribute('disabled', 'disabled');
						document.getElementById('offices').setAttribute('disabled', 'disabled');
					}
				}
			}).responseText;
		}
	</script>
</head>

<!--Тушка-->

<body>
	<div class="table-responsive text-center" style="width: 80%; margin: auto">
		<div style="text-align: right">
			<p style="margin: 0; font-size: 16pt">Здравствуйте,
			<?php
				echo " " . $_SESSION['logged_user'] . "!";
			?>
			</p>
			<button type="button" class="btn btn-info" style="width: 90px" onclick=location.href='php/logout.php'>Выйти</button>
		</div>
		<p><input type="button" class="btn btn-success" value="Регистрация паспорта" onclick=location.href='passport/index.php'></p>
		<p><input type="button" class="btn btn-success" value="Список паспортов" onclick=location.href='list/index.php' title="Изменение, удаление, ремонт и печать"></p>
		<p><input type="button" class="btn btn-success" value="Регистрация периферийного устройства" onclick=location.href='periphery/index.php' title="Создание устройства, не привязанного к конкретному ПК"></p>
		<p><input type="button" class="btn btn-success" value="Список периферийных устройств" onclick=location.href='periphery_list/index.php' title="Устройства, не привязанные к конкретному ПК"></p>
		<p><input type="button" class="btn btn-success" id="new_user" value="Регистрация учётной записи пользователя" onclick=location.href='user/index.php' title="Регистрация операторов и администраторов, имеющих доступ к системе"></p>
		<p><input type="button" class="btn btn-success" id="users_list" value="Список пользователей" onclick=location.href='users_list/index.php' title="Операторы и администраторы, имеющие доступ к системе"></p>
		<p><input type="button" class="btn btn-success" value="Регистрация сотрудника" onclick=location.href='worker/index.php' title="Регистрация сотрудника в базе данных"></p> 
		<p><input type="button" class="btn btn-success" value="Список сотрудников" onclick=location.href='workers_list/index.php' title="Все сотрудники в штате"></p>
		<p><input type="button" class="btn btn-success" id="offices" value="Кабинеты" onclick=location.href='offices/index.php'></p>
	</div>
</body>

</html>
<?php
$user_level = get_permissions($pdo, $_SESSION['logged_user']);
if ($user_level != 0) {
	echo '<script type="text/javascript"> set_block(); </script>';
}
} else {
	echo '<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Паспорт</title>
	</head>
	<body style="background-color: #c0c0c0">' .
	'<div style="margin-top: 18%; width: 80%; margin-left: 10%; background-color: #eeeeee; border-radius: 10pt">
	<p>&nbsp;
	<p style="font-size:30pt; color: #800000; text-align: center">Доступ запрещён. Вы можете <a href="login/index.php"> авторизоваться</a>.<p>&nbsp;</div>' .
	'</body>
	</html>';
}
?>