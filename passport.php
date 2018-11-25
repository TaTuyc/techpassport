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
						<input type="date" class="custom-select" name="born_date">
					</td>
					<th scope="row">Способ производства:</th>
					<td colspan="2">
						<select class="custom-select" name="how">
							<option> Выберите способ
							<option> Покупка
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
				</tr>

				<tr>
					<th scope="row">Дата постановки на баланс по информации бухгалтерии учреждения</th>
					<td colspan="2">
						<input type="date" class="custom-select" name="autorize_date">
					</td>
					<th scope="row">№ и дата документа постановки на баланс</th>
					<td colspan>
						<input class="input-group-text" type="text" placeholder="Введите номер">
					</td>
					<td colspan>
						<input type="date" class="custom-select" name="balance_date">
					</td>
				</tr>

				<tr>
					<th scope="row">Имя рабочей станции</th>
					<td colspan="2">
						<input type="text" class="input-group-text" placeholder="Имя">
					</td>
					<th scope="row">Место установки</th>
					<td>
						<select class="custom-select" name="place">
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
						<input type="text" class="input-group-text" placeholder="Номер">
					</td>
					<th scope="row">Ответственный за эксплуатацию:</th>
					<td colspan="2">
						<select class="custom-select" name="responsible">
							<option> Выберите ответственного
							<option>Пронина Людмила Александровна
						</select>
                        <input type="text" class="input-group-text" placeholder="Ручной ввод">
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
						<select class="custom-select" name="mb">
                            <option> Выберите модель
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Системная плата', 'description');
                            ?>
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
					<td></td>
					<td colspan="2" style="color: blue;">
						<select class="custom-select" name="mb_note">
							<option> Примечание
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Системная плата', 'hw_note');
                            ?>
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
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
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
					<td>
						<select class="custom-select" name="ram_capacity">
							<option> Выберите объём
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Оперативная память', 'feature');
                            ?>
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
					<td colspan="2">
						<select class="custom-select" name="ram_note">
							<option> Примечание
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'Оперативная память', 'hw_note');
                            ?>
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
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
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
					<td>
						<select class="custom-select" name="cpu_frequency">
							<option> Выберите частоту
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'ЦП', 'feature');
                            ?>
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
					<td colspan="2" style="color: blue;">
						<select class="custom-select" name="ram_capacity">
							<option> Примечание
							<?php
                                get_db_list($pdo, 'Hardware', 'hw_name', 'ЦП', 'hw_note');
                            ?>
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
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
							<select class="custom-select" name="select">
								<option>Выберите тип устройства</option>
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'hw_name');
                                ?>
							</select>
							<input type="text" name="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td colspan="2">
							<select class="custom-select">
								<option>Выберите модель</option>
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'description');
                                ?>
							</select>
							<input type="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td>
							<select class="custom-select">
								<option>Выберите характеристику</option>
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'feature');
                                ?>
							</select>
							<input type="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td>
                            <select class="custom-select">
								<option>Примечание</option>
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '1', 'hw_note');
                                ?>
							</select>
							<input type="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td>
							<button type="button" class="del btn btn-danger">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success">Добавить</button>
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
							<select class="custom-select" name="select">
								<option>Выберите тип устройства</option>
								<?php
                                    get_db_list($pdo, 'Hardware', 'category', '2', 'hw_name');
                                    get_db_list($pdo, 'Periphery', 'category', '2', 'pd_name');
                                ?>
							</select>
							<input type="text" name="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td colspan="2">
							<select class="custom-select">
								<option>Выберите модель</option>
								<option>Intel(R) HD Graphics</option>
								<option>Integrated</option>
							</select>
							<input type="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td></td>
						<td>
							<input type="text" class="input-group-text" placeholder="Примечание">
						</td>
						<td>
							<button type="button" class="del btn btn-danger">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success">Добавить</button>
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
							<select class="custom-select" name="select">
								<option>Выберите тип устройства</option>
								<option>Звуковой адаптер</option>
							</select>
							<input type="text" name="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td colspan="2">
							<select class="custom-select">
								<option>Выберите модель</option>
								<option>Intel Cougar Point PCH - High Definition Audio Controller</option>
							</select>
							<input type="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td></td>
						<td>
							<input type="text" class="input-group-text" placeholder="Примечание">
						</td>
						<td>
							<button type="button" class="del btn btn-danger">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success">Добавить</button>
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
							<select class="custom-select" name="select">
								<option>Выберите тип устройства</option>
								<option>Сетевой адаптер</option>
							</select>
							<input type="text" name="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td colspan="2">
							<select class="custom-select">
								<option>Выберите модель</option>
								<option>Realtek RTL8188CE Wireless LAN 802.11n PCI-E</option>
							</select>
							<input type="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td>
							<select class="custom-select">
								<option>Выберите количество</option>
								<option>1</option>
							</select>
							<input type="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td>
							<input type="text" class="input-group-text" placeholder="Примечание">
						</td>
						<td>
							<button type="button" class="del btn btn-danger">Удалить</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success">Добавить</button>
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
						<select class="custom-select" name="Tower">
							<option> Выберите тип
							<option> Моноблок
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
					<td>
						<select class="custom-select" name="Power">
							<option> Выберите мощность
							<option> 350
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
					<td colspan="2">
						<select class="custom-select" name="Prim">
							<option> Выберите примечание
							<option> Мощность блока питания
						</select>
						<input type="text" class="input-group-text" placeholder="Примечание">
					</td>
				</tr>

				<tr>
					<th scope="row">Принтер</th>
					<td colspan="2">
						<select class="custom-select" name="Printer">
							<option> Выберите модель
							<option> Xerox 3220
						</select>
						<input type="text" class="input-group-text" placeholder="Ручной ввод">
					</td>
					<td></td>
					<th style="color: blue">инв.номер:</th>
					<td colspan="2">
						<input type="text" class="input-group-text" placeholder="Номер">
					</td>
				</tr>

				<tbody id="perepherals">
					<tr>
						<th colspan="6" style="background-color: #D3D3D3">Другие периферийные устройства:</th>
					</tr>

					<tbody id="dynamic_per">
						<tr>
							<td>
								<select class="custom-select" name="select">
									<option>Выберите тип устройства</option>
								</select>
								<input type="text" name="text" class="input-group-text" placeholder="Ручной ввод">
							</td>
							<td colspan="2">
								<select class="custom-select">
									<option>Описание</option>
								</select>
								<input type="text" class="input-group-text" placeholder="Ручной ввод">
							</td>
							<td>
								<select class="custom-select">
									<option>Характеристика</option>
								</select>
								<input type="text" class="input-group-text" placeholder="Ручной ввод">
							</td>
							<td>
								<input type="text" class="input-group-text" placeholder="Примечание">
							</td>
							<td>
								<button type="button" class="del btn btn-danger">Удалить</button>
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<button type="button" class="add btn btn-success">Добавить устройство</button>
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
							<select class="custom-select" name="select">
								<option>Выберите наименование</option>
								<option>Microsoft Windows</option>
								<option>Microsoft Office</option>
								<option>"Культура"</option>
							</select>
							<input type="text" name="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td>
							<select class="custom-select">
								<option>Выберите тип лицензии</option>
								<option>OEM</option>
								<option>Academic</option>
							</select>
							<input type="text" class="input-group-text" placeholder="Ручной ввод">
						</td>
						<td>
							<input type="text" class="input-group-text" placeholder="Номер лицензии">
						</td>
						<td>
							<input type="text" class="input-group-text" placeholder="Ключ продукта">
						</td>
						<td>
							<input type="text" class="input-group-text" placeholder="Версия">
						</td>
						<td>
							<input type="text" class="input-group-text" placeholder="Примечание">
							<button type="button" class="del btn btn-danger">Удалить строку</button>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<button type="button" class="add btn btn-success">Добавить ПО</button>
						</td>
					</tr>
				</tbody>
				<script>
					new DynamicTable(document.getElementById("dynamic_sw"));
				</script>
			</tbody>

			</tbody>

		</table>
		<input type="submit" class="btn btn-primary" name="send" value="Сохранить паспорт">
	</div>
</body>

</html>