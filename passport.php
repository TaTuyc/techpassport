<!DOCTYPE html>
<html>

<!--Головушка-->

<head>
	<meta charset="utf-8">
	<!--Тип Кодировки-->
	<title>Паспорт.</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="css/bootstrap.css">
	<script type="text/javascript" src="script/jquery.js"></script>
	<script type="text/javascript" src="script/dynamicTable.js"></script>
    <?php include 'action.php';
        $pdo = connect_db('root', '62996326');
    ?>
</head>

<!--Тушка-->

<body>
	<div class="table-responsive text-center" style="width: 80%; margin: auto">
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
					<th scope="row">Дата производства:</th>
					<td colspan="2">
						<input type="date" class="custom-select" name="manufacture_date">
					</td>
					<th scope="row">Способ производства:</th>
					<td colspan="2">
						<select class="custom-select" name="buying_method">
							<option> Выберите способ
							<option> Покупка
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
				</tr>

				<tr>
					<th scope="row">Дата постановки на баланс по информации бухгалтерии учреждения</th>
					<td colspan="2">
						<input type="date" class="custom-select" name="balance_date_bookkeeping">
					</td>
					<th scope="row">№ и дата документа постановки на баланс</th>
					<td colspan>
						<input type="text" class="input-group-text" name="balance_num" placeholder="Введите номер">
					</td>
					<td colspan>
						<input type="date" class="custom-select" name="balance_date">
					</td>
				</tr>

				<tr>
					<th scope="row">Имя рабочей станции</th>
					<td colspan="2">
						<input type="text" class="input-group-text" name="pc_name" placeholder="Имя">
					</td>
					<th scope="row">Место установки</th>
					<td>
						<select class="custom-select" name="pc_place">
							<option> Кабинет
							<option> Кабинет №13
						</select>
					</td>
					<td>
						<select class="custom-select" name="position">
							<option> Должность
							<option> Директор
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">ИНВ.НОМЕР:</th>
					<td colspan="2">
						<input type="text" class="input-group-text" name="pc_inv_num" placeholder="Номер">
					</td>
					<th scope="row">Ответственный за эксплуатацию:</th>
					<td colspan="2">
						<select class="custom-select" name="responsible_person">
							<option> Выберите ответственного
							<option>Пронина Людмила Александровна
						</select>
                        <input type="text" class="input-group-text" name="responsible_person_manually" placeholder="Ручной ввод">
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
						<select class="custom-select" name="mb_model">
                            <option> Выберите модель
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Системная плата', 'description');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="mb_model_manually" placeholder="Ручной ввод">
					</td>
					<td></td>
					<td colspan="2" style="color: blue;">
						<select class="custom-select" name="mb_note">
							<option> Примечание
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Системная плата', 'hw_note');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="mb_note_manually" placeholder="Ручной ввод">
					</td>
				</tr>

				<tr>
					<th scope="row">Оперативная память</th>
					<td colspan="2">
						<select class="custom-select" name="ram_type">
                            <option> Выберите тип
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Оперативная память', 'description');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="ram_type_manually" placeholder="Ручной ввод">
					</td>
					<td>
						<select class="custom-select" name="ram_capacity">
							<option> Выберите объём
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Оперативная память', 'feature');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="ram_capacity_manually" placeholder="Ручной ввод">
					</td>
					<td colspan="2">
						<select class="custom-select" name="ram_note">
							<option> Примечание
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Оперативная память', 'hw_note');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="ram_note_manually" placeholder="Ручной ввод">
					</td>
				</tr>

				<tr>
					<th scope="row">ЦП</th>
					<td colspan="2">
						<select class="custom-select" name="cpu_model">
							<option> Выберите модель
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'ЦП', 'description');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="cpu_model_manually" placeholder="Ручной ввод">
					</td>
					<td>
						<select class="custom-select" name="cpu_frequency">
							<option> Выберите частоту
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'ЦП', 'feature');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="cpu_frequency_manually" placeholder="Ручной ввод">
					</td>
					<td colspan="2" style="color: blue;">
						<select class="custom-select" name="cpu_note">
							<option> Примечание
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'ЦП', 'hw_note');
                            ?>
						</select>
						<input type="text" class="input-group-text" name="cpu_note_manually" placeholder="Ручной ввод">
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
							<select class="custom-select" name="hw_name_c1"> //c1 - категория 1, устройства хранения данных
								<option>Выберите тип устройства
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'hw_name');
                                ?>
							</select>
							<input type="text" class="input-group-text" name="hw_name_c1_manually" placeholder="Ручной ввод">
						</td>
						<td colspan="2">
							<select class="custom-select" name="description_c1">
								<option>Выберите модель
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'description');
                                ?>
							</select>
							<input type="text" class="input-group-text" name="description_c1_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<select class="custom-select" name="feature_c1">
								<option>Выберите характеристику
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'feature');
                                ?>
							</select>
							<input type="text" class="input-group-text" name="feature_c1_manually" placeholder="Ручной ввод">
						</td>
						<td>
                            <select class="custom-select" name="hw_note_c1">
								<option>Примечание
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'hw_note');
                                ?>
							</select>
							<input type="text" class="input-group-text" name="hw_note_c1_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<button type="button" class="del btn btn-danger" name="del_btn_c1">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success" name="add_btn_c1">Добавить</button>
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
							<select class="custom-select" name="hw_name_c2">
								<option>Выберите тип устройства
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '2', 'hw_name');
                                    get_db_list($pdo, 'Periphery', 'category', '2', 'pd_name');
                                ?>
							</select>
							<input type="text" class="input-group-text" name="hw_name_c2_manually" placeholder="Ручной ввод">
						</td>
						<td colspan="2">
							<select class="custom-select" name="description_c2">
								<option>Выберите модель
								<?php
									get_db_list($pdo, 'Hardware', 'category', '2', 'description');
									get_db_list($pdo, 'Periphery', 'category', '2', 'pd_model');
								?>
							</select>
							<input type="text" class="input-group-text" name="description_c2_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<p style="color: blue">инв.номер:</p>
							<input type="text" class="input-group-text" name="pd_inv_num_c2" placeholder="Номер">
						</td>
						<td>
							<select class="custom-select" name="hw_note_c2">
								<option>Примечание
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '2', 'hw_note');
                                    get_db_list($pdo, 'Periphery', 'category', '2', 'pd_note');
                                ?>
							</select>
							<input type="text" class="input-group-text" name="hw_note_c2_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<button type="button" class="del btn btn-danger" name="del_btn_c2">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success" name="add_btn_c2">Добавить</button>
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
							<select class="custom-select" name="hw_name_c3">
								<option>Выберите тип устройства
								<?php
									get_db_list($pdo, 'Hardware', 'category', '3', 'hw_name');
								?>
							</select>
							<input type="text" class="input-group-text" name="hw_name_c3_manually" placeholder="Ручной ввод">
						</td>
						<td colspan="2">
							<select class="custom-select" name="description_c3">
								<option>Выберите модель
								<?php
									get_db_list($pdo, 'Hardware', 'category', '3', 'description');
								?>
							</select>
							<input type="text" class="input-group-text" name="description_c3_manually" placeholder="Ручной ввод">
						</td>
						<td></td>
						<td>
							<select class="custom-select" name="hw_note_c3">
								<option>Примечание
								<?php
									get_db_list($pdo, 'Hardware', 'category', '3', 'hw_note');
								?>
							</select>
							<input type="text" class="input-group-text" name="hw_note_c3_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<button type="button" class="del btn btn-danger" name="del_btn_c3">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success" name="add_btn_c3">Добавить</button>
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
							<select class="custom-select" name="hw_name_c4">
								<option>Выберите тип устройства
								<?php
									get_db_list($pdo, 'Hardware', 'category', '4', 'hw_name');
								?>
							</select>
							<input type="text" class="input-group-text" name="hw_name_c4_manually" placeholder="Ручной ввод">
						</td>
						<td colspan="2">
							<select class="custom-select" name="description_c4">
								<option>Выберите модель
								<?php
									get_db_list($pdo, 'Hardware', 'category', '4', 'description');
								?>
							</select>
							<input type="text" class="input-group-text" name="description_c4_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<select class="custom-select" name="feature_c4">
								<option>Выберите количество
								<?php
									get_db_list($pdo, 'Hardware', 'category', '4', 'feature');
								?>
							</select>
							<input type="text" class="input-group-text" name="feature_c4_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<select class="custom-select" name="hw_note_c4">
								<option>Примечание
								<?php
									get_db_list($pdo, 'Hardware', 'category', '4', 'hw_note');
								?>
							</select>
							<input type="text" class="input-group-text" name="hw_note_c4_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<button type="button" class="del btn btn-danger" name="del_btn_c4">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success" name="add_btn_c4">Добавить</button>
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
						<select class="custom-select" name="description_c5">
							<option> Выберите тип
							<?php
								get_db_list($pdo, 'Hardware', 'category', '5', 'description');
							?>
						</select>
						<input type="text" class="input-group-text" name="description_c5_manually" placeholder="Ручной ввод">
					</td>
					<td>
						<select class="custom-select" name="feature_c5">
							<option> Выберите мощность
							<?php
								get_db_list($pdo, 'Hardware', 'category', '5', 'feature');
							?>
						</select>
						<input type="text" class="input-group-text" name="feature_c5_manually" placeholder="Ручной ввод">
					</td>
					<td colspan="2">
						<select class="custom-select" name="hw_note_c5">
							<option> Примечание
							<?php
								get_db_list($pdo, 'Hardware', 'category', '5', 'hw_note');
							?>
						</select>
						<input type="text" class="input-group-text" name="hw_note_c5" placeholder="Ручной ввод">
					</td>
				</tr>

				<tr>
					<th scope="row">Принтер</th>
					<td colspan="2">
						<select class="custom-select" name="hw_name_c6">
							<option> Выберите модель
							<?php
								get_db_list($pdo, 'Periphery', 'category', '6', 'pd_model');
							?>
						</select>
						<input type="text" class="input-group-text" name="hw_name_c6_manually" placeholder="Ручной ввод">
					</td>
					<th style="color: blue">инв.номер:</th>
					<td colspan="3">
						<input type="text" class="input-group-text" name="pd_inv_num_c6" placeholder="Номер">
					</td>
				</tr>

				<tbody id="perepherals">
					<tr>
						<th colspan="6" style="background-color: #D3D3D3">Другие периферийные устройства:</th>
					</tr>

					<tbody id="dynamic_per">
						<tr>
							<td>
								<select class="custom-select" name="pd_name_c10">
									<option>Выберите тип устройства
									<?php
										get_db_list($pdo, 'Periphery', 'category', '10', 'pd_name');
									?>
								</select>
								<input type="text" class="input-group-text" name="pd_name_c10_manually" placeholder="Ручной ввод">
							</td>
							<td colspan="2">
								<select class="custom-select" name="pd_model_c10">
									<option>Выберите описание
									<?php
										get_db_list($pdo, 'Periphery', 'category', '10', 'pd_model');
									?>
								</select>
								<input type="text" class="input-group-text" name="pd_model_c10_manually" placeholder="Ручной ввод">
							</td>
							<td>
								<select class="custom-select" name="feature_c10">
									<option>Характеристика
									<?php
										get_db_list($pdo, 'Periphery', 'category', '10', 'feature');
									?>
								</select>
								<input type="text" class="input-group-text" name="feature_c10_manually" placeholder="Ручной ввод">
							</td>
							<td></td>
							<td>
								<button type="button" class="del btn btn-danger" name="del_btn_c10">Удалить</button>
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<button type="button" class="add btn btn-success" name="add_btn_c10">Добавить устройство</button>
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
							<select class="custom-select" name="sw_name">
								<option>Выберите наименование
								<?php
									get_db_list($pdo, 'Software', 'sw_name', '', '');
								?>
							</select>
							<input type="text" class="input-group-text" name="sw_name_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<select class="custom-select" name="licence_type">
								<option>Выберите тип лицензии
								<?php
									get_db_list($pdo, 'Software', 'licence_type', '', '');
								?>
							</select>
							<input type="text" class="input-group-text" name="licence_type_manually" placeholder="Ручной ввод">
						</td>
						<td>
							<input type="text" class="input-group-text" name="licence_num" placeholder="Номер лицензии">
						</td>
						<td>
							<input type="text" class="input-group-text" name="licence_key" placeholder="Ключ продукта">
						</td>
						<td>
							<select class="custom-select" name="version">
								<option>Выберите версию
								<?php
									get_db_list($pdo, 'Software', 'version', '', '');
								?>
							</select>
							<input type="text" class="input-group-text" name="version_manually" placeholder="Версия">
						</td>
						<td>
							<select class="custom-select" name="sw_note">
								<option>Примечание
								<?php
									get_db_list($pdo, 'Software', 'sw_note', '', '');
								?>
							</select>
							<input type="text" class="input-group-text" name="sw_note_manually" placeholder="Ручной ввод">
							<button type="button" class="del btn btn-danger" name="del_btn_sw">Удалить строку</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success" name="add_btn_sw">Добавить ПО</button>
						</td>
					</tr>
				</tbody>
				<script>
					new DynamicTable(document.getElementById("dynamic_sw"));
				</script>
			</tbody>

			</tbody>

		</table>
		<input type="submit" class="btn btn-primary" name="save_passport" value="Сохранить паспорт">
	</div>
</body>

</html>