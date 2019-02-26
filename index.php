<?php
include_once 'php/action.php';
if (isset($_POST['log_out'])) {
	unset($_SESSION['logged_user']);
	header('Location: login/index.php');
}
if (isset($_SESSION['logged_user'])) {
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
    <form action="../link_list.php" method="post">
    	<p><input type="button" class="btn btn-success" value="Регистрация паспорта" onclick=location.href='passport/index.php'></p>
        <p><input type="button" class="btn btn-success" value="Список паспортов" onclick=location.href='list/index.php' title="Изменение, удаление, ремонт и печать"></p>
        <p><input type="submit" class="btn btn-success" name="fix_list" value="Список ремонтов ПК"></p>
        <p><input type="submit" class="btn btn-success" name="per_list" value="Регистрация периферийного устройства" title="Создание устройства, не привязанного к конкретному ПК"></p>
        <p><input type="submit" class="btn btn-success" name="per_list" value="Список периферийных устройств" title="Устройства, не привязанные к конкретному ПК"></p>
        <p><input type="submit" class="btn btn-success" name="make_oper" value="Регистрация учётной записи пользователя" title="Регистрация операторов и администраторов, имеющих доступ к системе"></p>
		<p><input type="submit" class="btn btn-success" name="oper_list" value="Список пользователей" title="Операторы и администраторы, имеющие доступ к системе"></p>
		<p><input type="submit" class="btn btn-success" name="oper_list" value="Регистрация сотрудника" title="Регистрация сотрудника в базе данных"></p> 
		<p><input type="submit" class="btn btn-success" name="oper_list" value="Список сотрудников" title="Все сотрудники в штате"></p>    
	</form>
	</div>
</body>

</html>
<?php
} else {
	echo '<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Паспорт.</title>
	</head>
	<body style="background-color: #c0c0c0">' .
	'<div style="margin-top: 18%; width: 80%; margin-left: 10%; background-color: #eeeeee; border-radius: 10pt">
	<p>&nbsp;
	<p style="font-size:30pt; color: #800000; text-align: center">Доступ запрещён. Вы можете <a href="login/index.php"> авторизоваться</a>.<p>&nbsp;</div>' .
	'</body>
	</html>';
}
?>