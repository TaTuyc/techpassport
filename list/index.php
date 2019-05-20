<?php
include_once '../php/action.php';
include_once '../php/fillprint.php';
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
	<title>Список паспортов</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/setTable.js"></script>
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
	<script type="text/javascript" src="../script/list.js"></script>
	<?php
		$pdo = connect_db();
	?>
</head>

<body>
	<div style="display: flex; width: 80%; margin: auto">
		<div id="page_settings" style="text-align: left; width: 50%">
			<p>Количество страниц для просмотра</p>
			<a onclick="set_portion_size(2);">&nbsp;2</a>
			<a onclick="set_portion_size(3);">&nbsp;3</a>
			<a onclick="set_portion_size(10);">&nbsp;10</a>
			<a onclick="set_portion_size(20);">&nbsp;20</a>
			<a onclick="set_portion_size(40);">&nbsp;40</a>
		</div>
		
		<div style="text-align: right; width: 50%">
			<p style="margin: 0; font-size: 16pt">Здравствуйте,
				<?php
					echo " " . $_SESSION['logged_user'] . "!";
				?>
			</p>
			<button type="button" class="btn btn-info" style="width: 90px" onclick=location.href='../php/logout.php'>Выйти</button>
		</div>
	</div>
	
	<div class="table-responsive text-center" style="width: 80%; margin: auto">
		<table id="list" class="table table-bordered table-hover ">
			<thead>
				<tr>
					<th colspan="9" style="background-color: #8FBC8F">Список паспортов</th>
				</tr>
			</thead>

			<tbody>
				<tr style="background-color: #D3D3D3">
					<th scope="row" style="width: 20%">Имя рабочей станции</th>
					<th scope="row" style="width: 25%">Кабинет</th>
					<th scope="row" style="width: 25%">Инвентарный номер</th>
					<th scope="row" colspan="6">Действия</th>
				</tr>
				
				<tr style="background-color: #D3D3D3">
					<td>
						<input id="search_pc_name" type="text" class="input-group-text" placeholder="Имя" maxlength="20">
					</td>
					<td>
						<select id="search_pc_place" class="custom-select">
							<option value=""> Кабинет
							<?php
								get_db_list($pdo, 'Office', 'office', '', '');
							?>
						</select>
					</td>
					<td>
						<input id="search_pc_inv_num" type="text" class="input-group-text" placeholder="Номер" maxlength="20" pattern="[0-9]{0,20}">
					</td>
					<td scope="row" colspan="6">
						<button id="search_passport" type="button" class="chng btn btn-primary" onclick="search_pc()">Поиск по заданным полям</button>
					</td>		
				</tr>

				<tbody id="pas_list">
				</tbody>
				
				<tbody id="num_pages" style="border: none">
				</tbody>
			</tbody>
		</table>
	</div>
	<?php
		echo '<script type="text/javascript">
			get_portion(1);
		</script>';
	?>
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
	<p style="font-size:30pt; color: #800000; text-align: center">Доступ запрещён. Вы можете <a href="../login/index.php"> авторизоваться</a>.<p>&nbsp;</div>' .
	'</body>
	</html>';
}
?>
