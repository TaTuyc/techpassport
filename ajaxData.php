<?php
include 'action.php';
include 'fillprint.php';
$pdo = connect_db();
if (isset($_POST["mb_model"]) && !empty($_POST["mb_model"])) {
   get_db_list($pdo, 'Hardware', 'hw_name', 'Системная плата', 'hw_note');
} elseif (isset($_POST["ram_type"]) && !empty($_POST["ram_type"])) {
    if ($_POST["need"] == "rc") {
        get_db_list($pdo,'Hardware','description',$_POST["ram_type"],'feature');
    } else {
        get_db_list($pdo,'Hardware','description',$_POST["ram_type"],'hw_note');
    }    
} elseif (isset($_POST["cpu_model"]) && !empty($_POST["cpu_model"])) {
    if ($_POST["need"] == "cf") {
        get_db_list($pdo,'Hardware','description',$_POST["cpu_model"],'feature');
    } else {
        get_db_list($pdo,'Hardware','description',$_POST["cpu_model"],'hw_note');
    }
} elseif (isset($_POST["is_call"])) {
   $parent_value = $_POST["parent"];
   $name = $_POST["name"];
   //$parent_select = "[$index]$name" . "_$category";
   if ($_POST["is_call"] == '1') {
      $index = $_POST["index"];
      $category = $_POST["category"];
      if ($name == 'hw_name') {
         get_db_list($pdo, 'Hardware', 'hw_name', $parent_value, 'description');
         get_db_list($pdo, 'Periphery', 'pd_name', $parent_value, 'pd_model');
      }
   } elseif ($_POST["is_call"] == 'f') {
      get_db_list($pdo, 'Hardware', 'description', $parent_value, 'feature');
      get_db_list($pdo, 'Periphery', 'pd_model', $parent_value, 'feature');
   } elseif ($_POST["is_call"] == 'n') {
      get_db_list($pdo, 'Hardware', 'description', $parent_value, 'hw_note');
   } elseif ($_POST["is_call"] == '1_pd') {
      get_db_list($pdo, 'Periphery', 'pd_name', $parent_value, 'pd_model');
   } elseif ($_POST["is_call"] == 'f_pd') {
      get_db_list($pdo, 'Periphery', 'pd_model', $parent_value, 'feature');
   } elseif ($_POST["is_call"] == '2') {
      get_db_list($pdo, 'Software', 'sw_name', $parent_value, 'licence_type');
   } elseif ($_POST["is_call"] == 'v_sw') {
      get_db_list($pdo, 'Software', 'sw_name', $parent_value, 'version');
   } elseif ($_POST["is_call"] == 'n_sw') {
      get_db_list($pdo, 'Software', 'sw_name', $parent_value, 'sw_note');
   } elseif ($_POST["is_call"] == '3') {
      $category = $_POST["category"];
      if ($name == 'description') {
         get_db_list($pdo, 'Hardware', 'description', $parent_value, 'feature');
      }
   } elseif ($_POST["is_call"] == '3n') {
      get_db_list($pdo, 'Hardware', 'description', $parent_value, 'hw_note');
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
   }
}
?>