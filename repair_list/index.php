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
	<title>Ремонт</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
	<script type="text/javascript">
		function set_pas_info(id_pc) {
			$.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				data: {
					print_data: 'pas_3',
					ID_page: id_pc},
				dataType: "json",
				success: function(data){
					$('#pas_info').html(
						'<tr>' +
							'<td style="width: 40%">' + data[0] + '</td>' +
							'<td style="width: 30%">' + data[1] + '</td>' +
							'<td style="width: 30%">' + data[2] + '</td>' +
						'</tr>'
					);
				}
			});
		}
        
        function get_repair_list(id_pc) {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					get_repair_list: id_pc},
				dataType: "json",
				success: function(data){
					rows = data.length / 3;
                    buff = '';
					for (i = 0; i < rows; i++) {
						buff +=
                        '<tr>' +
                            '<td style="width: 20%">' + data[i * 3] + '</td>' +
                            '<td style="width: 20%">' + data[i * 3 + 1] + '</td>' +
                            '<td style="width: 60%; text-align: justify">' + data[i * 3 + 2].replace(/(\n)/g, "<br>") + '</td>' +
                        '</tr>';
                    }
					$('#repair_info').html(buff);
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
                $pdo = connect_db();
                $ID_pc = htmlspecialchars($_GET['id']);
            ?>
            </p>
			<button type="button" class="btn btn-info" style="width: 90px" onclick=location.href='../php/logout.php'>Выйти</button>
		</div>
		<table id="list" class="table table-bordered table-hover ">
			<tbody>
				<tr style="background-color: #D3D3D3">
					<th scope="row" style="width: 40%">Имя рабочей станции</th>
					<th scope="row" style="width: 30%">Кабинет</th>
					<th scope="row" style="width: 30%">Инвентарный номер</th>
				</tr>
			</tbody>
			<tbody id="pas_info">
			</tbody>
		</table>
        <h1>Список ремонтов этого компьютера</h1>
        <table class="table table-bordered table-hover ">
            <tbody id="repair_info">
            </tbody>
        </table>
	</div>
</body>

</html>
<?php
echo '<script type="text/javascript"> set_pas_info(' . $ID_pc . '); get_repair_list(' . $ID_pc . ');</script>';
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