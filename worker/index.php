<?php
include_once '../php/action.php';
include_once '../php/fillprint.php';
if (isset($_POST['log_out'])) {
	unset($_SESSION['logged_user']);
	header('Location: ../login/index.php');
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
	<title>Регистрация сотрудника</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
    <script type="text/javascript">
		function set_block() {
			document.getElementById('is_working').setAttribute('disabled', 'disabled');
		}
		
        function get_old_page(id) {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					get_worker_data: id},
				dataType: "json",
				success: function(data){
					document.getElementById('full_name').value = data[0];
					document.getElementById('position').value = data[1] == null ? '' : data[1];
					document.getElementById('office').value = data[2] == null ? '' : data[2];
					document.getElementById('is_working').value = data[3] == 'Работает' ? '' : '1';
				}
			}).responseText;
			document.getElementById('save_btn').setAttribute('name', 'update_worker');
			document.getElementById('user_header').innerHTML = 'Редактирование данных сотрудника';
		}
    </script>
</head>

<!--Тушка-->

<body>
	<div class="table-responsive text-center" style="width: 70%; margin: auto">
		<div style="text-align: right">
            <p style="margin: 0; font-size: 16pt">Здравствуйте,
            <?php
                echo " " . $_SESSION['logged_user'] . "!";
            ?>
            </p>
            <button type="button" class="btn btn-info" style="width: 90px" onclick=location.href='../php/logout.php'>Выйти</button>
        </div>
		<h1 id="user_header">Регистрация сотрудника в базе данных</h1>
        <form id="form" action="../php/action.php" method="post">
            <table id="new_worker" class="table table-bordered table-hover " style="margin-top: 20px">
      			<tbody style="background-color: #D3D3D3">
      				<tr>
      					<th>ФИО сотрудника</th>
      					<th>Должность</th>
      					<th>Кабинет</th>
						<th>Статус</th>
      				</tr>
      			</tbody>

      			<tbody>
      				<tr>
      					<td>
      						<p><input type="text" class="input-group-text" name="full_name" id="full_name" placeholder="ФИО сотрудника" maxlength="200" required></p>
      					</td>
      					<td>
							<select class="custom-select" name="position" id="position">
								<option value=""> Выберите должность
								<?php
									get_db_list($pdo, 'Position', 'position', '', '');
								?>
							</select>
      						<input type="text" class="input-group-text" name="position_manually" placeholder="Ручной ввод" maxlength="100">
      					</td>
      					<td>
							<select class="custom-select" name="office" id="office">
								<option value=""> Выберите кабинет
								<?php
									get_db_list($pdo, 'Office', 'office', '', '');
								?>
							</select>
      					</td>
						<td>
							<select class="custom-select" name="is_working" id="is_working">
								<option value=""> Работает
								<option value="1"> Не работает
							</select>
      					</td>
      				</tr>
				</tbody>
			</table>
            <input id="save_btn" type="submit" class="btn btn-primary" name="save_worker" value="Сохранить">
        </form>
	</div>
</body>

</html>
<?php
	if (isset($_GET['id'])) {
		$ID_worker = htmlspecialchars($_GET['id']);
		echo '<script type="text/javascript">
			get_old_page(' . $ID_worker . ');
			document.getElementById("form").setAttribute("action", "../php/action.php?id=' . $ID_worker .'");';
		if (is_responsible_now($pdo, $ID_worker)) {
			echo 'set_block();';
		}
		echo '</script>';
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
	<p style="font-size:30pt; color: #800000; text-align: center">Доступ запрещён. Вы можете <a href="../login/index.php"> авторизоваться</a>.<p>&nbsp;</div>' .
	'</body>
	</html>';
}
?>
