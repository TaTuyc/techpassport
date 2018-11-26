<?php
    function get_dependent_db_list ($pdo, $table_name, $column_name, $name, $value) {
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
?>