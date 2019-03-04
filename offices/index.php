<?php
include_once '../php/action.php';
include_once '../php/fillprint.php';
if (isset($_POST['log_out'])) {
	unset($_SESSION['logged_user']);
	header('Location: ../login/index.php');
}
if (isset($_SESSION['logged_user'])) {
    $pdo = connect_db();
    $user_level = get_permissions($pdo, $_SESSION['logged_user']);
    if ($user_level == 0) {
?>
<!DOCTYPE html>
<html>

<!--Головушка-->

<head>
	<meta charset="utf-8">
	<!--Тип Кодировки-->
	<title>Кабинеты</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
    <script type="text/javascript">
		function get_delete_confirmation(id) {
			var answer = confirm("Удалить кабинет из базы данных?");
			var is_using = false;
			if (answer) {
				var x = $.ajax({
					type: 'POST',
					url: '../php/ajaxData.php',
					async: false,
					data: {
						delete_office: id},
					dataType: "json",
					success: function(data){
						if (data != null) {
							alert(data);
							is_using = true;
							return;
						}
					}
				}).responseText;
				if (!is_using) {
					document.location.reload(true);
				}
			}
		}
		
        function get_offices_list() {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					get_offices_list: ''},
				dataType: "json",
				success: function(data){
					rows = data.length / 2;
                    buff = '';
					for (i = 0; i < rows; i++) {
						buff +=
                        '<tr>' +
                            '<td>' + data[i * 2];
                        buff += '<td><input id="new_office_name_id' + data[i * 2 + 1] + '" type="text" class="input-group-text" placeholder="Ручной ввод" maxlength="20">';
						buff += '<td style="width: 15%"><button type="button" class="chng btn btn-primary" onclick="set_new_name(' + data[i * 2 + 1] + ')" style="width: 100%">Переименовать</button></td>';
                        buff += '<td><button type="button" class="del btn btn-danger" onclick="get_delete_confirmation(' + data[i * 2 + 1] + ')">Удалить</button></td></tr>';
                    }
                    buff +=
                    '<tr><th colspan="4" style="background-color: #D3D3D3">Добавление кабинета</tr>' +
                    '<tr><td><td><input type="text" class="input-group-text" name="create_office" placeholder="Ручной ввод" maxlength="20">';
                    buff += '<td style="width: 15%"><button type="submit" class="btn btn-success" style="width: 100%" onclick="create_office()">Создать</button></td>';
                    buff += '<td></td></tr>';
					$('#offices_list').html(buff);
				}
			});
		}
		
		function set_new_name(id_office) {
			var name = document.getElementById('new_office_name_id' + id_office).value;
			var suc = false;
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					update_office: id_office,
					new_office_name: name},
				dataType: "json",
				success: function(data){
					suc = true;
				}
			}).responseText;
			if (suc) {
				document.location.reload(true);
			}
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
		<h1>Список кабинетов</h1>
        <form id="form" action="../php/action.php" method="post">
            <table class="table table-bordered table-hover ">
                <tbody>
                    <tr style="background-color: #D3D3D3">
                        <th scope="row" style="width: 40%">Название кабинета</th>
                        <th scope="row" style="width: 45%" colspan="2">Обновить название</th>
                        <th scope="row" style="width: 15%">Другие действия</th>
                    </tr>
                </tbody>
                <tbody id="offices_list">
                </tbody>
            </table>
        </form>
	</div>
</body>

</html>
<?php
	echo '<script type="text/javascript"> get_offices_list(); </script>';
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
        <p style="font-size:30pt; color: #800000; text-align: center">Доступ запрещён. Вы можете <a href="../login/index.php"> авторизоваться</a> как администратор.<p>&nbsp;</div>' .
        '</body>
        </html>';
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