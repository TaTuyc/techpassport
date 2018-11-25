<?php

    function connect_db ($login, $password) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=Passport;charset=utf8', $login, $password);
            return $pdo;
        } catch (PDOException $e) {
            die('Подключение не удалось. Код ошибки: ' . $e->getMessage());
        }
    }
    
    function get_db_list ($pdo, $table_name, $column_name, $name, $value) {
        if ($name != '' && $value != '') {
            $name = "'" . $name . "'";
            $sql =
            "SELECT DISTINCT $value
                FROM $table_name
                WHERE $column_name = $name AND $value IS NOT NULL
                ORDER BY $value";
            $result = $pdo->query($sql);
            foreach($result as $row) {
                echo "<option>" . $row[$value];
            }
        } else {
            $sql =
            "SELECT DISTINCT $column_name
                FROM $table_name
                WHERE $column_name IS NOT NULL
                ORDER BY $column_name";
            $result = $pdo->query($sql);
            foreach($result as $row) {
                echo "<option>" . $row[$column_name];
            }
        };
    }
    
    if (isset($_POST['periph_dev'])) {
        echo 'Миииииу';
    }
    
    function get_periph_device ($pd_count) {
        $pd_count++;
        echo $pd_count;
    }
    
?>