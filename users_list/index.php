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
	<title>Список пользователей</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
    <script type="text/javascript">
		function get_delete_confirmation(login) {
			login = "'" + login + "'";
			var answer = confirm("Удалить эту учётную запись?");
			if (answer) {
				var x = $.ajax({
					type: 'POST',
					url: '../php/ajaxData.php',
					async: false,
					data: {
						delete_user: login},
					dataType: "json",
					success: function(){}
				}).responseText;
				document.location.reload(true);
			}
		}
		
		function is_user_exist(login, call_type) {
			login_ajax = "'" + login + "'";
			var answer = false;
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					is_user_exist: login_ajax},
				dataType: "json",
				success: function(data){
					answer = data;
				}
			}).responseText;
			if (!answer) {
				alert("Пользователь" + login + " удалён, страница будет перезагружена.");
				document.location.reload(true);
			} else {
				if (call_type == 'edit') {
					document.location.href = '../user/index.php?login=' + login;
				}
			}
		}
		
        function get_users_list() {
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					get_users_list: ''},
				dataType: "json",
				success: function(data){
					rows = data.length / 3;
                    buff = '';
					for (i = 0; i < rows; i++) {
						buff +=
                        '<tr>' +
                            '<td>' + data[i * 3] + '</td>' +
                            '<td>' + data[i * 3 + 1] + '</td>';
						var role = data[i * 3 + 2];
						if (role == 0) {
							buff += '<td>Администратор</td>';
						} else if (role == 1) {
							buff += '<td>Оператор</td>';
						}
						buff += '<td><button type="button" class="del btn btn-danger" onclick="get_delete_confirmation(\'' + data[i * 3] + '\')">Удалить</button></td>';
						buff += '<td><button type="button" class="chng btn btn-primary" style="width: 100%" onclick="is_user_exist(\'' + data[i * 3] + '\', \'edit\')">Изменить</button></td></tr>';
                    }
					$('#users_list').html(buff);
				}
			});
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
		<h1>Список пользователей</h1>
        <table class="table table-bordered table-hover ">
            <tbody>
                <tr style="background-color: #D3D3D3">
                    <th scope="row" style="width: 20%">Логин пользователя</th>
                    <th scope="row" style="width: 20%">Пароль</th>
                    <th scope="row" style="width: 20%">Привилегии</th>
					<th scope="row" style="width: 40%" colspan="2">Действия</th>
                </tr>
            </tbody>
			<tbody id="users_list">
			</tbody>
        </table>
	</div>
</body>

</html>
<?php
	echo '<script type="text/javascript"> get_users_list(); </script>';
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