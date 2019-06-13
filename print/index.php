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
<html id="html">
<head>
	<meta charset="utf-8">
	<title>Паспорт.</title>
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/xepOnline.jqPlugin.js"></script>

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
							buff + '<tr><td class="nameright">' + data[0] + '</td><td colspan="2" class="nameleft">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2" class="notes">' + data[3] + '</td></tr>');
					} else if (data[4] == '2') {
						buff = document.getElementById('dynamic_disp').innerHTML;
						$('#dynamic_disp').html(
							buff + '<tr><td class="nameright">' + data[0] + '</td><td colspan="2" class="nameleft">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2" class="notes">' + data[3] + '</td></tr>');
					} else if (data[4] == '3') {
						buff = document.getElementById('dynamic_mult').innerHTML;
						$('#dynamic_mult').html(
							buff + '<tr><td class="nameright">' + data[0] + '</td><td colspan="2" class="nameleft">' + data[1] + '</td><td></td><td colspan="2" class="notes">' + data[3] + '</td></tr>');
					} else if (data[4] == '4') {
						buff = document.getElementById('dynamic_net').innerHTML;
						$('#dynamic_net').html(
							buff + '<tr><td class="nameright">' + data[0] + '</td><td colspan="2" class="nameleft">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2" class="notes">' + data[3] + '</td></tr>');
					} else if (data[4] == '5') {
						buff = document.getElementById('case').innerHTML;
						$('#case').html(
							'<tr><th style="text-align: left !important">Корпус</th><td colspan="2" class="nameleft">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2" class="notes">' + data[3] + '</td></tr>');
					}
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
						$('#dynamic_disp').html(
							buff + '<tr><td class="nameright">' + data[0] + '</td><td colspan="2" class="nameleft">' + data[1] + '</td><td>' + data[2] + '</td><td colspan="2" class="notes">' + data[3] + '</td></tr>');
					} else if (data[4] == '6') {
						buff = document.getElementById('printer').innerHTML;
						$('#printer').html(
							'<tr><th style="text-align: left !important">Принтер</th><td colspan="2" class="nameleft">' + data[1] + '</td><td></td><th style="color: blue">инв.номер:</th><th>' + data[3] + '</th></tr>');
					} else if (data[4] == '7') {
						buff = document.getElementById('dynamic_per').innerHTML;
						$('#dynamic_per').html(
							buff + '<tr><td class="nameleft">' + data[0] + '</td><td class="nameleft" colspan="2">' + data[1] + '</td><td>' + data[2] + '</td><th style="color: blue">инв.номер:</th><th>' +
							data[3] + '</th></tr>');
					}
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
						buff + '<tr><td class="nameleft">' + data[0] + '</td><td>' + data[1] + '</td><td>' + data[2] + '</td><td>' + data[3] + '</td><td>' + data[4] + '</td><td>' + data[5] + '</td></tr>');
					}
			});
			return;
		}

		function get_decorative_rows() {
			components = ['dynamic_stor', 'dynamic_disp', 'dynamic_mult', 'dynamic_net', 'dynamic_per', 'dynamic_sw'];
			components.forEach(function(item, i, data){
				if (document.getElementById(item).textContent.trim() == '') {
					$('#' + item).html(
						'<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>');
				}
			});
			if (document.getElementById('case').textContent.trim() == '') {
				$('#case').html('<tr><th class="nameleft">Корпус</th><td colspan="2">&nbsp;</td><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>');
			}
			if (document.getElementById('printer').textContent.trim() == '') {
				$('#printer').html('<tr><th class="nameleft">Принтер</th><td colspan="2">&nbsp;</td><th style="color: blue">инв.номер:</th><th colspan="2">&nbsp;</th></tr>');
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
		
		function reform_for_pdf() {
			html = '<!DOCTYPE html><html>' + document.getElementById("html").innerHTML + '</html>';
			html = deleteScriptTag(html);
			html = deleteTbodyTag(html);
			html = deleteSpaces(html);
			html = deleteInputTag(html);
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					print_passport: html},
				dataType: "json"
			}).responseText;
			
			window.location.replace('./goprint.php');
			console.log(html);
		}
	</script>
	<?php
        $pdo = connect_db();
        $ID_pc = $_GET['id'];
    ?>
	<style>
		* {
            margin: 0px !important;
			padding-left: 3px !important;
            padding-right: 3px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
			font-size: 10pt !important;
		}
        table {
            padding: 0px !important;
        }
		table, table tr, table th, table tr td, table th td {
			border-collapse: collapse;
			border: 1px solid #000 !important;
			line-height: 100%;
		}
		table tr th {
			vertical-align: middle !important;
		}
		table tr td {
			font-size: 9pt !important;
		}
		td {
			vertical-align: middle !important;
		}
		.notes {
			color: blue;
			font-size: 8pt !important;
			text-align: left !important;
		}
		.nameleft {
			text-align: left !important;
		}
		.nameright {
			text-align: right !important;
		}
        .bhi {
            border: hidden !important;
        }
	</style>
