<?php
    include_once 'action.php';
    $pdo_copy = connect_db();
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
        return NULL;
    }
    
    function get_data_via_2id ($pdo, $id_pc, $table_name_parent, $column_name_parent, $table_name_child, $column_name_child, $result_column) {
        $buff_id = get_data_via_id($pdo, $id_pc, $table_name_parent, $column_name_parent);
        // получили ID для дочернего элемента, узнаем его значение
        if ($buff_id != NULL) {
            $sql =
            "SELECT $result_column
                FROM $table_name_child
                WHERE $column_name_child = $buff_id";
            $result = $pdo->prepare($sql);
            $result->execute();
            foreach($result as $row) {
                return $row[$result_column];
            }
        } else {
            return '';
        }
    }
    
    function get_responsible_since ($pdo, $id_pc) {
        $sql = "SELECT started_date FROM Operating_history WHERE ID_pc = $id_pc AND finished_date IS NULL";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return $row['started_date'];
        }
    }
    
    function get_worker_via_id ($pdo, $id_worker) {
        $sql = "SELECT full_name FROM Worker WHERE ID_worker = $id_worker";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return $row['full_name'];
        }
    }
    
    function get_history ($pdo, $id_pc) {
        $sql =
        "SELECT * FROM Operating_history WHERE ID_pc = $id_pc";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        $result_array[] = str_pad('ФИО ответственного', 60);
        $result_array[] = str_pad('Начало срока', 15);
        $result_array[] = str_pad('Конец срока', 15);
        foreach($result as $row) {
            $result_array[] = str_pad(get_worker_via_id($pdo, $row['ID_worker']), 60);
            $result_array[] = str_pad($row['started_date'], 15);
            $result_array[] = str_pad($row['finished_date'], 15);
        }
        print json_encode($result_array);
    }
    
    function get_repair_list($pdo, $id_pc) {
        $sql =
        "SELECT * FROM Repair WHERE ID_pc = $id_pc";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['repairer'];
            $result_array[] = $row['rp_date'];
            $result_array[] = $row['rp_type'];
        }
        print json_encode($result_array);
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
            $result_array[] = $row[$result_column] == NULL ? '' : $row[$result_column];
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
    
    function get_pas_info($pdo, $id_pc) {
        $sql =
        "SELECT * FROM Computer WHERE ID_pc = $id_pc";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['pc_name'];
            $result_array[] = get_data_via_2id($pdo, $row['ID_pc'], 'Computer', 'installation_site_office', 'Office', 'ID_office', 'office');
            $result_array[] = $row['inventory_number'] == NULL ? '' : $row['inventory_number'];
        }
        print json_encode($result_array);
    }
    
    function get_passport_list($pdo, $id_page, $portion_size) {
        $cnt = 0;
        $min_cnt = ($id_page - 1) * $portion_size;
        /*$max_cnt = $id_page * $portion_size - 1;
        $max_lim = $pdo->query("SELECT COUNT(*) FROM Computer")->fetchColumn();
        if ($max_cnt > $max_lim) {
            $max_cnt = $max_lim;
        }*/
        $sql =
        "SELECT * FROM Computer LIMIT " . $min_cnt . "," . $portion_size;
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['pc_name'];
            $result_array[] = get_data_via_2id($pdo, $row['ID_pc'], 'Computer', 'installation_site_office', 'Office', 'ID_office', 'office');
            $result_array[] = $row['inventory_number'] == NULL ? '' : $row['inventory_number'];
            $result_array[] = $row['ID_pc'];
            $cnt++;
        }
        print json_encode($result_array);
    }
    
    function get_page_list($pdo, $id_page) {
        $result = array();
        $result[] = $pdo->query("SELECT COUNT(*) FROM Computer")->fetchColumn();
        $result[] = $_SESSION['portion_size'];
        print json_encode($result);
    }
    
    if (isset($_POST['name'])) {
        if ($_POST['name'] == 'manufacture_date') {
            echo get_data_via_id($pdo_copy, $_POST['id'], 'Computer', 'manufacture_date');
        } elseif ($_POST['name'] == 'buying_method') {
            echo get_data_via_2id($pdo_copy, $_POST['id'], 'Computer', 'manufacture_method', 'Manufacture_method', 'ID_mm', 'method');
        } elseif ($_POST['name'] == 'balance_date_bookkeeping') {
            echo get_data_via_id($pdo_copy, $_POST['id'], 'Computer', 'bookkeeping_balance_sheet');
        } elseif ($_POST['name'] == 'balance_num') {
            echo get_data_via_id($pdo_copy, $_POST['id'], 'Computer', 'doc_balance_num');
        } elseif ($_POST['name'] == 'balance_date') {
            echo get_data_via_id($pdo_copy, $_POST['id'], 'Computer', 'doc_balance_date');
        } elseif ($_POST['name'] == 'pc_name') {
            echo get_data_via_id($pdo_copy, $_POST['id'], 'Computer', 'pc_name');
        } elseif ($_POST['name'] == 'pc_place') {
            echo get_data_via_2id($pdo_copy, $_POST['id'], 'Computer', 'installation_site_office', 'Office', 'ID_office', 'office');
        } elseif ($_POST['name'] == 'position') {
            echo get_data_via_2id($pdo_copy, $_POST['id'], 'Computer', 'installation_site_position', 'Worker', 'ID_worker', 'position');
        } elseif ($_POST['name'] == 'pc_inv_num') {
            echo get_data_via_id($pdo_copy, $_POST['id'], 'Computer', 'inventory_number');
        } elseif ($_POST['name'] == 'responsible_person') {
            echo get_data_via_2id($pdo_copy, $_POST['id'], 'Computer', 'responsible', 'Worker', 'ID_worker', 'full_name');
        } elseif ($_POST['name'] == 'responsible_since') {
            echo get_responsible_since($pdo_copy, $_POST['id']);
        }
    }
?>