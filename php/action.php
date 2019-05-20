<?php
    session_start();
    function connect_db () {
        try {
            $pdo = new PDO('mysql:host=localhost; dbname=Passport; charset=utf8', 'root', '62996326');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die('Подключение не удалось. Код ошибки: ' . $e->getMessage());
        }
    }
    
    function find_user ($pdo, $login) {
        $login = "'" . $login . "'";
        $empty_check = true;
        $result = $pdo->prepare("SELECT $login
            FROM User 
            WHERE login = $login LIMIT 1");
        $result->execute();
        foreach($result as $row) {
            $empty_check = false;
        }
        if ($empty_check) {
            return false;
        } else {
            return true;
        }
    }
    
    function find_password ($pdo, $login) {
        $login = "'" . $login . "'";
        $sql = "SELECT password 
            FROM User 
            WHERE login = $login LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return htmlspecialchars($row['password']);
        }
    }
    
    function get_permissions ($pdo, $login) {
        $login = "'" . $login . "'";
        $sql = "SELECT permissions
            FROM User
            WHERE login = $login LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return htmlspecialchars($row['permissions']);
        }
    }
    
    function get_db_list_с2 ($pdo) {
        $sql =
        "SELECT DISTINCT hw_name 
            FROM Hardware 
            WHERE category = '2' AND hw_name IS NOT NULL 
            ORDER BY hw_name";
        $result = $pdo->prepare($sql);
        $result->execute();
        $arr = array();
        foreach($result as $row) {
            $arr[] = htmlspecialchars($row['hw_name']);
        }
        
        $sql =
        "SELECT DISTINCT pd_name 
            FROM Periphery 
            WHERE category = '2' AND pd_name IS NOT NULL 
            ORDER BY pd_name";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            $arr[] = htmlspecialchars($row['pd_name']);
        }
        $arr = array_unique($arr);
        
        foreach($arr as $row) {
            echo "<option value=\"" . $row . "\"> " . $row;
        }
    }
    
    function get_db_list ($pdo, $table_name, $column_name, $name, $value) {
        $is_empty_select = true;
        if ($name != '' && $value != '') {
            $name = "'" . $name . "'";
            $sql =
            "SELECT DISTINCT $value 
                FROM $table_name 
                WHERE $column_name = $name AND $value IS NOT NULL 
                ORDER BY $value";
            $result = $pdo->prepare($sql);
            $result->execute();
            foreach($result as $row) {
                $is_empty_select = false;
                echo "<option value=\"" . htmlspecialchars($row[$value]) . "\"> " . htmlspecialchars($row[$value]);
            }
        } else {
            $sql =
            "SELECT DISTINCT $column_name 
                FROM $table_name 
                WHERE $column_name IS NOT NULL 
                ORDER BY $column_name";
            $result = $pdo->prepare($sql);
            $result->execute();
            foreach($result as $row) {
                $is_empty_select = false;
                echo "<option value=\"" . htmlspecialchars($row[$column_name]) . "\"> " . htmlspecialchars($row[$column_name]);
            }
        };
        return $is_empty_select;
    }
    
    function get_actual_workers ($pdo) {
        $sql ="SELECT full_name FROM Worker WHERE is_working IS NULL";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            echo "<option value=\"" . htmlspecialchars($row['full_name']) . "\"> " . htmlspecialchars($row['full_name']);
        }
        $res_row_count = $result->rowCount();
        if ($res_row_count == 0) {
            echo '<option value=""></option>';
        }
    }
    
    function get_id ($pdo, $table_name, $column_name, $name, $value) {
        $name = "'$name'";
        $sql =
        "SELECT $value
            FROM $table_name
            WHERE $column_name = $name LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        foreach($result as $row) {
            return $row[$value];
        }
        return '';
    }
    
    function get_name_for_select($var) {
        if ($var == NULL) {
            return 'IS NULL';
        } elseif ($var == '') {
            return 'IS NULL';
        } else {
            return "= '" . htmlspecialchars_decode($var) . "'";
        }
    }
    
    function get_name_for_insert($var) {
        if ($var == NULL) {
            return 'NULL';
        } elseif ($var == '') {
            return 'NULL';
        } else {
            return "'" . htmlspecialchars_decode($var) . "'";
        }
    }
    
    function get_name_for_search($var) {
        if ($var == '') {
            return '';
        } else {
            return " = '" . htmlspecialchars_decode($var) . "'";
        }
    }
    
    function get_name_for_search_contains($column, $var) {
        if ($var == '') {
            return '';
        } else {
            return " instr($column, '" . htmlspecialchars_decode($var) . "')";
        }
    }
    
    function get_hw_id ($pdo, $hw_name, $description, $feature, $hw_note, $category) {
        $hw_id = NULL;
        $hw_name_s = get_name_for_select($hw_name);
        $description_s = get_name_for_select($description);
        $feature_s = get_name_for_select($feature);
        $hw_note_s = get_name_for_select($hw_note);
        $category_s = get_name_for_select($category);
        $sql1 =
        "SELECT * 
            FROM Hardware 
            WHERE hw_name $hw_name_s AND description $description_s AND feature $feature_s AND hw_note $hw_note_s AND category $category_s LIMIT 1";
        $result = $pdo->prepare($sql1);
        $result->execute();
        foreach($result as $row) {
            $hw_id = htmlspecialchars($row['ID_hw']);
        }
        if ($hw_id == NULL && $hw_name != "" && $description != "") {
            $hw_name_i = get_name_for_insert($hw_name);
            $description_i = get_name_for_insert($description);
            $feature_i = get_name_for_insert($feature);
            $hw_note_i = get_name_for_insert($hw_note);
            $category_i = get_name_for_insert($category);
            
            $sql2 =
            "INSERT INTO Hardware (ID_hw, hw_name, description, feature, hw_note, category) 
            VALUES (NULL, $hw_name_i, $description_i, $feature_i, $hw_note_i, $category_i)";
            $result = $pdo->prepare($sql2);
            $result->execute();
            
            $result = $pdo->prepare($sql1);
            $result->execute();
            foreach($result as $row) {
                $hw_id = htmlspecialchars($row['ID_hw']);
            }
        }
        return $hw_id;
    }
    
    function get_pd_id ($pdo, $id_pc, $pd_name, $pd_model, $feature, $pd_inv_num, $category) {
        $pd_id = NULL;
        if ($pd_name != "" && $pd_model != "") {
            $id_pc_s = get_name_for_select($id_pc);
            $pd_name_s = get_name_for_select($pd_name);
            $pd_model_s = get_name_for_select($pd_model);
            $feature_s = get_name_for_select($feature);
            $pd_inv_num_s = get_name_for_select($pd_inv_num);
            $category_s = get_name_for_select($category);
            
            $sql1 =
            "SELECT ID_pd 
                FROM Periphery 
                WHERE ID_pc $id_pc_s AND pd_name $pd_name_s AND pd_model $pd_model_s AND feature $feature_s AND pd_inventory_number $pd_inv_num_s AND category $category_s LIMIT 1";
            $result_select = $pdo->prepare($sql1);
            $result_select->execute();
            
            $is_pd_exist = false;
            foreach($result_select as $row) {
                $pd_id = $row['ID_pd'];
                $is_pd_exist = true;
            }
            
            if (!$is_pd_exist) {
                $id_pc_i = get_name_for_insert($id_pc);
                $pd_name_i = get_name_for_insert($pd_name);
                $pd_model_i = get_name_for_insert($pd_model);
                $feature_i = get_name_for_insert($feature);
                $pd_inv_num_i = get_name_for_insert($pd_inv_num);
                $category_i = get_name_for_insert($category);
                
                $sql2 =
                "INSERT INTO Periphery (ID_pd, ID_pc, pd_name, pd_model, feature, pd_inventory_number, category) 
                VALUES (NULL, $id_pc_i, $pd_name_i, $pd_model_i, $feature_i, $pd_inv_num_i, $category_i)";
                $result_insert = $pdo->prepare($sql2);
                $result_insert->execute();
                
                $result_select->execute();
                foreach($result_select as $row) {
                    $pd_id = htmlspecialchars($row['ID_pd']);
                }
            }
        }
        return $pd_id;
    }
    
    function get_sw_id ($pdo, $sw_name, $licence_type, $number, $sw_key, $version, $sw_note) {
        $sw_id = NULL;
        if ($sw_name != "") {
            $sw_name_s = get_name_for_select($sw_name);
            $licence_type_s = get_name_for_select($licence_type);
            $number_s = get_name_for_select($number);
            $sw_key_s = get_name_for_select($sw_key);
            $version_s = get_name_for_select($version);
            $sw_note_s = get_name_for_select($sw_note);
            
            $sql1 =
            "SELECT ID_sw 
                FROM Software 
                WHERE sw_name $sw_name_s AND licence_type $licence_type_s AND number $number_s AND sw_key $sw_key_s AND version $version_s AND sw_note $sw_note_s LIMIT 1";
            
            $result_select = $pdo->prepare($sql1);
            $result_select->execute();
            
            $is_sw_exist = false;
            foreach($result_select as $row) {
                $sw_id = $row['ID_sw'];
                $is_sw_exist = true;
            }
            
            if (!$is_sw_exist) {
                $sw_name_i = get_name_for_insert($sw_name);
                $licence_type_i = get_name_for_insert($licence_type);
                $number_i = get_name_for_insert($number);
                $sw_key_i = get_name_for_insert($sw_key);
                $version_i = get_name_for_insert($version);
                $sw_note_i = get_name_for_insert($sw_note);
                $sql2 =
                "INSERT INTO Software (ID_sw, sw_name, licence_type, number, sw_key, version, sw_note) 
                VALUES (NULL, $sw_name_i, $licence_type_i, $number_i, $sw_key_i, $version_i, $sw_note_i)";
                    
                $result_insert = $pdo->prepare($sql2);
                $result_insert->execute();
                
                $result_select->execute();
                foreach($result_select as $row) {
                    $sw_id = htmlspecialchars($row['ID_sw']);
                }
            }
        }
        return $sw_id;
    }
    
    function register_hw($pdo, $id_pc, $id_hw) {
        if (!$id_hw == NULL) {
            $sql =
            "INSERT INTO Installed_hardware (ID_ihw, ID_pc, ID_hw) 
            VALUES (NULL, " . get_name_for_insert($id_pc) . ", " . get_name_for_insert($id_hw) . ")";
            $result = $pdo->prepare($sql);
            $result->execute();
        }
    }
    
    function register_sw($pdo, $id_pc, $id_sw) {
        if (!$id_sw == NULL) {
            $sql =
            "INSERT INTO Installed_software (ID_isw, ID_pc, ID_sw) 
            VALUES (NULL, " . get_name_for_insert($id_pc) . ", " . get_name_for_insert($id_sw) . ")";
            $result = $pdo->prepare($sql);
            $result->execute();
        }
    }
    
    function set_history_start($pdo, $id_pc, $id_worker, $started_date) {
        if (!$id_worker == NULL) {
            $sql =
            "INSERT INTO Operating_history (ID_oh, ID_pc, ID_worker, started_date, finished_date)
            VALUES (NULL, " . get_name_for_insert($id_pc) . ", " . get_name_for_insert($id_worker) . ", " . get_name_for_insert($started_date) . ", NULL)";
            $result = $pdo->prepare($sql);
            $result->execute();
        }
    }
    
    function set_history_continue($pdo, $id_pc, $id_worker, $started_date) {
        if (!$id_worker == NULL) {
            $sql =
            "SELECT * FROM Operating_history WHERE ID_pc = $id_pc AND finished_date IS NULL LIMIT 1";
            $result = $pdo->prepare($sql);
            $result->execute();
            $id_worker_old = NULL;
            foreach($result as $row) {
                $id_oh = $row['ID_oh'];
                $id_worker_old = $row['ID_worker'];
            }
            if ($id_worker_old == NULL) {
                set_history_start($pdo, $id_pc, $id_worker, $started_date);
            } elseif ($id_worker != $id_worker_old) {
                $sql =
                "UPDATE Operating_history SET finished_date = '$started_date' WHERE ID_oh = $id_oh";
                $result = $pdo->prepare($sql);
                $result->execute();
                set_history_start($pdo, $id_pc, $id_worker, $started_date);
            }
        }
    }
    
    function get_right_value($var, $var_m) {
        if (trim($var_m) != "") {
            return trim($var_m);
        } else {
            if ($var != "") {
                return $var;
            } else {
                return NULL;
            }
        }
    }
    
    function get_name($el) {
        if (preg_match('/_c\d+/', $el, $matches) == 1) {
            return substr($el, 0, strpos($el, $matches[0]));
        } else {
            preg_match('/_elem\d+/', $el, $matches);
            return substr($el, 0, strpos($el, $matches[0]));
        }
    };
    
    if (isset($_POST['save_passport'])){
        try {
            $pdo = connect_db();
            $pdo->beginTransaction();
            $data = $_POST;
            
            $manufacture_date = trim($data['manufacture_date']) == '' ? NULL : substr($data['manufacture_date'], 0, 4);
            
            $balance_date_bookkeeping = trim($data['balance_date_bookkeeping']) == '' ? NULL : $data['balance_date_bookkeeping'];
            
            $pc_name = htmlspecialchars($data['pc_name']);
            
            $pc_inv_num = trim(htmlspecialchars($data['pc_inv_num'])) == '' ? NULL : htmlspecialchars($data['pc_inv_num']);
            
            // ставим в приоритет ручной ввод
            
            // буфер-переменная для большего быстродействия (иначе много раз обращаемся к элементу массива data и проводим экранирование спец.символов) и экономии памяти
            $buff = trim(htmlspecialchars($data['buying_method_manually']));
            if ($buff != "") {
                $buying_method = get_id($pdo, 'Manufacture_method', 'method', $buff, 'ID_mm');
                if ($buying_method == "") {
                    $sql =
                    "INSERT INTO Manufacture_method (ID_mm, method) VALUES (NULL, '" . $buff . "')";
                    $result = $pdo->prepare($sql);
                    $result->execute();
                    $buying_method = get_id($pdo, 'Manufacture_method', 'method', $buff, 'ID_mm');
                }
            } else {
                $buff = trim(htmlspecialchars($data['buying_method']));
                if ($buff != "") {
                    $buying_method = get_id($pdo, 'Manufacture_method', 'method', $buff, 'ID_mm');
                } else {
                    $buying_method = NULL;
                }
            }
            
            $balance_num = trim(htmlspecialchars($data['balance_num'])) == '' ? NULL : htmlspecialchars($data['balance_num']);
            
            $balance_date = trim($data['balance_date']) == '' ? NULL : $data['balance_date'];
            
            $buff = htmlspecialchars($data['pc_place']);
            if ($buff != "") {
                $pc_place = get_id($pdo, 'Office', 'office', $buff, 'ID_office');
            } else {
                $pc_place = NULL;
            }
            
            $buff = htmlspecialchars($data['position']);
            if ($buff != "") {
                $position = get_id($pdo, 'Position', 'position', $buff, 'ID_pos');
            } else {
                $position = NULL;
            }
            
            $buff = trim(htmlspecialchars($data['responsible_person_manually']));
            if ($buff != "") {
                $responsible_person = get_id($pdo, 'Worker', 'full_name', $buff, 'ID_worker');
                if ($responsible_person == "") {
                    $sql =
                    "INSERT INTO Worker (ID_worker, full_name, position, office) VALUES (NULL, '" . $buff . "', NULL, NULL)";
                    $result = $pdo->prepare($sql);
                    $result->execute();
                    $responsible_person = get_id($pdo, 'Worker', 'full_name', htmlspecialchars($data['responsible_person_manually']), 'ID_worker');
                }
            } else {
                $buff = trim(htmlspecialchars($data['responsible_person']));
                if ($buff != "") {
                    $responsible_person = get_id($pdo, 'Worker', 'full_name', $buff, 'ID_worker');
                } else {
                    $responsible_person = NULL;
                }
            }
            
            // регистрация компьютера, получение ID
            $v1 = get_name_for_insert($manufacture_date);
            $v2 = get_name_for_insert($balance_date_bookkeeping);
            $v3 = get_name_for_insert($pc_name);
            $v4 = get_name_for_insert($pc_inv_num);
            $v5 = get_name_for_insert($buying_method);
            $v6 = get_name_for_insert($balance_num);
            $v7 = get_name_for_insert($balance_date);
            $v8 = get_name_for_insert($pc_place);
            $v9 = get_name_for_insert($position);
            $v10 = get_name_for_insert($responsible_person);
            
            $sql = 
            "INSERT INTO Computer 
            (ID_pc, manufacture_date, bookkeeping_balance_sheet, pc_name, inventory_number, manufacture_method, doc_balance_num, 
            doc_balance_date, installation_site_office, installation_site_position, responsible) 
            VALUES 
            (NULL, $v1, $v2, $v3, $v4, $v5, $v6, $v7, $v8, $v9, $v10)";
            $result = $pdo->prepare($sql);
            $result->execute();
            
            $w1 = get_name_for_select($manufacture_date);
            $w2 = get_name_for_select($balance_date_bookkeeping);
            $w3 = get_name_for_select($pc_name);
            $w4 = get_name_for_select($pc_inv_num);
            $w5 = get_name_for_select($buying_method);
            $w6 = get_name_for_select($balance_num);
            $w7 = get_name_for_select($balance_date);
            $w8 = get_name_for_select($pc_place);
            $w9 = get_name_for_select($position);
            $w10 = get_name_for_select($responsible_person);
            
            $sql =
            "SELECT *
                FROM Computer
                WHERE manufacture_date $w1 AND bookkeeping_balance_sheet $w2 AND pc_name $w3 AND inventory_number $w4 AND manufacture_method $w5 AND doc_balance_num $w6 AND 
                doc_balance_date $w7 AND installation_site_office $w8 AND installation_site_position $w9 AND responsible $w10 LIMIT 1";
            $result = $pdo->prepare($sql);
            $result->execute();
            foreach($result as $row) {
                $ID_pc = $row['ID_pc'];
            }
            
            // проверка существования указанной материнской платы в БД. Если её нет, она будет создана
            // экранирование необходимо прямо здесь: иначе возможна инъекция на этапе передачи значения
            $mb_model = get_right_value(htmlspecialchars($data['mb_model']), htmlspecialchars($data['mb_model_manually']));
            $mb_note = get_right_value(htmlspecialchars($data['mb_note']), htmlspecialchars($data['mb_note_manually']));
            $ID_mb = get_hw_id($pdo, 'Системная плата', $mb_model, NULL, $mb_note, '0');
            
            // оперативная память
            $ram_type = get_right_value(htmlspecialchars($data['ram_type']), htmlspecialchars($data['ram_type_manually']));
            $ram_capacity = get_right_value(htmlspecialchars($data['ram_capacity']), htmlspecialchars($data['ram_capacity_manually']));
            $ram_note = get_right_value(htmlspecialchars($data['ram_note']), htmlspecialchars($data['ram_note_manually']));
            $ID_ram = get_hw_id($pdo, 'Оперативная память', $ram_type, $ram_capacity, $ram_note, '0');
            
            // процессор
            $cpu_model = get_right_value(htmlspecialchars($data['cpu_model']), htmlspecialchars($data['cpu_model_manually']));
            $cpu_frequency = get_right_value(htmlspecialchars($data['cpu_frequency']), htmlspecialchars($data['cpu_frequency_manually']));
            $cpu_note = get_right_value(htmlspecialchars($data['cpu_note']), htmlspecialchars($data['cpu_note_manually']));
            $ID_cpu = get_hw_id($pdo, 'ЦП', $cpu_model, $cpu_frequency, $cpu_note, '0');
            
            // регистрация главной части аппаратного обеспечения
            register_hw($pdo, $ID_pc, $ID_mb);
            register_hw($pdo, $ID_pc, $ID_ram);
            register_hw($pdo, $ID_pc, $ID_cpu);
            
            $names_arr = array();
            foreach($data as $key => $value) {
                if ((!strpos($key, 'name_c') === false || !strpos($key, 'name_e') === false) && $key != 'pc_name') {
                    $names_arr[] = $key;
                }
            }
            foreach($names_arr as $el) {
                $name_now = get_name($el);
                $category_now = preg_match('/_c\d+/', $el, $matches) ? substr($matches[0], 2) : NULL;
                $index_now = preg_match('/_elem\d+/', $el, $matches) ? substr($matches[0], 5) : NULL;
                if ($category_now == '1' || $category_now == '4') {
                    $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                 htmlspecialchars($data[$name_now . '_manually_c'. $category_now . '_elem' . $index_now]));
                    $buff_description = get_right_value(htmlspecialchars($data['description_c' . $category_now . '_elem' . $index_now]),
                                                        htmlspecialchars($data['description_manually_c' . $category_now . '_elem' . $index_now]));
                    $buff_feature = get_right_value(htmlspecialchars($data['feature_c' . $category_now . '_elem' . $index_now]),
                                                        htmlspecialchars($data['feature_manually_c' . $category_now . '_elem' . $index_now]));
                    $buff_note = get_right_value(htmlspecialchars($data['hw_note_c' . $category_now . '_elem' . $index_now]),
                                                        htmlspecialchars($data['hw_note_manually_c' . $category_now . '_elem' . $index_now]));
                    $buff_id = get_hw_id($pdo, $buff_name, $buff_description, $buff_feature, $buff_note, $category_now);
                    register_hw($pdo, $ID_pc, $buff_id);
                } elseif ($category_now == '2') {
                    $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                 htmlspecialchars($data[$name_now . '_manually_c'. $category_now . '_elem' . $index_now]));
                    $buff_description = get_right_value(htmlspecialchars($data['description_c' . $category_now . '_elem' . $index_now]),
                                                        htmlspecialchars($data['description_manually_c' . $category_now . '_elem' . $index_now]));
                    $buff_feature = NULL;
                    if (isset($data['feature_c' . $category_now . '_elem' . $index_now])) {
                        $buff_feature = get_right_value(htmlspecialchars($data['feature_c' . $category_now . '_elem' . $index_now]),
                                                        htmlspecialchars($data['feature_manually_c' . $category_now . '_elem' . $index_now]));
                    }
                    $buff_inv_num = NULL;
                    if (isset($data['pd_inv_num_c' . $category_now . '_elem' . $index_now])) {
                        $buff_inv_num = htmlspecialchars($data['pd_inv_num_c' . $category_now . '_elem' . $index_now]);
                    }
                    $buff_note = NULL;
                    if (isset($data['hw_note_c' . $category_now . '_elem' . $index_now])) {
                        $buff_note = get_right_value(htmlspecialchars($data['hw_note_c' . $category_now . '_elem' . $index_now]),
                                                        htmlspecialchars($data['hw_note_manually_c' . $category_now . '_elem' . $index_now]));
                    }
                    if ($buff_inv_num != "" AND $buff_inv_num != NULL) {
                        $buff_id = get_pd_id($pdo, $ID_pc, $buff_name, $buff_description, $buff_feature, $buff_inv_num, $category_now);
                        // регистрировать периферийное устройство в специальной таблице не нужно, как как создаётся запись, где устройство привязано к ПК
                    } else {
                        $buff_id = get_hw_id($pdo, $buff_name, $buff_description, $buff_feature, $buff_note, $category_now);
                        register_hw($pdo, $ID_pc, $buff_id);
                    }
                } elseif ($category_now == '3') {
                    $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                 htmlspecialchars($data[$name_now . '_manually_c'. $category_now . '_elem' . $index_now]));
                    $buff_description = get_right_value(htmlspecialchars($data['description_c' . $category_now . '_elem' . $index_now]),
                                                        htmlspecialchars($data['description_manually_c' . $category_now . '_elem' . $index_now]));
                    $buff_note = get_right_value(htmlspecialchars($data['hw_note_c' . $category_now . '_elem' . $index_now]),
                                                        htmlspecialchars($data['hw_note_manually_c' . $category_now . '_elem' . $index_now]));
                    $buff_id = get_hw_id($pdo, $buff_name, $buff_description, NULL, $buff_note, $category_now);
                    register_hw($pdo, $ID_pc, $buff_id);
                } elseif ($name_now == "pd_name") {
                    $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                 htmlspecialchars($data[$name_now . '_manually_c7_elem' . $index_now]));
                    $buff_description = get_right_value(htmlspecialchars($data['pd_model_c7_elem' . $index_now]),
                                                        htmlspecialchars($data['pd_model_manually_c7_elem' . $index_now]));
                    $buff_feature = get_right_value(htmlspecialchars($data['feature_c7_elem' . $index_now]),
                                                        htmlspecialchars($data['feature_manually_c7_elem' . $index_now]));
                    $buff_inv_num = htmlspecialchars($data['pd_inv_num_c7_elem' . $index_now]);
                    $buff_id = get_pd_id($pdo, $ID_pc, $buff_name, $buff_description, $buff_feature, $buff_inv_num, '7');
                } elseif ($name_now == "sw_name") {
                    $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                 htmlspecialchars($data[$name_now . '_manually_elem' . $index_now]));
                    $buff_licence_type = get_right_value(htmlspecialchars($data['licence_type_elem' . $index_now]),
                                                 htmlspecialchars($data['licence_type_manually_elem' . $index_now]));
                    $buff_licence_num = htmlspecialchars($data['licence_num_elem' . $index_now]);
                    $buff_licence_key = htmlspecialchars($data['licence_key_elem' . $index_now]);
                    $buff_version = get_right_value(htmlspecialchars($data['version_elem' . $index_now]),
                                                 htmlspecialchars($data['version_manually_elem' . $index_now]));
                    $buff_note = get_right_value(htmlspecialchars($data['sw_note_elem' . $index_now]),
                                                 htmlspecialchars($data['sw_note_manually_elem' . $index_now]));
                    $buff_id = get_sw_id($pdo, $buff_name, $buff_licence_type, $buff_licence_num, $buff_licence_key, $buff_version, $buff_note);
                    if (trim($buff_id) != "") {
                        register_sw($pdo, $ID_pc, $buff_id);
                    }
                }            
            }
            if ($data['description_c5'] != "") {
                $buff_description = get_right_value(htmlspecialchars($data['description_c5']),
                                                    htmlspecialchars($data['description_manually_c5']));
                $buff_feature = get_right_value(htmlspecialchars($data['feature_c5']),
                                                    htmlspecialchars($data['feature_manually_c5']));
                $buff_note = get_right_value(htmlspecialchars($data['hw_note_c5']),
                                                    htmlspecialchars($data['hw_note_manually_c5']));
                $buff_id = get_hw_id($pdo, 'Корпус', $buff_description, $buff_feature, $buff_note, '5');
                register_hw($pdo, $ID_pc, $buff_id);
            }
            if ($data['description_c6'] != "") {
                $buff_description = get_right_value(htmlspecialchars($data['description_c6']),
                                                    htmlspecialchars($data['description_manually_c6']));
                $buff_inv_num = htmlspecialchars($data['pd_inv_num_c6']);
                $buff_id = get_pd_id($pdo, $ID_pc, 'Принтер', $buff_description, NULL, $buff_inv_num, '6');
            }
            set_history_start($pdo, $ID_pc, $responsible_person, $data['responsible_since']);
            $pdo->commit();
            header('Location: ../list/index.php');
        } catch (Exception $e) {
            echo '<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Паспорт</title>
                </head>
                <body style="background-color: #c0c0c0">' .
                '<div style="margin-top: 18%; width: 80%; margin-left: 10%; background-color: #eeeeee; border-radius: 10pt">
                <p>&nbsp;
                <p style="font-size:30pt; color: #800000; text-align: center">Ошибка записи: ' . $e->getMessage() . '.<p>&nbsp;</div>' .
                '</body>
                </html>';
            $pdo->rollBack();
        }
    } elseif (isset($_POST['update_passport'])) {
        try {
            $pdo = connect_db();
            $pdo->beginTransaction();
            $ID_pc = $_GET['id'];
            
            if (is_pc_exist($pdo, $ID_pc)) {
                $data = $_POST;
                
                $manufacture_date = trim($data['manufacture_date']) == '' ? NULL : substr($data['manufacture_date'], 0, 4);
                
                $balance_date_bookkeeping = trim($data['balance_date_bookkeeping']) == '' ? NULL : $data['balance_date_bookkeeping'];
                
                $pc_name = htmlspecialchars($data['pc_name']);
                
                $pc_inv_num = trim(htmlspecialchars($data['pc_inv_num'])) == '' ? NULL : htmlspecialchars($data['pc_inv_num']);
                
                $buff = trim(htmlspecialchars($data['buying_method_manually']));
                if ($buff != "") {
                    $buying_method = get_id($pdo, 'Manufacture_method', 'method', $buff, 'ID_mm');
                    if ($buying_method == "") {
                        $sql =
                        "INSERT INTO Manufacture_method (ID_mm, method) VALUES (NULL, '" . $buff . "')";
                        $result = $pdo->prepare($sql);
                        $result->execute();
                        $buying_method = get_id($pdo, 'Manufacture_method', 'method', $buff, 'ID_mm');
                    }
                } else {
                    $buff = trim(htmlspecialchars($data['buying_method']));
                    if ($buff != "") {
                        $buying_method = get_id($pdo, 'Manufacture_method', 'method', $buff, 'ID_mm');
                    } else {
                        $buying_method = NULL;
                    }
                }
                
                $balance_num = trim(htmlspecialchars($data['balance_num'])) == '' ? NULL : htmlspecialchars($data['balance_num']);
                
                $balance_date = trim($data['balance_date']) == '' ? NULL : $data['balance_date'];
                
                $buff = htmlspecialchars($data['pc_place']);
                if ($buff != "") {
                    $pc_place = get_id($pdo, 'Office', 'office', $buff, 'ID_office');
                } else {
                    $pc_place = NULL;
                }
                
                $buff = htmlspecialchars($data['position']);
                if ($buff != "") {
                    $position = get_id($pdo, 'Position', 'position', $buff, 'ID_pos');
                } else {
                    $position = NULL;
                }
                
                $buff = trim(htmlspecialchars($data['responsible_person_manually']));
                if ($buff != "") {
                    $responsible_person = get_id($pdo, 'Worker', 'full_name', $buff, 'ID_worker');
                    if ($responsible_person == "") {
                        $sql =
                        "INSERT INTO Worker (ID_worker, full_name, position, office) VALUES (NULL, '" . $buff . "', NULL, NULL)";
                        $result = $pdo->prepare($sql);
                        $result->execute();
                        $responsible_person = get_id($pdo, 'Worker', 'full_name', htmlspecialchars($data['responsible_person_manually']), 'ID_worker');
                    }
                } else {
                    $buff = trim(htmlspecialchars($data['responsible_person']));
                    if ($buff != "") {
                        $responsible_person = get_id($pdo, 'Worker', 'full_name', $buff, 'ID_worker');
                    } else {
                        $responsible_person = NULL;
                    }
                }
                
                // регистрация изменений
                $v1 = get_name_for_insert($manufacture_date);
                $v2 = get_name_for_insert($balance_date_bookkeeping);
                $v3 = get_name_for_insert($pc_name);
                $v4 = get_name_for_insert($pc_inv_num);
                $v5 = get_name_for_insert($buying_method);
                $v6 = get_name_for_insert($balance_num);
                $v7 = get_name_for_insert($balance_date);
                $v8 = get_name_for_insert($pc_place);
                $v9 = get_name_for_insert($position);
                $v10 = get_name_for_insert($responsible_person);
                
                $sql = 
                "UPDATE Computer SET 
                manufacture_date = $v1, bookkeeping_balance_sheet = $v2, pc_name = $v3, inventory_number = $v4, manufacture_method = $v5, doc_balance_num = $v6, 
                doc_balance_date = $v7, installation_site_office = $v8, installation_site_position = $v9, responsible = $v10 WHERE ID_pc = " . $ID_pc;
                
                $result = $pdo->prepare($sql);
                $result->execute();
                
                // получение списка старого оборудования, таблица Hardware
                $sql =
                "SELECT ID_hw FROM Installed_hardware
                WHERE ID_pc = $ID_pc";
                $result = $pdo->prepare($sql);
                $result->execute();
                $arr_hw_old = array();
                foreach($result as $row) {
                    if (array_key_exists($row['ID_hw'], $arr_hw_old)) {
                        $arr_hw_old[$row['ID_hw']]++;
                    } else {
                        $arr_hw_old[$row['ID_hw']] = 1;
                    }
                }
                $arr_hw_new = array();
                
                // получение списка периферийных устройств
                $sql =
                "SELECT ID_pd FROM Periphery
                WHERE ID_pc = $ID_pc";
                $result = $pdo->prepare($sql);
                $result->execute();
                $arr_pd_old = array();
                foreach($result as $row) {
                    if (array_key_exists($row['ID_pd'], $arr_pd_old)) {
                        $arr_pd_old[$row['ID_pd']]++;
                    } else {
                        $arr_pd_old[$row['ID_pd']] = 1;
                    }
                }
                $arr_pd_new = array();
                
                // получение списка старого ПО, таблица Software
                $sql =
                "SELECT ID_sw FROM Installed_software
                WHERE ID_pc = $ID_pc";
                $result = $pdo->prepare($sql);
                $result->execute();
                $arr_sw_old = array();
                foreach($result as $row) {
                    if (array_key_exists($row['ID_sw'], $arr_sw_old)) {
                        $arr_sw_old[$row['ID_sw']]++;
                    } else {
                        $arr_sw_old[$row['ID_sw']] = 1;
                    }
                }
                $arr_sw_new = array();
                
                $mb_model = get_right_value(htmlspecialchars($data['mb_model']), htmlspecialchars($data['mb_model_manually']));
                $mb_note = get_right_value(htmlspecialchars($data['mb_note']), htmlspecialchars($data['mb_note_manually']));
                $ID_mb = get_hw_id($pdo, 'Системная плата', $mb_model, NULL, $mb_note, '0');
                $arr_hw_new += [$ID_mb => 1];
                
                // оперативная память
                $ram_type = get_right_value(htmlspecialchars($data['ram_type']), htmlspecialchars($data['ram_type_manually']));
                $ram_capacity = get_right_value(htmlspecialchars($data['ram_capacity']), htmlspecialchars($data['ram_capacity_manually']));
                $ram_note = get_right_value(htmlspecialchars($data['ram_note']), htmlspecialchars($data['ram_note_manually']));
                $ID_ram = get_hw_id($pdo, 'Оперативная память', $ram_type, $ram_capacity, $ram_note, '0');
                $arr_hw_new += [$ID_ram => 1];
                
                // процессор
                $cpu_model = get_right_value(htmlspecialchars($data['cpu_model']), htmlspecialchars($data['cpu_model_manually']));
                $cpu_frequency = get_right_value(htmlspecialchars($data['cpu_frequency']), htmlspecialchars($data['cpu_frequency_manually']));
                $cpu_note = get_right_value(htmlspecialchars($data['cpu_note']), htmlspecialchars($data['cpu_note_manually']));
                $ID_cpu = get_hw_id($pdo, 'ЦП', $cpu_model, $cpu_frequency, $cpu_note, '0');
                $arr_hw_new += [$ID_cpu => 1];
                
                $names_arr = array();
                foreach($data as $key => $value) {
                    if ((!strpos($key, 'name_c') === false || !strpos($key, 'name_e') === false) && $key != 'pc_name') {
                        $names_arr[] = $key;
                    }
                }
                foreach($names_arr as $el) {
                    $name_now = get_name($el);
                    $category_now = preg_match('/_c\d+/', $el, $matches) ? substr($matches[0], 2) : NULL;
                    $index_now = preg_match('/_elem\d+/', $el, $matches) ? substr($matches[0], 5) : NULL;
                    if ($category_now == '1' || $category_now == '4') {
                        $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                     htmlspecialchars($data[$name_now . '_manually_c'. $category_now . '_elem' . $index_now]));
                        $buff_description = get_right_value(htmlspecialchars($data['description_c' . $category_now . '_elem' . $index_now]),
                                                            htmlspecialchars($data['description_manually_c' . $category_now . '_elem' . $index_now]));
                        $buff_feature = get_right_value(htmlspecialchars($data['feature_c' . $category_now . '_elem' . $index_now]),
                                                            htmlspecialchars($data['feature_manually_c' . $category_now . '_elem' . $index_now]));
                        $buff_note = get_right_value(htmlspecialchars($data['hw_note_c' . $category_now . '_elem' . $index_now]),
                                                            htmlspecialchars($data['hw_note_manually_c' . $category_now . '_elem' . $index_now]));
                        $buff_id = get_hw_id($pdo, $buff_name, $buff_description, $buff_feature, $buff_note, $category_now);
                        if (array_key_exists($buff_id, $arr_hw_new)) {
                            $arr_hw_new[$buff_id]++;
                        } else {
                            $arr_hw_new[$buff_id] = 1;
                        }
                    } elseif ($category_now == '2') {
                        $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                     htmlspecialchars($data[$name_now . '_manually_c'. $category_now . '_elem' . $index_now]));
                        $buff_description = get_right_value(htmlspecialchars($data['description_c' . $category_now . '_elem' . $index_now]),
                                                            htmlspecialchars($data['description_manually_c' . $category_now . '_elem' . $index_now]));
                        $buff_feature = NULL;
                        if (isset($data['feature_c' . $category_now . '_elem' . $index_now])) {
                            $buff_feature = get_right_value(htmlspecialchars($data['feature_c' . $category_now . '_elem' . $index_now]),
                                                            htmlspecialchars($data['feature_manually_c' . $category_now . '_elem' . $index_now]));
                        }
                        $buff_inv_num = NULL;
                        if (isset($data['pd_inv_num_c' . $category_now . '_elem' . $index_now])) {
                            $buff_inv_num = htmlspecialchars($data['pd_inv_num_c' . $category_now . '_elem' . $index_now]);
                        }
                        $buff_note = NULL;
                        if (isset($data['hw_note_c' . $category_now . '_elem' . $index_now])) {
                            $buff_note = get_right_value(htmlspecialchars($data['hw_note_c' . $category_now . '_elem' . $index_now]),
                                                            htmlspecialchars($data['hw_note_manually_c' . $category_now . '_elem' . $index_now]));
                        }
                        if ($buff_inv_num != "" AND $buff_inv_num != NULL) {
                            $buff_id = get_pd_id($pdo, $ID_pc, $buff_name, $buff_description, $buff_feature, $buff_inv_num, $category_now);
                            // регистрировать периферийное устройство в специальной таблице не нужно, как как создаётся запись, где устройство привязано к ПК
                            
                            if (array_key_exists($buff_id, $arr_pd_new)) {
                                $arr_pd_new[$buff_id]++;
                            } else {
                                $arr_pd_new[$buff_id] = 1;
                            }
                        } else {
                            $buff_id = get_hw_id($pdo, $buff_name, $buff_description, $buff_feature, $buff_note, $category_now);
                            if (array_key_exists($buff_id, $arr_hw_new)) {
                                $arr_hw_new[$buff_id]++;
                            } else {
                                $arr_hw_new[$buff_id] = 1;
                            }
                        }
                    } elseif ($category_now == '3') {
                        $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                     htmlspecialchars($data[$name_now . '_manually_c'. $category_now . '_elem' . $index_now]));
                        $buff_description = get_right_value(htmlspecialchars($data['description_c' . $category_now . '_elem' . $index_now]),
                                                            htmlspecialchars($data['description_manually_c' . $category_now . '_elem' . $index_now]));
                        $buff_note = get_right_value(htmlspecialchars($data['hw_note_c' . $category_now . '_elem' . $index_now]),
                                                            htmlspecialchars($data['hw_note_manually_c' . $category_now . '_elem' . $index_now]));
                        $buff_id = get_hw_id($pdo, $buff_name, $buff_description, NULL, $buff_note, $category_now);
                        if (array_key_exists($buff_id, $arr_hw_new)) {
                            $arr_hw_new[$buff_id]++;
                        } else {
                            $arr_hw_new[$buff_id] = 1;
                        }
                    } elseif ($name_now == "pd_name") {
                        $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                     htmlspecialchars($data[$name_now . '_manually_c7_elem' . $index_now]));
                        $buff_description = get_right_value(htmlspecialchars($data['pd_model_c7_elem' . $index_now]),
                                                            htmlspecialchars($data['pd_model_manually_c7_elem' . $index_now]));
                        $buff_feature = get_right_value(htmlspecialchars($data['feature_c7_elem' . $index_now]),
                                                            htmlspecialchars($data['feature_manually_c7_elem' . $index_now]));
                        $buff_inv_num = htmlspecialchars($data['pd_inv_num_c7_elem' . $index_now]);
                        $buff_id = get_pd_id($pdo, $ID_pc, $buff_name, $buff_description, $buff_feature, $buff_inv_num, '7');
                        if (array_key_exists($buff_id, $arr_pd_new)) {
                            $arr_pd_new[$buff_id]++;
                        } else {
                            $arr_pd_new[$buff_id] = 1;
                        }
                    } elseif ($name_now == "sw_name") {
                        $buff_name = get_right_value(htmlspecialchars($data[$el]),
                                                     htmlspecialchars($data[$name_now . '_manually_elem' . $index_now]));
                        $buff_licence_type = get_right_value(htmlspecialchars($data['licence_type_elem' . $index_now]),
                                                     htmlspecialchars($data['licence_type_manually_elem' . $index_now]));
                        $buff_licence_num = htmlspecialchars($data['licence_num_elem' . $index_now]);
                        $buff_licence_key = htmlspecialchars($data['licence_key_elem' . $index_now]);
                        $buff_version = get_right_value(htmlspecialchars($data['version_elem' . $index_now]),
                                                     htmlspecialchars($data['version_manually_elem' . $index_now]));
                        $buff_note = get_right_value(htmlspecialchars($data['sw_note_elem' . $index_now]),
                                                     htmlspecialchars($data['sw_note_manually_elem' . $index_now]));
                        $buff_id = get_sw_id($pdo, $buff_name, $buff_licence_type, $buff_licence_num, $buff_licence_key, $buff_version, $buff_note);
                        if (trim($buff_id) != "") {
                            if (array_key_exists($buff_id, $arr_sw_new)) {
                                $arr_sw_new[$buff_id]++;
                            } else {
                                $arr_sw_new[$buff_id] = 1;
                            }
                        }
                    }            
                }
                if ($data['description_c5'] != "") {
                    $buff_description = get_right_value(htmlspecialchars($data['description_c5']),
                                                        htmlspecialchars($data['description_manually_c5']));
                    $buff_feature = get_right_value(htmlspecialchars($data['feature_c5']),
                                                        htmlspecialchars($data['feature_manually_c5']));
                    $buff_note = get_right_value(htmlspecialchars($data['hw_note_c5']),
                                                        htmlspecialchars($data['hw_note_manually_c5']));
                    $buff_id = get_hw_id($pdo, 'Корпус', $buff_description, $buff_feature, $buff_note, '5');
                    if (array_key_exists($buff_id, $arr_hw_new)) {
                        $arr_hw_new[$buff_id]++;
                    } else {
                        $arr_hw_new[$buff_id] = 1;
                    }
                }
                if ($data['description_c6'] != "") {
                    $buff_description = get_right_value(htmlspecialchars($data['description_c6']),
                                                        htmlspecialchars($data['description_manually_c6']));
                    $buff_inv_num = htmlspecialchars($data['pd_inv_num_c6']);
                    $buff_id = get_pd_id($pdo, $ID_pc, 'Принтер', $buff_description, NULL, $buff_inv_num, '6');
                    if (array_key_exists($buff_id, $arr_pd_new)) {
                        $arr_pd_new[$buff_id]++;
                    } else {
                        $arr_pd_new[$buff_id] = 1;
                    }
                }
                
                // обновление списка установленного аппаратного обеспечения
                // рассматривается ситуация, когда ставится несколько единиц одинакового оборудования на одну машину
                foreach($arr_hw_old as $key => $value) {
                    // в новом списке такого оборудования нет, удаляем
                    if (!array_key_exists($key, $arr_hw_new)) {
                        $sql =
                        "DELETE FROM Installed_hardware WHERE ID_pc = $ID_pc AND ID_hw = $key";
                        $result = $pdo->prepare($sql);
                        $result->execute();
                    } else {
                        if ($value == $arr_hw_new[$key]) {
                        }
                        // в новом списке такое оборудование есть, но его меньше
                        elseif ($value > $arr_hw_new[$key]) {
                            $diff = $value - $arr_hw_new[$key];
                            $sql =
                            "SELECT ID_ihw FROM Installed_hardware WHERE ID_pc = $ID_pc AND ID_hw = $key LIMIT 1";
                            $result = $pdo->prepare($sql);
                            $result->execute();
                            $diff_now = 0;
                            foreach($result as $row) {
                                $sql =
                                "DELETE FROM Installed_hardware WHERE ID_ihw = " . $row['ID_ihw'];
                                $result_del = $pdo->prepare($sql);
                                $result_del->execute();
                                $diff_now++;
                                if ($diff == $diff_now) {
                                    break;
                                }
                            }
                        }
                        // в новом списке такое оборудование есть, но его больше
                        elseif ($value < $arr_hw_new[$key]) {
                            $diff = $arr_hw_new[$key] - $value;
                            for ($diff_now = 0; $diff_now < $diff; $diff_now++) {
                                register_hw($pdo, $ID_pc, $key);
                            }
                        }
                    }
                }
                
                foreach($arr_hw_new as $key => $value) {
                    // появилось новое оборудование, добавляем: столько, каково количество этого оборудования
                    if (!array_key_exists($key, $arr_hw_old)) {
                        for ($i = 0; $i < $value; $i++) {
                            register_hw($pdo, $ID_pc, $key);
                        }      
                    }            
                }
                
                /* обновление списка установленного ПО
                специфика: собираем УНИКАЛЬНОЕ программное обеспечение, т.к. даже если поставить виртуальную машину и установить на неё то же ПО, что на хосте
                (или на другой виртуальной машине того же хоста), в данной предметной области учёт нескольких таких одинаковых единиц абсолютно не имеет смысла.*/
                foreach($arr_sw_old as $key => $value) {
                    // в новом списке это ПО отсутствует
                    if (!array_key_exists($key, $arr_sw_new)) {
                        $sql =
                        "DELETE FROM Installed_software WHERE ID_pc = $ID_pc AND ID_sw = $key";
                        $result_del = $pdo->prepare($sql);
                        $result_del->execute();
                    }
                }
                
                foreach($arr_sw_new as $key => $value) {
                    // появилось новое ПО, которого не было в старом списке
                    if (!array_key_exists($key, $arr_sw_old)) {
                        register_sw($pdo, $ID_pc, $key);
                    }
                }
                
                foreach($arr_pd_old as $key => $value) {
                    // в новом списке периферийных устройств элемента не оказалось
                    if (!array_key_exists($key, $arr_pd_new)) {
                        $sql =
                        "DELETE FROM Periphery WHERE ID_pc = $ID_pc AND ID_pd = $key";
                        $result_del = $pdo->prepare($sql);
                        $result_del->execute();
                    }
                }
                
                set_history_continue($pdo, $ID_pc, $responsible_person, $data['responsible_since']);
            }
            $pdo->commit();
            header('Location: ../list/index.php');
        } catch (Exception $e) {
            echo '<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Паспорт</title>
                </head>
                <body style="background-color: #c0c0c0">' .
                '<div style="margin-top: 18%; width: 80%; margin-left: 10%; background-color: #eeeeee; border-radius: 10pt">
                <p>&nbsp;
                <p style="font-size:30pt; color: #800000; text-align: center">Ошибка записи: ' . $e->getMessage() . '.<p>&nbsp;</div>' .
                '</body>
                </html>';
            $pdo->rollBack();
        }
    } elseif (isset($_POST["save_repair"])) {
        $pdo = connect_db();
        $ID_pc = get_name_for_insert($_GET['id']);
        $rp_type = get_name_for_insert($_POST['rp_type']);
        $rp_date = get_name_for_insert($_POST['rp_date']);
        $repairer = get_name_for_insert($_POST['repairer']);
        $sql =
        "INSERT INTO Repair (ID_rp, ID_pc, rp_type, rp_date, repairer) VALUES (NULL, $ID_pc, $rp_type, $rp_date, $repairer)";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../repair_list/index.php?id=' . $_GET['id']);
    } elseif (isset($_POST["save_user"])) {
        $pdo = connect_db();
        $login = get_name_for_insert($_POST['login']);
        $password = get_name_for_insert($_POST['password']);
        $permissions = get_name_for_insert($_POST['permissions']);
        $sql =
        "INSERT INTO User (login, password, permissions) VALUES ($login, $password, $permissions)";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../users_list/index.php');
    } elseif (isset($_POST["update_user"])) {
        $pdo = connect_db();
        $login = get_name_for_insert($_GET["login"]);
        $password = get_name_for_insert($_POST["password"]);
        $permissions = get_name_for_insert($_POST["permissions"]);
        $sql = 
            "UPDATE User SET 
            password = $password, permissions = $permissions WHERE login = $login";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../users_list/index.php');
    } elseif (isset($_POST["save_worker"])) {
        $pdo = connect_db();
        $data = $_POST;
        $full_name = get_name_for_insert($data["full_name"]);
        
        $buff = trim(htmlspecialchars($data['position_manually']));
        if ($buff != "") {
            $position = get_id($pdo, 'Position', 'position', $buff, 'ID_pos');
            if ($position == "") {
                $sql =
                "INSERT INTO Position (ID_pos, position) VALUES (NULL, '" . $buff . "')";
                $result = $pdo->prepare($sql);
                $result->execute();
                $position = get_id($pdo, 'Position', 'position', $buff, 'ID_pos');
            }
        } else {
            $buff = htmlspecialchars($data['position']);
            if ($buff != "") {
                $position = get_id($pdo, 'Position', 'position', $buff, 'ID_pos');
            } else {
                $position = NULL;
            }
        }
        $position = get_name_for_insert($position);
        
        $buff = htmlspecialchars($data['office']);
        if ($buff != "") {
            $office = get_id($pdo, 'Office', 'office', $buff, 'ID_office');
        } else {
            $office = NULL;
        }
        $office = get_name_for_insert($office);
        
        $is_working = get_name_for_insert($_POST["is_working"]);
        
        $sql =
        "INSERT INTO Worker (ID_worker, full_name, position, office, is_working) VALUES (NULL, $full_name, $position, $office, $is_working)";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../workers_list/index.php');
    } elseif (isset($_POST["update_worker"])) {
        $pdo = connect_db();
        $data = $_POST;
        $full_name = get_name_for_insert($data["full_name"]);
        
        $buff = trim(htmlspecialchars($data['position_manually']));
        if ($buff != "") {
            $position = get_id($pdo, 'Position', 'position', $buff, 'ID_pos');
            if ($position == "") {
                $sql =
                "INSERT INTO Position (ID_pos, position) VALUES (NULL, '" . $buff . "')";
                $result = $pdo->prepare($sql);
                $result->execute();
                $position = get_id($pdo, 'Position', 'position', $buff, 'ID_pos');
            }
        } else {
            $buff = htmlspecialchars($data['position']);
            if ($buff != "") {
                $position = get_id($pdo, 'Position', 'position', $buff, 'ID_pos');
            } else {
                $position = NULL;
            }
        }
        $position = get_name_for_insert($position);
        
        $buff = htmlspecialchars($data['office']);
        if ($buff != "") {
            $office = get_id($pdo, 'Office', 'office', $buff, 'ID_office');
        } else {
            $office = NULL;
        }
        $office = get_name_for_insert($office);
        
        $is_working = get_name_for_insert($_POST["is_working"]);
        
        $ID_worker = get_name_for_insert($_GET['id']);
        
        $sql =
        "UPDATE Worker SET 
            full_name = $full_name, position = $position, office = $office, is_working = $is_working WHERE ID_worker = $ID_worker";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../workers_list/index.php');
    } elseif (isset($_POST["create_office"])) {
        $pdo = connect_db();
        $office = get_name_for_insert($_POST["create_office"]);
        $sql =
        "INSERT INTO Office (ID_office, office) VALUES (NULL, $office)";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../offices/index.php');
    } elseif (isset($_POST["save_pd"])) {
        $pdo = connect_db();
        $data = $_POST;
        
        $category = htmlspecialchars($data['category']);
        if ($category == 2 || $category == 6) {
            $feature = NULL;
        } else {
            $feature = get_right_value(htmlspecialchars($data['feature']), htmlspecialchars($data['feature_manually']));
        }
        if ($category == 6) {
            $pd_name = 'Принтер';
        } else {
            $pd_name = get_right_value(htmlspecialchars($data['pd_name']), htmlspecialchars($data['pd_name_manually']));
        }
        $pd_model = get_right_value(htmlspecialchars($data['pd_model']), htmlspecialchars($data['pd_model_manually']));
        $inv_num = trim(htmlspecialchars($data['pd_inv_num']));
        
        $pd_name = get_name_for_insert($pd_name);
        $pd_model = get_name_for_insert($pd_model);
        $feature = get_name_for_insert($feature);
        $inv_num = get_name_for_insert($inv_num);
        $category = get_name_for_insert($category);
        $sql =
        "INSERT INTO Periphery (ID_pd, ID_pc, pd_name, pd_model, feature, pd_inventory_number, category) VALUES (NULL, NULL, $pd_name, $pd_model, $feature, $inv_num, $category)";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../periphery_list/index.php');
    } elseif (isset($_POST["update_pd"])) {
        $pdo = connect_db();
        $data = $_POST;
        
        $ID_pd = $_GET['id'];
        $category = htmlspecialchars($data['category']);
        if ($category == 2 || $category == 6) {
            $feature = NULL;
        } else {
            $feature = get_right_value(htmlspecialchars($data['feature']), htmlspecialchars($data['feature_manually']));
        }
        if ($category == 6) {
            $pd_name = 'Принтер';
        } else {
            $pd_name = get_right_value(htmlspecialchars($data['pd_name']), htmlspecialchars($data['pd_name_manually']));
        }
        $pd_model = get_right_value(htmlspecialchars($data['pd_model']), htmlspecialchars($data['pd_model_manually']));
        $inv_num = trim(htmlspecialchars($data['pd_inv_num']));
        
        $pd_name = get_name_for_insert($pd_name);
        $pd_model = get_name_for_insert($pd_model);
        $feature = get_name_for_insert($feature);
        $inv_num = get_name_for_insert($inv_num);
        $category = get_name_for_insert($category);
        $sql =
        "UPDATE Periphery SET
            pd_name = $pd_name, pd_model = $pd_model, feature = $feature, pd_inventory_number = $inv_num, category = $category WHERE ID_pd = $ID_pd";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../periphery_list/index.php');
    }
    
    function delete_passport($pdo, $id_pc) {
        $sql =
        "DELETE FROM Computer WHERE ID_pc = $id_pc";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../list/index.php');
    }
    
    function delete_user($pdo, $login) {
        $sql =
        "DELETE FROM User WHERE login = $login";
        $result = $pdo->prepare($sql);
        $result->execute();
        header('Location: ../users_list/index.php');
    }
    
    function is_responsible($pdo, $id_worker) {
        $sql =
        "SELECT * FROM Operating_history WHERE ID_worker = $id_worker LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $res_row_count = $result->rowCount();
        if ($res_row_count == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function is_responsible_now($pdo, $id_worker) {
        $sql =
        "SELECT * FROM Operating_history WHERE ID_worker = $id_worker AND finished_date IS NULL LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $res_row_count = $result->rowCount();
        if ($res_row_count == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function delete_worker($pdo, $id_worker) {
        if (is_responsible($pdo, $id_worker)) {
            print json_encode('Удаление невозможно, так как сотрудник является или являлся ответственным за эксплуатацию ПК');
        } else {
            $sql =
            "DELETE FROM Worker WHERE ID_worker = $id_worker";
            $result = $pdo->prepare($sql);
            $result->execute();
            print json_encode(NULL);
            header('Location: ../workers_list/index.php');
        }
    }
    
    function is_using($pdo, $id_office) {
        $sql =
        "SELECT * FROM Computer WHERE installation_site_office = $id_office LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $res_row_count = $result->rowCount();
        if ($res_row_count == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function delete_office($pdo, $id_office) {
        if (is_using($pdo, $id_office)) {
            print json_encode('Удаление невозможно, так как в базе данных присутствуют компьютеры в этом кабинете');
        } else {
            $sql =
            "DELETE FROM Office WHERE ID_office = $id_office";
            $result = $pdo->prepare($sql);
            $result->execute();
            print json_encode(NULL);
            header('Location: ../offices/index.php');
        }
    }
    
    function delete_pd($pdo, $id_pd) {
        $sql =
        "DELETE FROM Periphery WHERE ID_pd = $id_pd";
        $result = $pdo->prepare($sql);
        $result->execute();
        print json_encode(NULL);
    }
    
    function is_pc_exist($pdo, $id_pc) {
        $sql =
        "SELECT ID_pc FROM Computer WHERE ID_pc = $id_pc LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $res_row_count = $result->rowCount();
        if ($res_row_count == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function is_user_exist($pdo, $login) {
        $sql =
        "SELECT login FROM User WHERE login = $login LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $res_row_count = $result->rowCount();
        if ($res_row_count == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function is_worker_exist($pdo, $id) {
        $sql =
        "SELECT ID_worker FROM Worker WHERE ID_worker = $id LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $res_row_count = $result->rowCount();
        if ($res_row_count == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function is_pd_exist($pdo, $id) {
        $sql =
        "SELECT ID_pd FROM Periphery WHERE ID_pd = $id LIMIT 1";
        $result = $pdo->prepare($sql);
        $result->execute();
        $res_row_count = $result->rowCount();
        if ($res_row_count == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function update_office($pdo, $id_office, $office_name) {
        $office_name = get_name_for_insert($office_name);
        $sql =
        "UPDATE Office SET 
            office = $office_name WHERE ID_office = $id_office";
        $result = $pdo->prepare($sql);
        $result->execute();
        print json_encode(NULL);
    }
    
    function search_pc($pdo, $pc_name, $pc_place, $pc_inv_num) {
        $name       = get_name_for_search_contains('pc_name', $pc_name);
        $place      = get_name_for_search(get_id($pdo, 'Office', 'office', $pc_place, 'ID_office'));
        $inv_num    = get_name_for_search_contains('inventory_number', $pc_inv_num);
        
        $start = "SELECT * FROM Computer WHERE ";
        $sql1 = "$name";
        $sql2 = "installation_site_office $place";
        $sql3 = "$inv_num";
                
        $num =  $name       == '' ? 0 :   1;
        $num += $place      == '' ? 0 :  10;
        $num += $inv_num    == '' ? 0 : 100;
        
        switch ($num) {
            case 0:
                $sql = "SELECT * FROM Computer";
                break;
            case 1:
                $sql = $start . $sql1;
                break;
            case 10:
                $sql = $start . $sql2;
                break;
            case 11:
                $sql = "$start $sql1 AND $sql2";
                break;
            case 100:
                $sql = $start . $sql3;
                break;
            case 101:
                $sql = "$start $sql1 AND $sql3";
                break;
            case 110:
                $sql = "$start $sql2 AND $sql3";
                break;
            case 111:
                $sql = "$start $sql1 AND $sql2 AND $sql3";
                break;
        }
        $result = $pdo->prepare($sql);
        $result->execute();
        $result_array = array();
        foreach($result as $row) {
            $result_array[] = $row['pc_name'];
            $result_array[] = get_data_via_2id($pdo, $row['ID_pc'], 'Computer', 'installation_site_office', 'Office', 'ID_office', 'office');
            $result_array[] = $row['inventory_number'] == NULL ? '' : $row['inventory_number'];
            $result_array[] = $row['ID_pc'];
        }
        print json_encode($result_array);
    }
?>