<?php
include '../php/action.php';
if (isset($_POST['log_out'])) {
	unset($_SESSION['logged_user']);
	header('Location: ../login/index.php');
}
if (isset($_SESSION['logged_user'])) {
?>
<!DOCTYPE html>
<html>

<!--Головушка-->

<head>
	<meta charset="utf-8">
	<!--Тип Кодировки-->
	<title>Ремонт</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
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
		<button type="button" class="btn btn-info" style="width: 90px" onclick=location.href='../php/logout.php'>Выйти</button>
	</div>
    <form action="../fix_page.php" method="post">
    			<p><input type="date" class="custom-select" name="fix_data" id="fix_data" style="width: 60%"></p>
          <p><input type="text" class="input-group-text" name="fixer" placeholder="Ответственный за ремонт" maxlength="200" style="width: 60%; margin: auto"></p>
          <p><input type="textarea" name="fix_type" placeholder="Вид ремонта" maxlength="500" style="width: 60%; margin: auto"></p>
		</form>
    <input type="submit" class="btn btn-primary" name="save_page_list" value="Сохранить">
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
	<p style="font-size:30pt; color: #800000; text-align: center">Доступ запрещён. Вы можете <a href="../login/index.php"> авторизоваться</a>.<p>&nbsp;</div>' .
	'</body>
	</html>';
}
?>