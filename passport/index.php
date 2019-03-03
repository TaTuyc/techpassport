<?php
include_once '../php/action.php';
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
    <script type="text/javascript" src="../script/passport.js"></script>
	<?php
        $pdo = connect_db();
    ?>
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
		<form id="form" name="form" action="../php/action.php" method="post">
		<table id="pasport" class="table table-bordered table-hover ">
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
						<input type="date" class="custom-select" name="manufacture_date" id="manufacture_date">
					</td>
					<th scope="row">Способ производства:</th>
					<td colspan="2">
						<select class="custom-select" name="buying_method">
							<option value=""> Выберите способ
							<?php
                                get_db_list($pdo, 'Manufacture_method', 'method', '', '');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="buying_method_manually" placeholder="Ручной ввод" maxlength="50">
					</td>
				</tr>

				<tr>
					<th scope="row">Дата постановки на баланс по информации бухгалтерии учреждения</th>
					<td colspan="2">
						<input type="date" class="custom-select" name="balance_date_bookkeeping">
					</td>
					<th scope="row">№ и дата документа постановки на баланс</th>
					<td colspan>
						<input type="text" class="input-group-text" name="balance_num" placeholder="Введите номер" maxlength="20" pattern="[0-9]{0,20}">
					</td>
					<td colspan>
						<input type="date" class="custom-select" name="balance_date">
					</td>
				</tr>

				<tr>
					<th scope="row">Имя рабочей станции</th>
					<td colspan="2">
						<input type="text" class="input-group-text" name="pc_name" placeholder="Имя" required maxlength="20">
					</td>
					<th scope="row">Место установки</th>
					<td>
						<select class="custom-select" name="pc_place">
							<option value=""> Кабинет
							<?php
                                get_db_list($pdo, 'Office', 'office', '', '');
                            ?>
						</select>
					</td>
					<td>
						<select class="custom-select" name="position">
							<option value=""> Должность
							<?php
                                get_db_list($pdo, 'Worker', 'position', '', '');
                            ?>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">ИНВ.НОМЕР:</th>
					<td colspan="2">
						<input type="text" class="input-group-text" name="pc_inv_num" placeholder="Номер" maxlength="20" pattern="[0-9]{0,20}">
					</td>
					<th scope="row">Ответственный за эксплуатацию:</th>
					<td colspan="2">
						<select class="custom-select" name="responsible_person">
							<option value=""> Выберите ответственного
							<?php
                                get_actual_workers($pdo);
                            ?>
						</select>
                        <input type="text" class="input-group-text" name="responsible_person_manually" placeholder="Ручной ввод" maxlength="200">
						<p style="margin: 0; padding: 5px; font-weight: bold">С какого времени</p>
						<input type="date" class="custom-select" name="responsible_since" required>
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
					<td colspan="2">
						<select class="custom-select" name="mb_model" id="mb_model">
                            <option value=""> Выберите модель
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Системная плата', 'description');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="mb_model_manually" placeholder="Ручной ввод" maxlength="100">
					</td>
					<td></td>
					<td colspan="2" style="color: blue;">
						<select class="custom-select" name="mb_note" id="mb_note">
							<option value=""> Примечание
						</select>
						<input type="text" class="input-group-text" name="mb_note_manually" placeholder="Ручной ввод" maxlength="100">
					</td>
				</tr>

				<tr>
					<th scope="row">Оперативная память</th>
					<td colspan="2">
						<select class="custom-select" name="ram_type" id="ram_type">
                            <option value=""> Выберите тип
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Оперативная память', 'description');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="ram_type_manually" placeholder="Ручной ввод" maxlength="100">
					</td>
					<td>
						<select class="custom-select" name="ram_capacity" id="ram_capacity">
							<option value=""> Выберите объём							
						</select>
						<input type="text" class="input-group-text" name="ram_capacity_manually" placeholder="Ручной ввод" maxlength="10" pattern="[0-9]{0,10}">
					</td>
					<td colspan="2">
						<select class="custom-select" name="ram_note" id="ram_note">
							<option value=""> Примечание							
						</select>
						<input type="text" class="input-group-text" name="ram_note_manually" placeholder="Ручной ввод" maxlength="100">
					</td>
				</tr>

				<tr>
					<th scope="row">ЦП</th>
					<td colspan="2">
						<select class="custom-select" name="cpu_model" id="cpu_model">
							<option value=""> Выберите модель
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'ЦП', 'description');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="cpu_model_manually" placeholder="Ручной ввод" maxlength="100">
					</td>
					<td>
						<select class="custom-select" name="cpu_frequency" id= "cpu_frequency">
							<option value=""> Выберите частоту
						</select>
						<input type="text" class="input-group-text" name="cpu_frequency_manually" placeholder="Ручной ввод" maxlength="10" pattern="[0-9]{0,10}">
					</td>
					<td colspan="2" style="color: blue;">
						<select class="custom-select" name="cpu_note" id="cpu_note">
							<option value=""> Примечание
						</select>
						<input type="text" class="input-group-text" name="cpu_note_manually" placeholder="Ручной ввод" maxlength="100">
					</td>
				</tr>
			</tbody>

			<tbody id="storage">

				<tr>
					<th colspan="6" style="background-color: #D3D3D3">Хранение данных:</th>
				</tr>

				<tbody id="dynamic_stor">
					<tr>
						<td>
							<select class="custom-select" name="hw_name_c1" id="hw_name_c1"> //c1 - категория 1, устройства хранения данных
								<option value="">Выберите тип устройства
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'hw_name');
                                ?>
							</select>
							<input type="text" class="input-group-text" name="hw_name_manually_c1" placeholder="Ручной ввод" maxlength="30">
						</td>
						<td colspan="2">
							<select class="custom-select" name="description_c1" id="description_c1">
								<option value="">Выберите модель
							</select>
							<input type="text" class="input-group-text" name="description_manually_c1" placeholder="Ручной ввод" maxlength="100">
						</td>
						<td>
							<select class="custom-select" name="feature_c1" id="feature_c1">
								<option value="">Значение
							</select>
							<input type="text" class="input-group-text" name="feature_manually_c1" placeholder="Ручной ввод" maxlength="10" pattern="[0-9]{0,10}">
						</td>
						<td>
                            <select class="custom-select" name="hw_note_c1" id="hw_note_c1">
								<option value="">Примечание
							</select>
							<input type="text" class="input-group-text" name="hw_note_manually_c1" placeholder="Ручной ввод" maxlength="100">
						</td>
						<td>
							<button type="button" class="del btn btn-danger" name="del_btn_c1">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button id="add_btn_c1" type="button" class="add btn btn-success" name="add_btn_c1">Добавить</button>
						</td>
					</tr>
				</tbody>
				<script>
					new DynamicTable(document.getElementById("dynamic_stor"));
				</script>

			</tbody>

			<tbody id="display">
				<tr>
					<th colspan="6" style="background-color: #D3D3D3">Отображение:</th>
				</tr>

				<tbody id="dynamic_disp">
					<tr>
						<td>
							<select class="custom-select" name="hw_name_c2" id="hw_name_c2">
								<option value="">Выберите тип устройства
								<?php
                                    get_db_list_с2($pdo);
                                ?>
							</select>
							<input type="text" class="input-group-text" name="hw_name_manually_c2" placeholder="Ручной ввод" maxlength="30">
						</td>
						<td colspan="2">
							<select class="custom-select" name="description_c2" id="description_c2">
								<option value="">Выберите модель
							</select>
							<input type="text" class="input-group-text" name="description_manually_c2" placeholder="Ручной ввод" maxlength="100">
						</td>
						<td>
							<div id="div_feature_c2">
								<select class="custom-select" name="feature_c2" id="feature_c2">
									<option value="">Значение
								</select>
								<input type="text" class="input-group-text" id="feature_manually_c2" name="feature_manually_c2" placeholder="Ручной ввод" maxlength="10" pattern="[0-9]{0,10}">
							</div>
							<div id="div_pd_inv_num_c2" style="display: none">
								<p style="color: blue; margin: 0; padding: 0">инв.номер:</p>
								<input type="text" class="input-group-text" id="pd_inv_num_c2" name="pd_inv_num_c2" placeholder="Номер" maxlength="20" pattern="[0-9]{0,20}">
							</div>
							<button id="switch_btn_c2" type="button" class="btn btn-switch" name="switch_btn_c2">Переключить</button>
						</td>
						<td>
							<div id="div_hw_note_c2">
								<select class="custom-select" id="hw_note_c2" name="hw_note_c2">
									<option value="">Примечание
								</select>
								<input type="text" class="input-group-text" id="hw_note_manually_c2" name="hw_note_manually_c2" placeholder="Ручной ввод" maxlength="100">
							</div>
						</td>
						<td>
							<button type="button" class="del btn btn-danger" name="del_btn_c2">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button id="add_btn_c2" type="button" class="add btn btn-success" name="add_btn_c2">Добавить</button>
						</td>
					</tr>
				</tbody>
				<script>
					new DynamicTable(document.getElementById("dynamic_disp"));
				</script>

			</tbody>

			<tbody id="multi">
				<tr>
					<th colspan="6" style="background-color: #D3D3D3">Мультимедиа:</th>
				</tr>

				<tbody id="dynamic_mult">
					<tr>
						<td>
							<select class="custom-select" name="hw_name_c3" id="hw_name_c3">
								<option value="">Выберите тип устройства
								<?php
									get_db_list($pdo, 'Hardware', 'category', '3', 'hw_name');
								?>
							</select>
							<input type="text" class="input-group-text" name="hw_name_manually_c3" placeholder="Ручной ввод" maxlength="30">
						</td>
						<td colspan="2">
							<select class="custom-select" name="description_c3" id="description_c3">
								<option value="">Выберите модель
							</select>
							<input type="text" class="input-group-text" name="description_manually_c3" placeholder="Ручной ввод" maxlength="100">
						</td>
						<td></td>
						<td>
							<select class="custom-select" name="hw_note_c3" id="hw_note_c3">
								<option value="">Примечание
							</select>
							<input type="text" class="input-group-text" name="hw_note_manually_c3" placeholder="Ручной ввод" maxlength="100">
						</td>
						<td>
							<button type="button" class="del btn btn-danger" name="del_btn_c3">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button id="add_btn_c3" type="button" class="add btn btn-success" name="add_btn_c3">Добавить</button>
						</td>
					</tr>
				</tbody>
				<script>
					new DynamicTable(document.getElementById("dynamic_mult"));
				</script>

			</tbody>

			<tbody id="network">
				<tr>
					<th colspan="6" style="background-color: #D3D3D3">Сеть:</th>
				</tr>

				<tbody id="dynamic_net">
					<tr>
						<td>
							<select class="custom-select" name="hw_name_c4" id="hw_name_c4">
								<option value="">Выберите тип устройства
								<?php
									get_db_list($pdo, 'Hardware', 'category', '4', 'hw_name');
								?>
							</select>
							<input type="text" class="input-group-text" name="hw_name_manually_c4" placeholder="Ручной ввод" maxlength="30">
						</td>
						<td colspan="2">
							<select class="custom-select" name="description_c4" id="description_c4">
								<option value="">Выберите модель
							</select>
							<input type="text" class="input-group-text" name="description_manually_c4" placeholder="Ручной ввод" maxlength="100">
						</td>
						<td>
							<select class="custom-select" name="feature_c4" id="feature_c4">
								<option value="">Значение
							</select>
							<input type="text" class="input-group-text" name="feature_manually_c4" placeholder="Ручной ввод" maxlength="10" pattern="[0-9]{0,10}">
						</td>
						<td>
							<select class="custom-select" name="hw_note_c4" id="hw_note_c4">
								<option value="">Примечание
							</select>
							<input type="text" class="input-group-text" name="hw_note_manually_c4" placeholder="Ручной ввод" maxlength="100">
						</td>
						<td>
							<button type="button" class="del btn btn-danger" name="del_btn_c4">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button id="add_btn_c4" type="button" class="add btn btn-success" name="add_btn_c4">Добавить</button>
						</td>
					</tr>
				</tbody>
				<script>
					new DynamicTable(document.getElementById("dynamic_net"));
				</script>
			</tbody>

			<tbody>
				<tr>
					<th scope="row">Корпус</th>
					<td colspan="2">
						<select class="custom-select" name="description_c5" id="description_c5">
							<option value=""> Выберите тип
							<?php
								get_db_list($pdo, 'Hardware', 'category', '5', 'description');
							?>
						</select>
						<input type="text" class="input-group-text" name="description_manually_c5" placeholder="Ручной ввод" maxlength="100">
					</td>
					<td>
						<select class="custom-select" name="feature_c5" id="feature_c5">
							<option value=""> Значение
						</select>
						<input type="text" class="input-group-text" name="feature_manually_c5" placeholder="Ручной ввод" maxlength="10" pattern="[0-9]{0,10}">
					</td>
					<td colspan="2">
						<select class="custom-select" name="hw_note_c5" id="hw_note_c5">
							<option value=""> Примечание
						</select>
						<input type="text" class="input-group-text" name="hw_note_manually_c5" placeholder="Ручной ввод" maxlength="100">
					</td>
				</tr>

				<tr>
					<th scope="row">Принтер</th>
					<td colspan="2">
						<select class="custom-select" name="description_c6" id="description_c6">
							<option value=""> Выберите модель
							<?php
								get_db_list($pdo, 'Periphery', 'category', '6', 'pd_model');
							?>
						</select>
						<input type="text" class="input-group-text" name="description_manually_c6" placeholder="Ручной ввод" maxlength="100">
					</td>
					<th style="color: blue">инв.номер:</th>
					<td colspan="3">
						<input type="text" class="input-group-text" id="pd_inv_num_c6" name="pd_inv_num_c6" placeholder="Номер" maxlength="20" pattern="[0-9]{0,20}">
					</td>
				</tr>

				<tbody id="perepherals">
					<tr>
						<th colspan="6" style="background-color: #D3D3D3">Другие периферийные устройства:</th>
					</tr>

					<tbody id="dynamic_per">
						<tr>
							<td>
								<select class="custom-select" name="pd_name_c7" id="pd_name_c7">
									<option value="">Выберите тип устройства
									<?php
										get_db_list($pdo, 'Periphery', 'category', '7', 'pd_name');
									?>
								</select>
								<input type="text" class="input-group-text" name="pd_name_manually_c7" placeholder="Ручной ввод" maxlength="30">
							</td>
							<td colspan="2">
								<select class="custom-select" name="pd_model_c7" id="pd_model_c7">
									<option value="">Выберите описание
								</select>
								<input type="text" class="input-group-text" name="pd_model_manually_c7" placeholder="Ручной ввод" maxlength="100">
							</td>
							<td>
								<select class="custom-select" name="feature_c7" id="feature_c7">
									<option value="">Значение
								</select>
								<input type="text" class="input-group-text" name="feature_manually_c7" placeholder="Ручной ввод" maxlength="10" pattern="[0-9]{0,10}">
							</td>
							<td>
								<p style="color: blue; margin: 0; padding: 0">инв.номер:</p>
								<input type="text" class="input-group-text" id="pd_inv_num_c7" name="pd_inv_num_c7" placeholder="Номер" maxlength="20" pattern="[0-9]{0,20}">
							</td>
							<td>
								<button type="button" class="del btn btn-danger" name="del_btn_c7">Удалить</button>
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<button id="add_btn_c7" type="button" class="add btn btn-success" name="add_btn_c7">Добавить устройство</button>
							</td>
						</tr>
					</tbody>
					<script>
						new DynamicTable(document.getElementById("dynamic_per"));
					</script>
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
					<tr>
						<td>
							<select class="custom-select" name="sw_name" id="sw_name">
								<option value="">Выберите наименование
								<?php
									get_db_list($pdo, 'Software', 'sw_name', '', '');
								?>
							</select>
							<input type="text" class="input-group-text" name="sw_name_manually" placeholder="Ручной ввод" maxlength="50">
						</td>
						<td>
							<select class="custom-select" name="licence_type" id="licence_type">
								<option value="">Выберите тип лицензии
							</select>
							<input type="text" class="input-group-text" name="licence_type_manually" placeholder="Ручной ввод" maxlength="22">
						</td>
						<td>
							<input type="text" class="input-group-text" id="licence_num" name="licence_num" placeholder="Номер лицензии" maxlength="40">
						</td>
						<td>
							<input type="text" class="input-group-text" id="licence_key" name="licence_key" placeholder="Ключ продукта" maxlength="40">
						</td>
						<td>
							<select class="custom-select" name="version" id="version">
								<option value="">Выберите версию
							</select>
							<input type="text" class="input-group-text" name="version_manually" placeholder="Версия" maxlength="15">
						</td>
						<td>
							<select class="custom-select" name="sw_note" id="sw_note">
								<option value="">Примечание
							</select>
							<input type="text" class="input-group-text" name="sw_note_manually" placeholder="Ручной ввод" maxlength="50">
							<button type="button" class="del btn btn-danger" name="del_btn_sw">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button id="add_btn_sw" type="button" class="add btn btn-success" name="add_btn_sw">Добавить ПО</button>
						</td>
					</tr>
				</tbody>
				<script>
					new DynamicTable(document.getElementById("dynamic_sw"));
				</script>
			</tbody>

			</tbody>

		</table>
		<input type="submit" class="btn btn-primary" id="save_btn" name="save_passport" value="Сохранить паспорт">
		</form>
	</div>
</body>

</html>

<?php
	if (isset($_GET['id'])) {
		$ID_pc = htmlspecialchars($_GET['id']);
		echo '<script type="text/javascript">
			get_old_page(' . $ID_pc . ');
			document.getElementById("form").setAttribute("action", "../php/action.php?id=' . $ID_pc .'");';
		$user_level = get_permissions($pdo, $_SESSION['logged_user']);
		if ($user_level != 0) {
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