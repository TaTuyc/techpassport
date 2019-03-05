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
	<title>Периферийное устройство</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<style>
		table.table th {
			text-align: left;
		}
	</style>
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
					get_pd_data: id},
				dataType: "json",
				success: function(data){
					document.getElementById('category').value = data[0];
					$('#category').trigger('change');
					document.getElementById('pd_name').value = data[1];
					$('#pd_name').trigger('change');
					document.getElementById('pd_model').value = data[2];
					$('#pd_model').trigger('change');
					document.getElementById('feature').value = data[3];
					document.getElementById('pd_inv_num').value = data[4];
				}
			}).responseText;
			document.getElementById('save_btn').setAttribute('name', 'update_pd');
			document.getElementById('pd_header').innerHTML = 'Редактирование данных о периферийном устройстве';
		}
		
		function eventChange(parent_id, call_type) {
			var parent_var = $('#' + parent_id).val();
			if (parent_var != "") {
				var wow = $.ajax({
					type: 'POST',
					url: '../php/ajaxData.php',
					async: false,
					data: {
						is_call: call_type,
						parent: parent_var
					}, success: function(html){
						if (call_type == '1_pd') {
							$('#pd_model').html(html);
							eventChange('pd_model', 'f_pd');
						} else if (call_type == 'f_pd') {
							$('#feature').html(html);
						} else if (call_type == 'pd_cat') {
							$('#pd_name').html(html);
							eventChange('pd_name', '1_pd');
							eventChange('pd_model', 'f_pd');
						}
					}
				}).responseText;
			}else{
				if (call_type == '1_pd') {
					$('#pd_model').html('<option value="">Выберите описание</option>');
					$('#feature').html('<option value="">Значение</option>');
				} else if (call_type == 'f_pd') {
					$('#feature').html('<option value="">Значение</option>');
				} else if (call_type == 'pd_cat') {
					$('#pd_name').html('<option value="">Выберите тип устройства</option>');
					$('#pd_model').html('<option value="">Выберите описание</option>');
					$('#feature').html('<option value="">Значение</option>');
				}
			}
		}
		
		$(document).on('change', function(e) {
			var parent_id = e.target.id;
			if (parent_id == "") {
				return;
			}
			var parent_var = $('#' + parent_id).val();
			if (parent_id == 'pd_name') {
				eventChange(parent_id, '1_pd');
			} else if (parent_id == 'pd_model') {
				eventChange(parent_id, 'f_pd');
			} else if (parent_id == 'category') {
				var category = parent_var;
				if (category == '') {
					document.getElementById('feature_block').style.display = "table-row";
					document.getElementById('pd_name').removeAttribute('disabled');
					$('#pd_name').val('');
					$('#pd_name').trigger('change');
				} else if (category == 2) {
					document.getElementById('feature_block').style.display = "none";
					document.getElementById('pd_name').removeAttribute('disabled');
					$('#pd_name').val('');
					$('#pd_name').trigger('change');
				} else if (category == 6) {
					$('#pd_name').val('Принтер');
					document.getElementById('feature_block').style.display = "none";
					document.getElementById('pd_name').setAttribute('disabled', 'disabled');
				} else if (category == 7) {
					document.getElementById('feature_block').style.display = "table-row";
					document.getElementById('pd_name').removeAttribute('disabled');
					$('#pd_name').val('');
					$('#pd_name').trigger('change');
				}
				eventChange(parent_id, 'pd_cat');
			}
		});
		
		$(document).on('submit', 'form', function(e) {
			var form = this;
			if (document.getElementById('category').value == "") {
				e.preventDefault();
				alert("Укажите категорию устройства!");
			} else if (document.getElementById('pd_name').value == "" && document.getElementById('pd_name_manually').value == "") {
				e.preventDefault();
				alert("Укажите наименование устройства!");
			} else {
				if (document.getElementById('pd_model').value == "" && document.getElementById('pd_model_manually').value == "") {
					e.preventDefault();
					alert("Укажите описание устройства!");
				} else {
					form.submit();
				}
			}
		});
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
		
		<form id="form" action="../php/action.php" method="post">

			<table id="per_reg" class="table table-bordered table-hover " style="margin-top: 20px">
				<thead>
					<tr>
						<th id="pd_header" style="background-color: #8FBC8F; text-align: center !important" colspan="3">Регистрация периферийного устройства</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<th style="background-color: #D3D3D3">Категория</th>
						<td colspan="2">
							<select class="custom-select" name="category" id="category" style="width: 60%">
								<option value="">Выберите категорию
								<option value="2" title="Мониторы, ТВ-панели и др.">Устройства отображения
								<option value="6" title="Принтеры">Печатающие устройства
								<option value="7" title="Периферийные устройства с характеристикой">Другие периферийные устройства
							</select>
						</td>
					</tr>
					
					<tr>
						<th style="background-color: #D3D3D3">Наименование</th>
						<td style="width: 40%">
							<select class="custom-select" name="pd_name" id="pd_name">
								<option value="">Выберите тип устройства
								<?php
									get_db_list($pdo, 'Periphery', 'category', '7', 'pd_name');
								?>
							</select>
						</td>
						<td style="width: 35%">
							<input type="text" class="input-group-text" name="pd_name_manually" id="pd_name_manually" placeholder="Ручной ввод" maxlength="30">
						</td>
					</tr>

					<tr>
						<th style="background-color: #D3D3D3">Описание</th>
						<td style="width: 40%">
							<select class="custom-select" name="pd_model" id="pd_model">
								<option value="">Выберите описание
							</select>
						</td>
						<td style="width: 35%">
							<input type="text" class="input-group-text" name="pd_model_manually" id="pd_model_manually" placeholder="Ручной ввод" maxlength="100">
						</td>
					</tr>
					
					<tr id="feature_block">
						<th style="background-color: #D3D3D3">Характеристика</th>
						<td style="width: 40%">
							<select class="custom-select" name="feature" id="feature">
								<option value="">Значение
							</select>
						</td>
						<td style="width: 35%">
							<input type="text" class="input-group-text" name="feature_manually" id="feature_manually" placeholder="Ручной ввод" pattern="[0-9]{0,10}">
						</td>
					</tr>

					<tr>
						<th style="background-color: #D3D3D3">Инвентарный номер</th>
						<td colspan="2">
							<input type="text" class="input-group-text" name="pd_inv_num" id="pd_inv_num" placeholder="Номер" pattern="[0-9]{0,20}" style="width: 60%; margin: auto">
						</td>
					</tr>
				</tbody>
			</table>
			<input id="save_btn" type="submit" class="btn btn-primary" name="save_pd" value="Сохранить">
		</form>
	</div>
</body>

</html>
<?php
	if (isset($_GET['id'])) {
		$ID_pd = htmlspecialchars($_GET['id']);
		echo '<script type="text/javascript">
			get_old_page(' . $ID_pd . ');
			document.getElementById("form").setAttribute("action", "../php/action.php?id=' . $ID_pd .'");
		</script>';
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