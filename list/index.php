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
	<title>Список паспортов.</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/setTable.js"></script>
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
	<script type="text/javascript">
		
		function get_delete_confirmation(id_pc) {
			var answer = confirm("Удалить паспорт?");
			if (answer) {
				var x = $.ajax({
					type: 'POST',
					url: '../php/ajaxData.php',
					async: false,
					data: {
						delete_passport: id_pc},
					dataType: "json",
					success: function(){}
				}).responseText;
				document.location.reload(true);
			}
		}
		
		function is_pc_exist(id_pc) {
			var answer = false;
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					is_pc_exist: id_pc},
				dataType: "json",
				success: function(data){
					answer = data;
				}
			}).responseText;
			if (!answer) {
				alert("Паспорт удалён, страница будет перезагружена.");
				document.location.reload(true);
			} else {
				document.location.href = '../passport/index.php?id=' + id_pc;
			}
		}
		
		function get_history(id_pc) {
			var answer = '';
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					get_history: id_pc},
				dataType: "json",
				success: function(data){
					data_size = data.length;
					for (i = 0; i < data_size; i++) {
						if (data[i] == null) {
							data[i] = '';
						}
					}
					data_size = data_size / 3;
					for (i = 0; i < data_size; i++) {
						answer += data[i * 3] + '\t' + data[i * 3 + 1] + '\t' + data[i * 3 + 2] + '\n';	
					}
					alert(answer);
				}
			}).responseText;
		}
		
		function get_portion(id_page) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'page_id',
					ID_page: id_page},
				dataType: "json",
				success: function(data){
					data_size = data.length;
					for (i = 0; i < data_size; i++) {
						if (data[i] == null) {
							data[i] = '';
						}
					}
					data_size = data_size / 4;
					var buff = '';
					$('#pas_list').html('');
					for (i = 0; i < data_size; i++) {
						//buff = document.getElementById('pas_list').innerHTML;
						buff += 
							'<tr><td>' + data[i * 4] + '</td><td>' + data[i * 4 + 1] + '</td><td>' + data[i * 4 + 2] + '</td>' + 
							'<td><button type="button" class="del btn btn-danger" onclick="get_delete_confirmation(' + data[i * 4 + 3] + ')">Удалить</button></td>' +
							'<td><button type="button" class="chng btn btn-primary" name="editbtn_' + data[i * 4 + 3] + '" onclick="is_pc_exist(' + data[i * 4 + 3] + ')">Изменить</button></td>' +
							'<td><button href="fix_file.php" type="button" class="fix btn btn-primary" name="repbtn_' + data[i * 4 + 3] + '">Ремонт</button></td>' +
							'<td><button href="export_file.php" type="button" class="exp btn btn-success" name="expbtn_' + data[i * 4 + 3] + '">Экспорт</button></td>' +
							'<td><button type="button" class="chng btn btn-primary" onclick="get_history(' + data[i * 4 + 3] + ')">История</button></td>';
					}
					$('#pas_list').html(buff);
				}
			});
			
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'page_list',
					ID_page: id_page},
				dataType: "json",
				success: function(data){
					var page_size = data[1];
					var page_this = id_page;
					var num = (data[0] - data[0] % page_size) / page_size + 1;
					if (data[0] % page_size != 0) {
						num++;
					}
					var buff = "";
					if (page_this != 1) {
						buff = '<a onclick="get_portion(' + (page_this - 1) + ');">&nbsp;Предыдущая&nbsp;</a>';
					}
					for (i = 1; i < num; i++) {
						buff += '<a onclick="get_portion(' + i + ');">&nbsp;' + i + '</a>';
					}
					if (page_this != num - 1) {
						buff += '<a onclick="get_portion(' + (page_this + 1) + ');">&nbsp;&nbsp;Следующая</a>';
					}
					$('#num_pages').html('<tr><td colspan="7">' + buff + '</td></tr>');
				}
			});
		}
		
		function set_portion_size(new_size) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					update_cookie: 'portion_size',
					portion_size: new_size},
				dataType: "json",
				success: function(data) {
					get_portion(1);
				}
			});
		}
	</script>
	<?php
		$pdo = connect_db();
	?>
</head>

<body>
	<div style="display: flex; width: 80%; margin: auto">
		<div style="text-align: left; width: 50%">
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
					<th colspan="8" style="background-color: #8FBC8F">Список паспортов.</th>
				</tr>
			</thead>

			<tbody>
				<tr style="background-color: #D3D3D3">
					<th scope="row" style="width: 20%">Имя рабочей станции</th>
					<th scope="row" style="width: 25%">Кабинет</th>
					<th scope="row" style="width: 25%">Инвентарный номер</th>
					<th scope="row" colspan="5">Действия</th>
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
