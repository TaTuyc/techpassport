<?php
include 'action.php';
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
	<title>Паспорт.</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="css/bootstrap.css">
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
		<button type="button" class="btn btn-info" style="width: 90px" onclick=location.href='logout.php'>Выйти</button>
		</div>
    <form action="../link_list.php" method="post">
    		<p><input type="submit" class="btn btn-success" style="width: 356px" name="open_list" value="Открыть список паспортов"></p>
        <p><input type="submit" class="btn btn-success" style="width: 356px" name="per_list" value="Список переферийных устройств"></p>
        <p><input type="submit" class="btn btn-success" style="width: 356px" name="fix_list" value="Открыть список ремонтов"></p>
        <p><input type="submit" class="btn btn-success" style="width: 356px" name="oper_list" value="Открыть список операторов"></p>
        <p><input type="submit" class="btn btn-success" style="width: 356px" name="make_pass" value="Создать паспорт"></p>
        <p><input type="submit" class="btn btn-success" style="width: 356px" name="make_oper" value="Создать запись операторов"></p>
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