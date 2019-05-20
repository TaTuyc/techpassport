<?php
include_once 'action.php';
include_once 'fillprint.php';
$pdo = connect_db();
if (isset($_POST["mb_model"])) {
   if (get_db_list($pdo, 'Hardware', 'hw_name', 'Системная плата', 'hw_note')) {
      echo '<option value="">';
   }
} elseif (isset($_POST["ram_type"])) {
    if ($_POST["need"] == "rc") {
        if (get_db_list($pdo,'Hardware','description',$_POST["ram_type"],'feature')) {
         echo '<option value="">';
        }
    } else {
        if (get_db_list($pdo,'Hardware','description',$_POST["ram_type"],'hw_note')) {
         echo '<option value="">';
        }
    }    
} elseif (isset($_POST["cpu_model"])) {
    if ($_POST["need"] == "cf") {
        if (get_db_list($pdo,'Hardware','description',$_POST["cpu_model"],'feature')) {
         echo '<option value="">';
        }
    } else {
        if (get_db_list($pdo,'Hardware','description',$_POST["cpu_model"],'hw_note')) {
         echo '<option value="">';
        }
    }
} elseif (isset($_POST["is_call"])) {
   $parent_value = $_POST["parent"];
   if ($_POST["is_call"] == '1') {
      if (get_db_list($pdo, 'Hardware', 'hw_name', $parent_value, 'description') && get_db_list($pdo, 'Periphery', 'pd_name', $parent_value, 'pd_model')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == 'f') {
      if (get_db_list($pdo, 'Hardware', 'description', $parent_value, 'feature') && get_db_list($pdo, 'Periphery', 'pd_model', $parent_value, 'feature')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == 'n') {
      if (get_db_list($pdo, 'Hardware', 'description', $parent_value, 'hw_note')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == '1_pd') {
      if (get_db_list($pdo, 'Periphery', 'pd_name', $parent_value, 'pd_model')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == 'f_pd') {
      if (get_db_list($pdo, 'Periphery', 'pd_model', $parent_value, 'feature')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == '2') {
      if (get_db_list($pdo, 'Software', 'sw_name', $parent_value, 'licence_type')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == 'v_sw') {
      if (get_db_list($pdo, 'Software', 'sw_name', $parent_value, 'version')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == 'n_sw') {
      if (get_db_list($pdo, 'Software', 'sw_name', $parent_value, 'sw_note')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == '3') {
      if (get_db_list($pdo, 'Hardware', 'description', $parent_value, 'feature')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == '3n') {
      if (get_db_list($pdo, 'Hardware', 'description', $parent_value, 'hw_note')) {
         echo '<option value="">';
      }
   } elseif ($_POST["is_call"] == 'pd_cat') {
      if (get_db_list($pdo, 'Periphery', 'category', $parent_value, 'pd_name')) {
         echo '<option value="">Выберите тип устройства';
      }
   }
} elseif (isset($_POST["print_data"])) {
   if ($_POST["print_data"] == 'hw') {
      get_data_array($pdo, $_POST["ID_pc"], 'Installed_hardware', 'ID_hw');
   } elseif ($_POST["print_data"] == 'hw_id') {
      get_data_hw_row($pdo, $_POST["ID_hw"]);
   } elseif ($_POST["print_data"] == 'pd') {
      get_data_array($pdo, $_POST["ID_pc"], 'Periphery', 'ID_pd');
   } elseif ($_POST["print_data"] == 'pd_id') {
      get_data_pd_row($pdo, $_POST["ID_pd"]);
   } elseif ($_POST["print_data"] == 'sw') {
      get_data_array($pdo, $_POST["ID_pc"], 'Installed_software', 'ID_sw');
   } elseif ($_POST["print_data"] == 'sw_id') {
      get_data_sw_row($pdo, $_POST["ID_sw"]);
   } elseif ($_POST["print_data"] == 'page_id') {
      get_passport_list($pdo, $_POST["ID_page"], $_SESSION['portion_size']);
   } elseif ($_POST["print_data"] == 'page_list') {
      get_page_list($pdo, $_POST["ID_page"]);
   } elseif ($_POST["print_data"] == 'pas_3') {
      get_pas_info($pdo, $_POST["ID_page"]);
   }
} elseif (isset($_POST["update_cookie"])) {
   if ($_POST["update_cookie"] == 'portion_size') {
      $_SESSION['portion_size'] = $_POST["portion_size"];
      print json_encode($_SESSION['portion_size']);
   }
} elseif (isset($_POST["delete_passport"])) {
   delete_passport($pdo, $_POST["delete_passport"]);
} elseif (isset($_POST["is_pc_exist"])) {
   echo is_pc_exist($pdo, $_POST["is_pc_exist"]);
} elseif (isset($_POST["get_history"])) {
   echo get_history($pdo, $_POST["get_history"]);
} elseif (isset($_POST["get_repair_list"])) {
   echo get_repair_list($pdo, $_POST["get_repair_list"]);
} elseif (isset($_POST["get_permissions"])) {
   echo get_permissions($pdo, $_SESSION['logged_user']);
} elseif (isset($_POST["get_users_list"])) {
   echo get_users_list($pdo);
} elseif (isset($_POST["delete_user"])) {
   delete_user($pdo, htmlspecialchars($_POST["delete_user"]));
} elseif (isset($_POST["is_user_exist"])) {
   echo is_user_exist($pdo, htmlspecialchars($_POST["is_user_exist"]));
} elseif (isset($_POST["get_user_data"])) {
   echo get_user_data($pdo, htmlspecialchars($_POST["get_user_data"]));
} elseif (isset($_POST["get_workers_list"])) {
   echo get_workers_list($pdo);
} elseif (isset($_POST["delete_worker"])) {
   echo delete_worker($pdo, $_POST["delete_worker"]);
} elseif (isset($_POST["is_worker_exist"])) {
   echo is_worker_exist($pdo, $_POST["is_worker_exist"]);
} elseif (isset($_POST["get_worker_data"])) {
   echo get_worker_data($pdo, $_POST["get_worker_data"]);
} elseif (isset($_POST["get_offices_list"])) {
   echo get_offices_list($pdo);
} elseif (isset($_POST["delete_office"])) {
   echo delete_office($pdo, $_POST["delete_office"]);
} elseif (isset($_POST["update_office"])) {
   echo update_office($pdo, $_POST["update_office"], htmlspecialchars($_POST["new_office_name"]));
} elseif (isset($_POST["get_pd_list"])) {
   echo get_pd_list($pdo);
} elseif (isset($_POST["delete_pd"])) {
   echo delete_pd($pdo, $_POST["delete_pd"]);
} elseif (isset($_POST["is_pd_exist"])) {
   echo is_pd_exist($pdo, $_POST["is_pd_exist"]);
} elseif (isset($_POST["get_pd_data"])) {
   echo get_pd_data($pdo, $_POST["get_pd_data"]);
} elseif (isset($_POST["search_pc"])) {
   search_pc($pdo, $_POST["pc_name"], $_POST["pc_place"], $_POST["pc_inv_num"]);
}
?>