</head>
<body>
    <div class="table-responsive text-center" style="margin-left: 40px !important; margin-right: 40px !important">
		<table class="bhi">
            <tr class="bhi">
                <td class="bhi" style="width: 25%"><img src="../img/logo.png" alt="Лого" width="115px" height="143px">
                <td class="bhi" style="width: 50%; font-size: 10pt !important">Управление культуры Комитета по социальной политике и культуре Администрации г. Иркутска Муниципальное учреждение культуры «Гуманитарный центр – библиотека имени семьи Полевых» 
                <td class="bhi" style="width: 25%"><img src="../img/pc.png" alt="ПК" width="158px" height="119px">
            </tr>
            <tr>
                <td colspan="3" class="bhi">
                    ПАСПОРТ АВТОМАТИЗИРОВАННОГО РАБОЧЕГО МЕСТА
                </td>
            </tr>
        </table>
		<table id="pasport" class="table table-bordered table-hover" style="page-break-inside: avoid; font-family: Arial; font-size: 10pt">
			<tr>
				<th colspan="6" style="background-color: #ccffcc; font-size: 10pt">Описание компьютера</th>
			</tr>

			<tr>
				<td class="nameleft">Дата производства:
				</td>
				<th colspan="2">
					<?php
						echo get_data_via_id($pdo, $ID_pc, 'Computer', 'manufacture_date');
					?>
				</th>
				<td class="nameleft">Способ производства:</td>
				<th colspan="2">
					<?php
						echo get_data_via_2id($pdo, $ID_pc, 'Computer', 'manufacture_method', 'Manufacture_method', 'ID_mm', 'method');
					?>
				</th>
			</tr>
			<tr>
				<td style="width: 170px" class="nameleft">Дата постановки на баланс по информации бухгалтерии учреждения</td>
				<th colspan="2">
					<?php
						echo get_formatted_date(get_data_via_id($pdo, $ID_pc, 'Computer', 'bookkeeping_balance_sheet'));
					?>
				</th>
				<td class="nameleft">№ и дата документа постановки на баланс</td>
				<th colspan>
					<?php
						echo get_data_via_id($pdo, $ID_pc, 'Computer', 'doc_balance_num');
					?>
				</th>
				<th colspan>
					<?php
						echo get_formatted_date(get_data_via_id($pdo, $ID_pc, 'Computer', 'doc_balance_date'));
					?>
				</th>
			</tr>

			<tr>
				<td class="nameleft">Имя рабочей станции</td>
				<th colspan="2">
					<?php
						echo get_data_via_id($pdo, $ID_pc, 'Computer', 'pc_name');
					?>
				</th>
				<td class="nameleft">Место установки</td>
				<th colspan="2" style="min-width: 250px">
					<?php
						echo get_data_via_2id($pdo, $ID_pc, 'Computer', 'installation_site_office', 'Office', 'ID_office', 'office');
						echo ', ';
						echo get_data_via_2id($pdo, $ID_pc, 'Computer', 'installation_site_position', 'Position', 'ID_pos', 'position');
					?>
				</th>
			</tr>

			<tr>
				<td class="nameleft">ИНВ.НОМЕР:</td>
				<th colspan="2">
					<?php
						echo get_data_via_id($pdo, $ID_pc, 'Computer', 'inventory_number');
					?>
				</th>
				<td class="nameleft">Ответственный за эксплуатацию:</td>
				<td colspan="2">
					<?php
						echo get_data_via_2id($pdo, $ID_pc, 'Computer', 'responsible', 'Worker', 'ID_worker', 'full_name');
					?>
				</td>
			</tr>

			<tr>
				<th colspan="6" style="background-color: #ccffcc; font-size: 10pt">Аппаратное обеспечение</th>
			</tr>
			<tr>
				<th style="background-color: #ccffcc; font-size: 10pt">Наименование</th>
				<th colspan="2" style="background-color: #ccffcc; font-size: 10pt">Описание</th>
				<th style="background-color: #ccffcc; font-size: 10pt">Характеристика</th>
				<th colspan="2" style="background-color: #ccffcc; font-size: 10pt">Примечания</th>
			</tr>

			<tr>
				<th class="nameleft">Системная плата</th>
				<td colspan="2" id="mb1" class="nameleft">
				</td>
				<td></td>
				<td colspan="2" id="mb3" class="notes">
				</td>
			</tr>

			<tr>
				<th class="nameleft">Оперативная память</th>
				<td colspan="2" id="ram1" class="nameleft">
				</td>
				<td id="ram2">
				</td>
				<td colspan="2" id="ram3" class="notes">
				</td>
			</tr>

			<tr>
				<th class="nameleft">ЦП</th>
				<td colspan="2" id="cpu1" class="nameleft">
				</td>
				<td id="cpu2">
				</td>
				<td colspan="2" id="cpu3" class="notes">
				</td>
			</tr>

			<tr>
				<th colspan="6" style="background-color: #c0c0c0; font-size: 10pt; text-align: left !important">Хранение данных:</th>
			</tr>

			<tbody id="dynamic_stor">
			</tbody>

			<tr>
				<th colspan="6" style="background-color: #c0c0c0; font-size: 10pt; text-align: left !important">Отображение:</th>
			</tr>

			<tbody id="dynamic_disp">
			</tbody>

			<tr>
				<th colspan="6" style="background-color: #c0c0c0; font-size: 10pt; text-align: left !important">Мультимедиа:</th>
			</tr>

			<tbody id="dynamic_mult">
			</tbody>

			<tr>
				<th colspan="6" style="background-color: #c0c0c0; font-size: 10pt; text-align: left !important">Сеть:</th>
			</tr>

			<tbody id="dynamic_net">
			</tbody>

			<tbody id="case">
			</tbody>

			<tbody id="printer" style="border-top: 0px">
			</tbody>

			<tr>
				<th colspan="6" style="background-color: #c0c0c0; font-size: 10pt; text-align: left !important">Другие периферийные устройства:</th>
			</tr>

			<tbody id="dynamic_per">
			</tbody>

			<tr>
				<th colspan="6" style="background-color: #ccffcc; font-size: 10pt">Программное обеспечение</th>
			</tr>

			<tr>
				<th>Наименование продукта</th>
				<th>Тип лицензии</th>
				<th>Номер лицензии</th>
				<th>Ключ продукта</th>
				<th>Версия</th>
				<th>Прим.</th>
			</tr>

			<tbody id="dynamic_sw">
			</tbody>
			
			<tr>
                <td colspan="2" class="nameleft" style="border-left: hidden !important; border-bottom: hidden !important; border-right: hidden !important">Заместитель директора по основной работе:</td>
                <td style="border-left: hidden !important"></td>
                <td colspan="2" class="nameleft" style="border-left: hidden !important; border-bottom: hidden !important; border-right: hidden !important">Заведующий отделом автоматизации:</td>
                <td style="border-right: hidden !important; border-left: hidden !important"></td>
            </tr>
            <tr>
                <td style="border-left: hidden !important; border-top: hidden !important">
                    <?php
						echo get_workers_name($pdo, 'Заместитель директора по основной работе');
					?>
				</td>
                <td style="border-top: hidden !important"></td><td style="border: hidden !important"></td>
                <td colspan="2" style="border-left: hidden !important; border-top: hidden !important; border-right: hidden !important">
                    <?php
						echo get_workers_name($pdo, 'Заведующий отделом автоматизации');
					?>
				</td>
                <td style="border-top: hidden !important; border-right: hidden !important"></td>
            </tr>
            <tr>
                <td style="border-left: hidden !important; border-bottom: hidden !important; border-right: hidden !important">ФИО</td>
                <td style="border-left: hidden !important; border-bottom: hidden !important; border-right: hidden !important">Подпись</td>
                <td style="border: hidden !important"></td>
                <td colspan="2" style="border-left: hidden !important; border-bottom: hidden !important; border-right: hidden !important">ФИО</td>
                <td style="border-left: hidden !important; border-bottom: hidden !important; border-right: hidden !important">Подпись</td>
            </tr>
		</table>
		<input type="submit" class="btn btn-primary" value="Сохранить в PDF"  onclick="reform_for_pdf()">
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
