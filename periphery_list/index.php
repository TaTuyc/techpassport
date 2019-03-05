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

	<title>Список периферийных устройств</title>
	<!--Заголовок-->

	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
	<script type="text/javascript">
		function get_delete_confirmation(id) {
			var answer = confirm("Удалить устройство из базы данных?");
			if (answer) {
				var x = $.ajax({
					type: 'POST',
					url: '../php/ajaxData.php',
					async: false,
					data: {
						delete_pd: id},
					dataType: "json",
					success: function(data){
					}
				}).responseText;
				document.location.reload(true);
			}
		}
		
		function is_pd_exist(id, call_type) {
			var answer = false;
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					is_pd_exist: id},
				dataType: "json",
				success: function(data){
					answer = data;
				}
			}).responseText;
			if (!answer) {
				alert("Устройство удалено из базы данных, страница будет перезагружена.");
				document.location.reload(true);
			} else {
				if (call_type == 'edit') {
					document.location.href = '../periphery/index.php?id=' + id;
				}
			}
		}
		
		function get_pd_list() {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					get_pd_list: ''},
				dataType: "json",
				success: function(data){
					rows = data.length / 6;
					dl = data.length;
                    buff = '';
					for (i = 0; i < rows; i++) {
						buff +=
                        '<tr>' +
                            '<td>' + data[i * 6] + '</td>' +
                            '<td>' + data[i * 6 + 1] + '</td>' +
                            '<td>' + data[i * 6 + 2] + '</td>' +
							'<td>' + data[i * 6 + 3] + '</td>' +
							'<td>' + data[i * 6 + 4] + '</td>' +
							'<td><button type="button" class="del btn btn-danger" onclick="get_delete_confirmation(' + data[i * 6 + 5] + ')">Удалить</button></td>' +
							'<td><button type="button" class="chng btn btn-primary" style="width: 100%" onclick="is_pd_exist(' + data[i * 6 + 5] + ', \'edit\')">Изменить</button></td>' +
                        '</tr>';
                    }
					$('#pd_list').html(buff);
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
			<button type="button" class="btn btn-info" style="width: 90px" onclick=location.href='../php/logout.php'>Выйти</button>
		</div>
		<table id="per_reg" class="table table-bordered table-hover " style="margin-top: 20px">
			<thead>
				<tr>
					<th style="background-color: #8FBC8F" colspan="7">Список периферийных устройств</th>
				</tr>
            </thead>

            <tbody>
                <tr style="background-color: #D3D3D3">
					<th>Наименование</th>
					<th>Описание</th>
					<th>Характеристика</th>
					<th>Инвентарный номер</th>
					<th>Категория</th>
					<th colspan="2">Действия</th>
                </tr>
            </tbody>
			
      		<tbody id="pd_list">
            </tbody>
        </table>
	</div>
</body>

</html>
<?php
	echo '<script type="text/javascript"> get_pd_list(); </script>';
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