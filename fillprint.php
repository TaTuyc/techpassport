<?php
    // разворачивание данных для создания отчёта
    function get_data_via_id ($pdo, $id_pc, $table_name, $column_name) {
        $sql =
        "SELECT $column_name
            FROM $table_name
            WHERE ID_pc = $id_pc";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return $row[$column_name];
        }
    }
    
    function get_data_via_2id ($pdo, $id_pc, $table_name_parent, $column_name_parent, $table_name_child, $column_name_child, $result_column) {
        $buff_id = get_data_via_id($pdo, $id_pc, $table_name_parent, $column_name_parent);
        // получили ID для дочернего элемента, узнаем его значение
        $sql =
        "SELECT $result_column
            FROM $table_name_child
            WHERE $column_name_child = $buff_id";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return $row[$result_column];
        }
    }
    
    function get_data_array ($pdo, $id_pc, $table_name, $result_column) {
        $sql =
        "SELECT $result_column
            FROM $table_name
            WHERE ID_pc = $id_pc";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row[$result_column];
        }
        print json_encode($result_array);
    }
    
    function get_data_hw_row ($pdo, $id_hw) {
        $sql =
        "SELECT *
            FROM Hardware
            WHERE ID_hw = $id_hw";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[0] = $row['hw_name'] == NULL ? '' : $row['hw_name'];
            $result_array[1] = $row['description'] == NULL ? '' : $row['description'];
            $result_array[2] = $row['feature'] == NULL ? '' : $row['feature'];
            $result_array[3] = $row['hw_note'] == NULL ? '' : $row['hw_note'];
            $result_array[4] = $row['category'] == NULL ? '' : $row['category'];
        }
        print json_encode($result_array);
    }
    
    function get_data_pd_row ($pdo, $id_pd) {
        $sql =
        "SELECT *
            FROM Periphery
            WHERE ID_pd = $id_pd";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[0] = $row['pd_name'] == NULL ? '' : $row['pd_name'];
            $result_array[1] = $row['pd_model'] == NULL ? '' : $row['pd_model'];
            $result_array[2] = $row['feature'] == NULL ? '' : $row['feature'];
            $result_array[3] = $row['pd_inventory_number'] == NULL ? '' : $row['pd_inventory_number'];
            $result_array[4] = $row['category'] == NULL ? '' : $row['category'];
        }
        print json_encode($result_array);
    }
    
    function get_data_sw_row ($pdo, $id_sw) {
        $sql =
        "SELECT *
            FROM Software
            WHERE ID_sw = $id_sw";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[0] = $row['sw_name'] == NULL ? '' : $row['sw_name'];
            $result_array[1] = $row['licence_type'] == NULL ? '' : $row['licence_type'];
            $result_array[2] = $row['number'] == NULL ? '' : $row['number'];
            $result_array[3] = $row['sw_key'] == NULL ? '' : $row['sw_key'];
            $result_array[4] = $row['version'] == NULL ? '' : $row['version'];
            $result_array[5] = $row['sw_note'] == NULL ? '' : $row['sw_note'];
        }
        print json_encode($result_array);
    }
?>