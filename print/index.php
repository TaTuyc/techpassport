<?php
include '../php/action.php';
include '../php/fillprint.php';
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
	<title>Паспорт.</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
    <script type="text/javascript">
		
		function get_hw_item(id_hw) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'hw_id',
					ID_hw: id_hw},
				dataType: "json",
				success: function(data){
					var buff;
					for (i = 0; i < 5; i++) {
						if (data[i] == 'null') {
							data[i] = '';
						}
					}
					if (data[4] == '0') {
						if (data[0] == 'Системная плата') {
							$('#mb1').html(data[1]);
							$('#mb3').html(data[3]);
						} else if (data[0] == 'Оперативная память') {
							$('#ram1').html(data[1]);
							$('#ram2').html(data[2]);
							$('#ram3').html(data[3]);
						} else if (data[0] == 'ЦП') {
							$('#cpu1').html(data[1]);
							$('#cpu2').html(data[2]);
							$('#cpu3').html(data[3]);
						}
						// размещаем данные в зависимости от категории
					} else if (data[4] == '1') {
						buff = document.getElementById('dynamic_stor').innerHTML;
						$('#dynamic_stor').html(
							buff + '<tr><td>' + data[0] + '</td><td colspan="2">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2">' + data[3] + '</td></tr>');
					} else if (data[4] == '2') {
						buff = document.getElementById('dynamic_disp').innerHTML;
						$('#dynamic_disp').html(
							buff + '<tr><td>' + data[0] + '</td><td colspan="2">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2">' + data[3] + '</td></tr>');
					} else if (data[4] == '3') {
						buff = document.getElementById('dynamic_mult').innerHTML;
						$('#dynamic_mult').html(
							buff + '<tr><td>' + data[0] + '</td><td colspan="2">' + data[1] + '</td><td></td><td colspan="2">' + data[3] + '</td></tr>');
					} else if (data[4] == '4') {
						buff = document.getElementById('dynamic_net').innerHTML;
						$('#dynamic_net').html(
							buff + '<tr><td>' + data[0] + '</td><td colspan="2">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2">' + data[3] + '</td></tr>');
					} else if (data[4] == '5') {
						buff = document.getElementById('case').innerHTML;
						$('#case').html(
							'<tr><th scope="row">Корпус</th><td colspan="2">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2">' + data[3] + '</td></tr>');
					}
					//console.log(data[0] + '   ' + data[1] + '   ' + data[2] + '   ' + data[3] + '   ' + data[4]);
				}
			});
		}
		
		function get_pd_item(id_pd) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'pd_id',
					ID_pd: id_pd},
				dataType: "json",
				success: function(data){
					var buff;
					for (i = 0; i < 5; i++) {
						if (data[i] == 'null') {
							data[i] = '';
						}
					}
					if (data[4] == '2') {
						buff = document.getElementById('dynamic_disp').innerHTML;
						//console.log('периферия////');
						$('#dynamic_disp').html(
							buff + '<tr><td>' + data[0] + '</td><td colspan="2">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2">' + data[3] + '</td></tr>');	
					} else if (data[4] == '6') {
						buff = document.getElementById('printer').innerHTML;
						$('#printer').html(
							'<tr><th scope="row">Принтер</th><td colspan="2">' + data[1] + '</td><th style="color: blue">инв.номер:</th><td colspan="2">' + data[3] + '</td></tr>');
					} else if (data[4] == '7') {
						buff = document.getElementById('dynamic_per').innerHTML;
						$('#dynamic_per').html(
							buff + '<tr><td>' + data[0] + '</td><td>' + data[1] + '</td><td>' + data[2] + '</td><th style="color: blue">инв.номер:</th><td colspan="2">' + 
							data[3] + '</td></tr>');
					}
					//console.log(data[0] + '   ' + data[1] + '   ' + data[2] + '   ' + data[3] + '   ' + data[4]);
				}
			});
			return;
		}
		
		function get_sw_item(id_sw) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'sw_id',
					ID_sw: id_sw},
				dataType: "json",
				success: function(data){
					var buff;
					for (i = 0; i < 5; i++) {
						if (data[i] == 'null') {
							data[i] = '';
						}
					}
					buff = document.getElementById('dynamic_sw').innerHTML;
					//console.log('периферия////');
					$('#dynamic_sw').html(
						buff + '<tr><td>' + data[0] + '</td><td>' + data[1] + '</td><td>' + data[2] + '</td><td>' + data[3] + '</td><td>' + data[4] + '</td><td>' + data[5] + '</td></tr>');
					//console.log(data[0] + '   ' + data[1] + '   ' + data[2] + '   ' + data[3] + '   ' + data[4]);
				}
			});
			return;
		}
		
		function get_decorative_rows() {
			components = ['dynamic_stor', 'dynamic_disp', 'dynamic_mult'];
			components.forEach(function(item, i, data){
				if (document.getElementById(item).textContent.trim() == '') {
					$('#' + item).html(
						'<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>');
				}
			});
			if (document.getElementById('case').textContent.trim() == '') {
				$('#case').html('<tr><th scope="row">Корпус</th><td colspan="2">&nbsp;</td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>');
			}
			if (document.getElementById('printer').textContent.trim() == '') {
				$('#printer').html('<tr><th scope="row">Принтер</th><td colspan="2">&nbsp;</td><th style="color: blue">инв.номер:</th><td colspan="2">&nbsp;</td></tr>');
			}
		}
		
		function get_hw_array(id_pc) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'hw',
					ID_pc: id_pc},
				dataType: "json",
				success: function(data){
					data.forEach(function(item, i, data){
						get_hw_item(item);
					});
				}
			});
			return;
		}
		
		function get_pd_array(id_pc) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'pd',
					ID_pc: id_pc},
				dataType: "json",
				success: function(data){
					data.forEach(function(item, i, data){
						get_pd_item(item);
					});
				}
			});
		}
		
		function get_sw_array(id_pc) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'sw',
					ID_pc: id_pc},
				dataType: "json",
				success: function(data){
					data.forEach(function(item, i, data){
						get_sw_item(item);
					});
				}
			});
		}
		
		$(document).ready(function(){
			console.log("миииу  ");
		});
	</script>
	<?php
        $pdo = connect_db();
        $ID_pc = 29;
    ?>
	<style>
		* {
			padding: 5px !important;
			font-size: 10pt !important;
		}
		table {
			border-collapse: collapse;
		}
		td {
			vertical-align: middle !important;
		}
	</style>
