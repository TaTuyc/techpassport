<?php
    include_once 'action.php';
    $pdo_copy = connect_db();
    // разворачивание данных для создания отчёта
    
    function get_any_data_via_id ($pdo, $column_name, $table_name, $search_column_name, $value) {
        if ($value != NULL) {
            $sql =
            "SELECT $column_name
                FROM $table_name
                WHERE $search_column_name = $value LIMIT 1";
            $result = $pdo->prepare($sql);
            $result->execute();
            foreach($result as $row) {
                return $row[$column_name];
            }
            return NULL;
        } else {
            return NULL;
        }
    }
    
    function get_data_via_id ($pdo, $id_pc, $table_name, $column_name) {
        $sql =
        "SELECT $column_name
            FROM $table_name
            WHERE ID_pc = $id_pc LIMIT 1";
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
                WHERE $column_name_child = $buff_id LIMIT 1";
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
        $sql = "SELECT started_date FROM Operating_history WHERE ID_pc = $id_pc AND finished_date IS NULL LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return $row['started_date'];
        }
    }
    
    function get_worker_via_id ($pdo, $id_worker) {
        $sql = "SELECT full_name FROM Worker WHERE ID_worker = $id_worker LIMIT 1";
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
            WHERE ID_hw = $id_hw LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['hw_name'] == NULL ? '' : $row['hw_name'];
            $result_array[] = $row['description'] == NULL ? '' : $row['description'];
            $result_array[] = $row['feature'] == NULL ? '' : $row['feature'];
            $result_array[] = $row['hw_note'] == NULL ? '' : $row['hw_note'];
            $result_array[] = $row['category'] == NULL ? '' : $row['category'];
        }
        print json_encode($result_array);
    }
    
    function get_data_pd_row ($pdo, $id_pd) {
        $sql =
        "SELECT *
            FROM Periphery
            WHERE ID_pd = $id_pd LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['pd_name'] == NULL ? '' : $row['pd_name'];
            $result_array[] = $row['pd_model'] == NULL ? '' : $row['pd_model'];
            $result_array[] = $row['feature'] == NULL ? '' : $row['feature'];
            $result_array[] = $row['pd_inventory_number'] == NULL ? '' : $row['pd_inventory_number'];
            $result_array[] = $row['category'] == NULL ? '' : $row['category'];
        }
        print json_encode($result_array);
    }
    
    function get_data_sw_row ($pdo, $id_sw) {
        $sql =
        "SELECT *
            FROM Software
            WHERE ID_sw = $id_sw LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['sw_name'] == NULL ? '' : $row['sw_name'];
            $result_array[] = $row['licence_type'] == NULL ? '' : $row['licence_type'];
            $result_array[] = $row['number'] == NULL ? '' : $row['number'];
            $result_array[] = $row['sw_key'] == NULL ? '' : $row['sw_key'];
            $result_array[] = $row['version'] == NULL ? '' : $row['version'];
            $result_array[] = $row['sw_note'] == NULL ? '' : $row['sw_note'];
        }
        print json_encode($result_array);
    }
    
    function get_pas_info($pdo, $id_pc) {
        $sql =
        "SELECT * FROM Computer WHERE ID_pc = $id_pc LIMIT 1";
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
            echo get_data_via_2id($pdo_copy, $_POST['id'], 'Computer', 'installation_site_position', 'Position', 'ID_pos', 'position');
        } elseif ($_POST['name'] == 'pc_inv_num') {
            echo get_data_via_id($pdo_copy, $_POST['id'], 'Computer', 'inventory_number');
        } elseif ($_POST['name'] == 'responsible_person') {
            echo get_data_via_2id($pdo_copy, $_POST['id'], 'Computer', 'responsible', 'Worker', 'ID_worker', 'full_name');
        } elseif ($_POST['name'] == 'responsible_since') {
            echo get_responsible_since($pdo_copy, $_POST['id']);
        }
    }
    
    function get_users_list($pdo) {
        $sql =
        "SELECT * FROM User";
        $result = $pdo->prepare($sql);
        $result->execute();
        $users = array();
        foreach($result as $row) {
            $users[] = $row['login'];
            $users[] = str_repeat('*', strlen($row['password']));
            $users[] = $row['permissions'];
        }
        print json_encode($users);
    }
    
    function get_user_data($pdo, $login) {
        $sql =
        "SELECT * FROM User WHERE login = $login LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $users = array();
        foreach($result as $row) {
            $users[] = $row['login'];
            $users[] = $row['password'];
            $users[] = $row['permissions'];
        }
        print json_encode($users);
    }
    
    function get_workers_list($pdo) {
        $sql =
        "SELECT * FROM Worker";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['full_name'];
            $result_array[] = get_any_data_via_id($pdo, 'position', 'Position', 'ID_pos', $row['position']);
            $result_array[] = get_any_data_via_id($pdo, 'office', 'Office', 'ID_office', $row['office']);
            $result_array[] = $row['is_working'] == NULL ? 'Работает' : 'Не работает';
            $result_array[] = $row['ID_worker'];
        }
        print json_encode($result_array);
    }
    
    function get_worker_data($pdo, $id) {
        $sql =
        "SELECT * FROM Worker WHERE ID_worker = $id LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['full_name'];
            $result_array[] = get_any_data_via_id($pdo, 'position', 'Position', 'ID_pos', $row['position']);
            $result_array[] = get_any_data_via_id($pdo, 'office', 'Office', 'ID_office', $row['office']);
            $result_array[] = $row['is_working'] == NULL ? 'Работает' : 'Не работает';
        }
        print json_encode($result_array);
    }
    
    function get_offices_list($pdo) {
        $sql =
        "SELECT * FROM Office";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['office'];
            $result_array[] = $row['ID_office'];
        }
        print json_encode($result_array);
    }
    
    function get_pd_list($pdo) {
        $sql =
        "SELECT * FROM Periphery WHERE ID_pc IS NULL";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['pd_name'];
            $result_array[] = $row['pd_model'];
            $result_array[] = $row['feature'] == NULL ? '' : $row['feature'];
            $result_array[] = $row['pd_inventory_number'] == NULL ? '' : $row['pd_inventory_number'];
            if ($row['category'] == 2) {
                $result_array[] = 'Устройство отображения';
            } elseif ($row['category'] == 6) {
                $result_array[] = 'Печатающее устройство';
            } elseif ($row['category'] == 7) {
                $result_array[] = 'Другие периферийные устройства';
            } else {
                $result_array[] = $row['category'];
            }
            $result_array[] = $row['ID_pd'];
        }
        print json_encode($result_array);
    }
    
    function get_pd_data($pdo, $id_pd) {
        $sql =
        "SELECT * FROM Periphery WHERE ID_pd = $id_pd LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['category'];
            $result_array[] = $row['pd_name'];
            $result_array[] = $row['pd_model'];
            $result_array[] = $row['feature'] == NULL ? '' : $row['feature'];
            $result_array[] = $row['pd_inventory_number'] == NULL ? '' : $row['pd_inventory_number'];
        }
        print json_encode($result_array);
    }
    
    function get_workers_name($pdo, $position) {
        $pos_id = get_id($pdo, 'Position', 'position', $position, 'ID_pos');
        $sql =
        "SELECT full_name FROM Worker WHERE position = $pos_id AND is_working IS NULL LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return $row['full_name'];
        }
        return '';
    }
?>