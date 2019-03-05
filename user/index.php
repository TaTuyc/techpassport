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
	<title>Пользователь</title>
	<!--Заголовок-->
	<link rel="stylesheet" href="../css/bootstrap.css">
	<script type="text/javascript" src="../script/jquery.js"></script>
	<script type="text/javascript" src="../script/parsing.js"></script>
	<script type="text/javascript" src="../script/dynamicTable.js"></script>
	<script type="text/javascript" src="../jquery/jquerymin.js"></script>
    <script type="text/javascript">
        $(document).on('submit', 'form', function(e) {
            var form = this;
            if (document.getElementById('password_1').value != document.getElementById('password_2').value) {
                e.preventDefault();
                alert("Пароли не совпадают!");
            } else if (document.getElementById('permissions').value == '') {
				e.preventDefault();
                alert("Роль не указана!");
			} else {
                form.submit();
            }
        });
		
		function get_old_page(login) {
			login_ajax = "'" + login + "'";
			var x = $.ajax({
				type: 'POST',
				url: '../php/ajaxData.php',
				async: false,
				data: {
					get_user_data: login_ajax},
				dataType: "json",
				success: function(data){
					document.getElementById('login').value = data[0];
					document.getElementById('password_1').value = data[1];
					document.getElementById('password_2').value = data[1];
					document.getElementById('permissions').value = data[2];
					
					document.getElementById('login').setAttribute('disabled', 'disabled');
				}
			}).responseText;
			document.getElementById('save_btn').setAttribute('name', 'update_user');
			document.getElementById('user_header').innerHTML = 'Редактирование учётной записи пользователя ' + login;
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
		<h1 id="user_header">Регистрация нового пользователя</h1>
        <form id="form" action="../php/action.php" method="post">
            <table class="table table-bordered table-hover ">
                <tbody>
                    <tr style="background-color: #D3D3D3">
                        <th scope="row" style="width: 25%">Логин пользователя</th>
                        <th scope="row" style="width: 25%">Пароль</th>
                        <th scope="row" style="width: 25%">Пароль (ещё раз)</th>
                        <th scope="row" style="width: 25%">Привилегии</th>
                    </tr>
                    <tr>
                        <td><input id="login" type="text" class="custom-select" name="login" pattern="[A-Za-z0-9]{5,}" required placeholder="Логин" title="Допустимые символы: латиница и цифры, не менее 5 символов"></td>
                        <td><input id="password_1" type="password" class="input-group-text" name="password" pattern="[A-Za-z0-9]{5,}" maxlength="30" required placeholder="Пароль" title="Допустимые символы: латиница и цифры, не менее 5 символов"></td>
                        <td><input id="password_2" type="password" class="input-group-text" maxlength="30" pattern="[A-Za-z0-9]{5,}" required placeholder="Пароль" title="Допустимые символы: латиница и цифры, не менее 5 символов"></td>
                        <td><select id="permissions" class="custom-select" name="permissions">
                            <option value="">Выберите роль
                            <option value="1">Оператор
                            <option value="0">Администратор
                        </select></td>
                    </tr>
                </tbody>
            </table>
            <input id="save_btn" type="submit" class="btn btn-primary" name="save_user" value="Сохранить">
        </form>
	</div>
</body>

</html>
<?php
		if (isset($_GET['login'])) {
			$login = htmlspecialchars($_GET['login']);
			echo '<script type="text/javascript">
				get_old_page(\'' . $login . '\');
				document.getElementById("form").setAttribute("action", "../php/action.php?login=' . $login .'"); </script>';
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