</head>

<!--Тушка-->

<body>
    <div class="table-responsive text-center">
        <table id="pasport" class="table table-bordered table-hover" style="page-break-inside: avoid">
			<thead>
				<tr>
					<th colspan="6">Паспорт Автоматизированного рабочего места</th>
				</tr>
			</thead>
			<thead>
				<tr>
					<th colspan="6" style="background-color: #8FBC8F">Описание компьютера</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<th scope="row">Дата производства:
					</th>
					<td colspan="2">
						<?php
                            echo get_data_via_id($pdo, $ID_pc, 'Computer', 'manufacture_date');
                        ?>
					</td>
					<th scope="row">Способ производства:</th>
					<td colspan="2">
						<?php
                            echo get_data_via_2id($pdo, $ID_pc, 'Computer', 'manufacture_method', 'Manufacture_method', 'ID_mm', 'method');
                        ?>
					</td>
				</tr>
                <!--Головушка-->
				<tr>
					<th scope="row">Дата постановки на баланс по информации бухгалтерии учреждения</th>
					<td colspan="2">
						<?php
                            echo get_data_via_id($pdo, $ID_pc, 'Computer', 'bookkeeping_balance_sheet');
                        ?>
					</td>
					<th scope="row">№ и дата документа постановки на баланс</th>
					<td colspan>
                        <?php
                            echo get_data_via_id($pdo, $ID_pc, 'Computer', 'doc_balance_num');
                        ?>
					</td>
					<td colspan>
						<?php
                            echo get_data_via_id($pdo, $ID_pc, 'Computer', 'doc_balance_date');
                        ?>
					</td>
				</tr>

				<tr>
					<th scope="row">Имя рабочей станции</th>
					<td colspan="2">
						<?php
                            echo get_data_via_id($pdo, $ID_pc, 'Computer', 'pc_name');
                        ?>
					</td>
					<th scope="row">Место установки</th>
					<td>
						<?php
                            echo get_data_via_2id($pdo, $ID_pc, 'Computer', 'installation_site_office', 'Office', 'ID_office', 'office');
                        ?>
					</td>
					<td>
						<?php
                            echo get_data_via_2id($pdo, $ID_pc, 'Computer', 'installation_site_position', 'Worker', 'ID_worker', 'position');
                        ?>
					</td>
				</tr>

				<tr>
					<th scope="row">ИНВ.НОМЕР:</th>
					<td colspan="2">
						<?php
                            echo get_data_via_id($pdo, $ID_pc, 'Computer', 'inventory_number');
                        ?>
					</td>
					<th scope="row">Ответственный за эксплуатацию:</th>
					<td colspan="2">
						<?php
                            echo get_data_via_2id($pdo, $ID_pc, 'Computer', 'responsible', 'Worker', 'ID_worker', 'full_name');
                        ?>
					</td>
				</tr>
			</tbody>

			<thead>
				<tr>
					<th colspan="6" style="background-color: #8FBC8F">Аппаратное обеспечение</th>
				</tr>
			</thead>
			<thead>
				<tr>
					<th style="background-color: #8FBC8F">Наименование</th>
					<th colspan="2" style="background-color: #8FBC8F">Описание</th>
					<th style="background-color: #8FBC8F">Характеристика</th>
					<th colspan="2" style="background-color: #8FBC8F">Примечание</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<th scope="row">Системная плата</th>
					<td colspan="2" id="mb1">
					</td>
					<td></td>
					<td colspan="2" id="mb3">
					</td>
				</tr>

				<tr>
					<th scope="row">Оперативная память</th>
					<td colspan="2" id="ram1">
					</td>
					<td id="ram2">
					</td>
					<td colspan="2" id="ram3">
					</td>
				</tr>

				<tr>
					<th scope="row">ЦП</th>
					<td colspan="2" id="cpu1">
					</td>
					<td id="cpu2">
					</td>
					<td colspan="2" id="cpu3">
					</td>
				</tr>
			</tbody>

			<tbody id="storage">

				<tr>
					<th colspan="6" style="background-color: #D3D3D3">Хранение данных:</th>
				</tr>

				<tbody id="dynamic_stor">
				</tbody>

			</tbody>

			<tbody id="display">
				<tr>
					<th colspan="6" style="background-color: #D3D3D3">Отображение:</th>
				</tr>

				<tbody id="dynamic_disp">
				</tbody>

			</tbody>

			<tbody id="multi">
				<tr>
					<th colspan="6" style="background-color: #D3D3D3">Мультимедиа:</th>
				</tr>

				<tbody id="dynamic_mult">
				</tbody>

			</tbody>

			<tr>
				<th colspan="6" style="background-color: #D3D3D3">Сеть:</th>
			</tr>
			
			<tbody id="dynamic_net">
			</tbody>

			<tbody>
				
				<tbody id="case">
				</tbody>

				<tbody id="printer" style="border-top: 0px">
				</tbody>

				<tbody id="perepherals">
					<tr>
						<th colspan="6" style="background-color: #D3D3D3">Другие периферийные устройства:</th>
					</tr>

					<tbody id="dynamic_per">
					</tbody>
				</tbody>
			</tbody>

			<thead>
				<tr>
					<th colspan="6" style="background-color: #8FBC8F">Программное обеспечение</th>
				</tr>
			</thead>

			<tbody id="sw">
				<tr>
					<th scope="row">Наименование продукта</th>
					<th scope="row">Тип лицензии</th>
					<th scope="row">Номер лицензии</th>
					<th scope="row">Ключ продукта</th>
					<th scope="row">Версия</th>
					<th scope="row">Примечание</th>
				</tr>

				<tbody id="dynamic_sw">
				</tbody>
			</tbody>

			</tbody>

		</table>
    </div>
	<?php
		echo
		'<script type="text/javascript">
			get_hw_array(' . $ID_pc . ');
			get_pd_array(' . $ID_pc . ');
			get_sw_array(' . $ID_pc . ');
			setTimeout(function() {get_decorative_rows();}, 1000);
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