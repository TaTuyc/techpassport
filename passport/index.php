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
    <script type="text/javascript">
		qq_func = function(){};
		var buff_arr_names = [];
		var buff_arr_data = [];
		
		max_index = 0;
		
		$(document).on('submit', 'form', function(e) {
			var form = this;
			if (document.getElementsByName('responsible_person')[0].value == "") {
				if (document.getElementsByName('responsible_person_manually')[0].value == "") {
					e.preventDefault();
					alert("Назначьте ответственного!");
				} else {
					form.submit();
				}
			} else {
				form.submit();
			}
		});
		
		function eventChange(parent_id, call_type, name_, category_, index_) {
			var parent_var = $('#' + parent_id).val();
			if (parent_var != "") {
				var wow = $.ajax({
					type: 'POST',
					url: '../php/ajaxData.php',
					async: false,
					data: {
						is_call: call_type,
						parent: parent_var,
						name: name_,
						category: category_,
						index: index_
					}, success: function(html){
						if (call_type == 1) {
							$('#description_c' + category_ + '_elem' + index_).html(html);
							param = 'description_c' + category_ + '_elem' + index_;
							eventChange(param, 'f', 'description', category_, index_);
							eventChange(param, 'n', 'description', category_, index_);
						} else if (call_type == 'f') {
							$('#feature_c' + category_ + '_elem' + index_).html(html);
						} else if (call_type == 'n') {
							$('#hw_note_c' + category_ + '_elem' + index_).html(html);
						} else if (call_type == '1_pd') {
							$('#pd_model_c' + category_ + '_elem' + index_).html(html);
							eventChange('pd_model_c' + category_ + '_elem' + index_, 'f_pd', name_, category_, index_);
						} else if (call_type == 'f_pd') {
							$('#feature_c' + category_ + '_elem' + index_).html(html);
						} else if (call_type == 2) {
							$('#licence_type_elem' + index_).html(html);
							eventChange(parent_id, 'v_sw', name_, category_, index_);
							eventChange(parent_id, 'n_sw', name_, category_, index_);
						} else if (call_type == 'v_sw') {
							$('#version_elem' + index_).html(html);
						} else if (call_type == 'n_sw') {
							$('#sw_note_elem' + index_).html(html);
						} else if (call_type == 3) {
							$('#feature_c' + category_).html(html);
							eventChange('description_c' + category_, '3n', name_, category_, null);
						} else if (call_type == '3n') {
							$('#hw_note_c' + category_).html(html);
						}
					}
				}).responseText;
			}else{
				if (call_type == 1) {
					$('#description_c' + category_ + '_elem' + index_).html('<option value="">Выберите модель</option>');
					$('#feature_c' + category_ + '_elem' + index_).html('<option value="">Значение</option>');
					$('#hw_note_c' + category_ + '_elem' + index_).html('<option value="">Примечание</option>');
				} else if (call_type == 'f') {
					$('#feature_c' + category_ + '_elem' + index_).html('<option value="">Значение</option>');
				} else if (call_type == 'n') {
					$('#hw_note_c' + category_ + '_elem' + index_).html('<option value="">Примечание</option>');
				} else if (call_type == '1_pd') {
					$('#pd_model_c' + category_ + '_elem' + index_).html('<option value="">Выберите описание</option>');
					$('#feature_c' + category_ + '_elem' + index_).html('<option value="">Значение</option>');
				} else if (call_type == 'f_pd') {
					$('#feature_c' + category_ + '_elem' + index_).html('<option value="">Значение</option>');
				} else if (call_type == 2) {
					$('#licence_type_elem' + index_).html('<option value="">Выберите тип лицензии</option>');
				} else if (call_type == 'v_sw') {
					$('#version_elem' + index_).html('<option value="">Выберите версию</option>');
				} else if (call_type == 'n_sw') {
					$('#sw_note_elem' + index_).html('<option value="">Примечание</option>');
				} else if (call_type == 3) {
					$('#feature_c' + category_).html('<option value="">Значение</option>');
					$('#hw_note_c' + category_).html('<option value="">Примечание</option>');
				} else if (call_type == '3n') {
					$('#hw_note_c' + category_).html('<option value="">Примечание</option>');
				}
			}
		}
		
		function set_value(id, data) {
			if (id != null) {
				document.getElementById(id).value = data;
			}
		}
		
		function get_available_id(name) {
			var elems = document.getElementsByTagName('*');
			var buff;
			for (i = 0; i < elems.length; i++) {
				buff = elems[i].getAttribute('name');
				if (buff != null) {
					if (buff.indexOf(name) != -1) {
						if ($('#' + buff).val() == '') {
							return buff;
						}
					}
				}
			}
			buff = getCategory(name);
			if (name == 'sw_name') {
				document.getElementById('add_btn_sw').click();
			} else {
				document.getElementById('add_btn_c' + buff).click();
			}			
			return get_available_id(name);
		}
		
		function set_hw_item(data) {
			var category = data[4];
			buff = get_available_id('hw_name_c' + category);
			buff_elem = getIndex(buff);
			set_value(buff, data[0]);
			$('#' + buff).trigger('change');
			buff = 'description_c' + category + '_elem' + buff_elem;
			buff1 = 'feature_c' + category + '_elem' + buff_elem;
			buff2 = 'hw_note_c' + category + '_elem' + buff_elem;
			set_value(buff, data[1]);
			$('#' + buff).trigger('change');
			if (document.getElementById(buff1) != null) {
				set_value(buff1, data[2]);
			}
			set_value(buff2, data[3]);
			if (category == 2) {
				document.getElementById('div_pd_inv_num_c2_elem' + buff_elem).style.display = "none";
				document.getElementById('switch_btn_c2_elem' + buff_elem).style.display = "none";
			}
		}
		
		function get_hw_item(id_hw) {
			var buff;
			var wow = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
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
							set_value('mb_model', data[1]);
							$('#mb_model').trigger('change');
							set_value('mb_note', data[3]);
						} else if (data[0] == 'Оперативная память') {
							set_value('ram_type', data[1]);
							$('#ram_type').trigger('change');
							set_value('ram_capacity', data[2]);
							set_value('ram_note', data[3]);
						} else if (data[0] == 'ЦП') {
							set_value('cpu_model', data[1]);
							$('#cpu_model').trigger('change');
							set_value('cpu_frequency', data[2]);
							set_value('cpu_note', data[3]);
						}
						// размещаем данные в зависимости от категории
					} else if (data[4] == '1' || data[4] == '2' || data[4] == '3' || data[4] == '4') {
						set_hw_item(data);
					} else if (data[4] == '5') {
						set_value('description_c5', data[1]);
						$('#description_c5').trigger('change');
						set_value('feature_c5', data[2]);
						set_value('hw_note_c5', data[3]);
					}
				}
			}).responseText;
		}
		
		function get_hw_array(id_pc) {
			var wow = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					print_data: 'hw',
					ID_pc: id_pc},
				dataType: "json",
				success: function(data){
					data.forEach(function(item, i, data){
						get_hw_item(item);
					});
				}
			}).responseText;
		}
		
		function set_pd_item(data) {
			var category = data[4];
			if (category == 2) {
				buff = get_available_id('hw_name_c2');
				buff_elem = getIndex(buff);
				set_value(buff, data[0]);
				$('#' + buff).trigger('change');
				buff = 'description_c2_elem' + buff_elem;
				buff1 = 'feature_c2_elem' + buff_elem;
				buff2 = 'hw_note_c2_elem' + buff_elem;
				buff3 = 'pd_inv_num_c2_elem' + buff_elem;
				set_value(buff, data[1]);
				$('#' + buff).trigger('change');
				set_value(buff1, '');
				set_value(buff2, '');
				set_value(buff3, data[3]);
				document.getElementById('div_feature_c2_elem' + buff_elem).style.display = "none";
				document.getElementById('div_hw_note_c2_elem' + buff_elem).style.display = "none";
				document.getElementById('div_pd_inv_num_c2_elem' + buff_elem).style.display = "block";
				document.getElementById('switch_btn_c2_elem' + buff_elem).style.display = "none";
			} else if (category == 6) {
				set_value('description_c6', data[1]);
				set_value('pd_inv_num_c6', data[3]);
			} else if (category == 7) {
				buff = get_available_id('pd_name_c7');
				buff_elem = getIndex(buff);
				set_value(buff, data[0]);
				$('#' + buff).trigger('change');
				buff = 'pd_model_c7_elem' + buff_elem;
				buff1 = 'feature_c7_elem' + buff_elem;
				buff2 = 'pd_inv_num_c7_elem' + buff_elem;
				set_value(buff, data[1]);
				$('#' + buff).trigger('change');
				set_value(buff1, data[2]);
				set_value(buff2, data[3]);
			}
		}
		
		function get_pd_item(id_pd) {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					print_data: 'pd_id',
					ID_pd: id_pd},
				dataType: "json",
				success: function(data){
					for (i = 0; i < 5; i++) {
						if (data[i] == 'null') {
							data[i] = '';
						}
					}
					if (data[4] == '2') {
						set_pd_item(data);	
					} else if (data[4] == '6') {
						set_pd_item(data);
					} else if (data[4] == '7') {
						set_pd_item(data);
					}
					//console.log(data[0] + '   ' + data[1] + '   ' + data[2] + '   ' + data[3] + '   ' + data[4]);
				}
			}).responseText;
			return;
		}
		
		function get_pd_array(id_pc) {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					print_data: 'pd',
					ID_pc: id_pc},
				dataType: "json",
				success: function(data){
					data.forEach(function(item, i, data){
						get_pd_item(item);
					});
				}
			}).responseText;
		}
		
		function get_sw_item(id_sw) {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					print_data: 'sw_id',
					ID_sw: id_sw},
				dataType: "json",
				success: function(data){
					for (i = 0; i < 5; i++) {
						if (data[i] == 'null') {
							data[i] = '';
						}
					}
					buff = get_available_id('sw_name');
					buff_elem = getIndex(buff);
					set_value(buff, data[0]);
					$('#' + buff).trigger('change');
					buff = 'licence_type_elem' + buff_elem;
					buff1 = 'licence_num_elem' + buff_elem;
					buff2 = 'licence_key_elem' + buff_elem;
					buff3 = 'version_elem' + buff_elem;
					buff4 = 'sw_note_elem' + buff_elem;
					set_value(buff, data[1]);
					set_value(buff1, data[2]);
					set_value(buff2, data[3]);
					set_value(buff3, data[4]);
					set_value(buff4, data[5]);
					//console.log(data[0] + ']   [' + data[1] + ']   [' + data[2] + ']   [' + data[3] + ']   [' + data[4] + ']   [' + data[5]);
				}
			}).responseText;
		}
		
		function get_sw_array(id_pc) {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					print_data: 'sw',
					ID_pc: id_pc},
				dataType: "json",
				success: function(data){
					data.forEach(function(item, i, data){
						get_sw_item(item);
					});
				}
			}).responseText;
		}
		
		function get_data_via_id(id_, name_) {
			var wow = $.ajax({
				type: 'POST',
				url: '../php/fillprint.php',
				async: false,
				data: {
					id: id_,
					name: name_
				}, success: function(html){
					//document.getElementById(name_).value = html + "-01-01";
					if (name_ == 'manufacture_date') {
						document.getElementsByName(name_)[0].value = html + "-01-01";
					} else if (name_ == 'buying_method') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'balance_date_bookkeeping') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'balance_num') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'balance_date') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'pc_name') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'pc_place') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'position') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'pc_inv_num') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'responsible_person') {
						document.getElementsByName(name_)[0].value = html;
					} else if (name_ == 'responsible_since') {
						document.getElementsByName(name_)[0].value = html;
					}
				}
			}).responseText;
		}
		
		$(document).ready(function(){
					
		});
		
		$(document).on('change', function(e) {
			var parent_id = e.target.id;
			if (parent_id == "") {
				return;
			}
			var parent_var = $('#' + parent_id).val();
			var x;
			//console.log(parent_id);
			if (parent_id == 'mb_model') {
				if(parent_var != ""){
					x = $.ajax({
						type:'POST',
						url: '../php/ajaxData.php',
						async: false,
						data: {
							mb_model: parent_var
						}, success:function(html){
							$('#mb_note').html(html);
						}
					}).responseText;
				}else{
					$('#mb_note').html('<option value="">Примечание</option>'); 
				}
			} else if (parent_id == 'ram_type') {
				if(parent_var != ""){
					x = $.ajax({
						type:'POST',
						url: '../php/ajaxData.php',
						async: false,
						data: {
							ram_type: parent_var,
							need: 'rc'
						}, success:function(html){
							$('#ram_capacity').html(html);
						}
					}).responseText;
					x = $.ajax({
						type:'POST',
						url: '../php/ajaxData.php',
						async: false,
						data: {
							ram_type: parent_var,
							need: 'rn'
						}, success:function(html){
							$('#ram_note').html(html);
						}
					}).responseText; 
				}else{
					$('#ram_capacity').html('<option value="">Выберите объём</option>');
					$('#ram_note').html('<option value="">Примечание</option>'); 
				}
			} else if (parent_id == 'cpu_model') {
				if(parent_var != ""){
					x = $.ajax({
						type:'POST',
						url: '../php/ajaxData.php',
						async: false,
						data: {
							cpu_model: parent_var,
							need: 'cf'
						}, success:function(html){
							$('#cpu_frequency').html(html);
						}
					}).responseText;
					x = $.ajax({
						type:'POST',
						url: '../php/ajaxData.php',
						async: false,
						data: {
							cpu_model: parent_var,
							need: 'cn'
						}, success:function(html){
							$('#cpu_note').html(html);
						}
					}).responseText; 
				}else{
					$('#cpu_frequency').html('<option value="">Выберите частоту</option>');
					$('#cpu_note').html('<option value="">Примечание</option>'); 
				}
			} else {
				//console.log(e.target);
				var name_ = getName(parent_id);
				var category_ = getCategory(parent_id);
				var index_ = getIndex(parent_id);
				if (getTypeDynamicRow(parent_id) == 1) {
					if (name_ == 'hw_name') {
						eventChange(parent_id, '1', name_, category_, index_);
					} else if (name_ == 'description') {
						eventChange(parent_id, 'f', name_, category_, index_);
						eventChange(parent_id, 'n', name_, category_, index_);
					} else if (name_ == 'pd_name') {
						eventChange(parent_id, '1_pd', name_, category_, index_);
					} else if (name_ == 'pd_model') {
						eventChange(parent_id, 'f_pd', name_, category_, index_);
					}
				} else if (getTypeDynamicRow(parent_id) == 2) {
					if (name_ == 'sw_name') {
						eventChange(parent_id, '2', name_, category_, index_);
					}
				} else if (getTypeDynamicRow(parent_id) == 3) {
					if (name_ == 'description') {
						eventChange(parent_id, '3', name_, category_, null);
					}
				}
			}
		});
		
		function get_old_page(id) {
			get_data_via_id(id, 'manufacture_date');
			get_data_via_id(id, 'buying_method');
			get_data_via_id(id, 'balance_date_bookkeeping');
			get_data_via_id(id, 'balance_num');
			get_data_via_id(id, 'balance_date');
			get_data_via_id(id, 'pc_name');
			get_data_via_id(id, 'pc_place');
			get_data_via_id(id, 'position');
			get_data_via_id(id, 'pc_inv_num');
			get_data_via_id(id, 'responsible_person');
			get_data_via_id(id, 'responsible_since');
			get_hw_array(id);
			get_pd_array(id);
			get_sw_array(id);
			document.getElementById('save_btn').setAttribute('name', 'update_passport');
			
			//console.log(get_RID());
			//document.getElementById('add_btn_c1').click();
			/*get_data_via_id(id, 'balance_date_bookkeeping');
			get_data_via_id(id, 'balance_date_bookkeeping');
			get_data_via_id(id, 'balance_date_bookkeeping');
			get_data_via_id(id, 'balance_date_bookkeeping');
			get_data_via_id(id, 'balance_date_bookkeeping');
			get_data_via_id(id, 'balance_date_bookkeeping');
			get_data_via_id(id, 'balance_date_bookkeeping');*/
			
		}
		
		$(document).on('click', function(e) {
			var id = e.target.id;
			var name = getName(id);
			if (name == 'switch_btn') {
				var category = getCategory(id);
				var index = getIndex(id);
				if (document.getElementById('div_feature_c' + category + '_elem' + index).style.display == "none") {
					document.getElementById('div_feature_c' + category + '_elem' + index).style.display = "block";
					document.getElementById('div_hw_note_c' + category + '_elem' + index).style.display = "block";
					document.getElementById('div_pd_inv_num_c' + category + '_elem' + index).style.display = "none";
				} else {
					document.getElementById('div_feature_c' + category + '_elem' + index).style.display = "none";
					document.getElementById('div_hw_note_c' + category + '_elem' + index).style.display = "none";
					document.getElementById('div_pd_inv_num_c' + category + '_elem' + index).style.display = "block";
				}
			}
		});
		
		//function switch_c2(btn_name)
	</script>
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
			document.getElementById("form").setAttribute("action", "../php/action.php?id=' . $ID_pc .'");
		</script>';
	}
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