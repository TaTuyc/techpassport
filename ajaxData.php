<?php
include 'action.php';
include 'fillprint.php';
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
   $name = $_POST["name"];
   if ($_POST["is_call"] == '1') {
      $index = $_POST["index"];
      $category = $_POST["category"];
      if ($name == 'hw_name') {
         if (get_db_list($pdo, 'Hardware', 'hw_name', $parent_value, 'description') && get_db_list($pdo, 'Periphery', 'pd_name', $parent_value, 'pd_model')) {
            echo '<option value="">';
         }
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
      $category = $_POST["category"];
      if ($name == 'description') {
         if (get_db_list($pdo, 'Hardware', 'description', $parent_value, 'feature')) {
            echo '<option value="">';
         }
      }
   } elseif ($_POST["is_call"] == '3n') {
      if (get_db_list($pdo, 'Hardware', 'description', $parent_value, 'hw_note')) {
         echo '<option value="">';
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
   }
} elseif (isset($_POST["update_cookie"])) {
   if ($_POST["update_cookie"] == 'portion_size') {
      $_SESSION['portion_size'] = $_POST["portion_size"];
      print json_encode($_SESSION['portion_size']);
   }
}
?>