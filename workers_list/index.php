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
	<title>Список сотрудников</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
    <script type="text/javascript">
		function get_delete_confirmation(id) {
			var answer = confirm("Удалить сотрудника из базы данных?");
			var is_responsible = false;
			if (answer) {
				var x = $.ajax({
					type: 'POST',
					url: '../php/ajaxData.php',
					async: false,
					data: {
						delete_worker: id},
					dataType: "json",
					success: function(data){
						if (data != null) {
							alert(data);
							is_responsible = true;
							return;
						}
					}
				}).responseText;
				if (!is_responsible) {
					document.location.reload(true);
				}
			}
		}
		
		function is_worker_exist(id, call_type) {
			var answer = false;
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					is_worker_exist: id},
				dataType: "json",
				success: function(data){
					answer = data;
				}
			}).responseText;
			if (!answer) {
				alert("Сотрудник удалён из базы данных, страница будет перезагружена.");
				document.location.reload(true);
			} else {
				if (call_type == 'edit') {
					document.location.href = '../worker/index.php?id=' + id;
				}
			}
		}
		
        function get_workers_list() {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					get_workers_list: ''},
				dataType: "json",
				success: function(data){
					rows = data.length / 5;
					dl = data.length;
					for (i = 0; i < dl; i++) {
						if (data[i] == null) {
							data[i] = '';
						}
					}
                    buff = '';
					for (i = 0; i < rows; i++) {
						buff +=
                        '<tr>' +
                            '<td>' + data[i * 5] + '</td>' +
                            '<td>' + data[i * 5 + 1] + '</td>' +
                            '<td>' + data[i * 5 + 2] + '</td>' +
							'<td>' + data[i * 5 + 3] + '</td>' +
							'<td><button type="button" class="del btn btn-danger" name="delbtn_worker" onclick="get_delete_confirmation(' + data[i * 5 + 4] + ')">Удалить</button></td>' +
							'<td><button type="submit" class="chng btn btn-primary" name="editbtn_worker" style="width: 100%" onclick="is_worker_exist(' + data[i * 5 + 4] + ', \'edit\')">Изменить</button></td>' +
                        '</tr>';
                    }
					$('#workers').html(buff);
				}
			}).responseText;
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
		<h1>Список сотрудников</h1>
        <table id="new_worker" class="table table-bordered table-hover" style="margin-top: 20px">
			<tbody style="background-color: #D3D3D3">
				<tr>
					<th>ФИО сотрудника</th>
					<th>Должность</th>
					<th>Кабинет</th>
					<th>Статус</th>
					<th colspan="2">Действия</th>
				</tr>
			</tbody>
			<tbody id="workers">
			</tbody>
        </table>
	</div>
</body>

</html>
<?php
	echo '<script type="text/javascript"> get_workers_list(); </script>';
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