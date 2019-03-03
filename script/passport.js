qq_func = function(){};
var buff_arr_names = [];
var buff_arr_data = [];

max_index = 0;

$(document).on('submit', 'form', function(e) {
    var form = this;
    if (document.getElementsByName('responsible_person')[0].value == "") {
        if (document.getElementsByName('responsible_person_manually')[0].value == "") {
            e.preventDefault();
            alert("Назначьте ответственного!");
        } else {
            form.submit();
        }
    } else {
        form.submit();
    }
});

function set_block() {    
    var x = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            get_permissions: ''},
        dataType: "json",
        success: function(data){
            if (data != 0) {
                if (document.getElementsByName('pc_place')[0].value != '') {
                    document.getElementsByName('pc_place')[0].setAttribute('disabled', 'disabled');
                }
                if (document.getElementsByName('position')[0].value != '') {
                    document.getElementsByName('position')[0].setAttribute('disabled', 'disabled');
                }
            }
        }
    }).responseText;
}

function eventChange(parent_id, call_type, name_, category_, index_) {
    var parent_var = $('#' + parent_id).val();
    if (parent_var != "") {
        var wow = $.ajax({
            type: 'POST',
            url: '../php/ajaxData.php',
            async: false,
            data: {
                is_call: call_type,
                parent: parent_var,
                name: name_,
                category: category_,
                index: index_
            }, success: function(html){
                if (call_type == 1) {
                    $('#description_c' + category_ + '_elem' + index_).html(html);
                    param = 'description_c' + category_ + '_elem' + index_;
                    eventChange(param, 'f', 'description', category_, index_);
                    eventChange(param, 'n', 'description', category_, index_);
                } else if (call_type == 'f') {
                    $('#feature_c' + category_ + '_elem' + index_).html(html);
                } else if (call_type == 'n') {
                    $('#hw_note_c' + category_ + '_elem' + index_).html(html);
                } else if (call_type == '1_pd') {
                    $('#pd_model_c' + category_ + '_elem' + index_).html(html);
                    eventChange('pd_model_c' + category_ + '_elem' + index_, 'f_pd', name_, category_, index_);
                } else if (call_type == 'f_pd') {
                    $('#feature_c' + category_ + '_elem' + index_).html(html);
                } else if (call_type == 2) {
                    $('#licence_type_elem' + index_).html(html);
                    eventChange(parent_id, 'v_sw', name_, category_, index_);
                    eventChange(parent_id, 'n_sw', name_, category_, index_);
                } else if (call_type == 'v_sw') {
                    $('#version_elem' + index_).html(html);
                } else if (call_type == 'n_sw') {
                    $('#sw_note_elem' + index_).html(html);
                } else if (call_type == 3) {
                    $('#feature_c' + category_).html(html);
                    eventChange('description_c' + category_, '3n', name_, category_, null);
                } else if (call_type == '3n') {
                    $('#hw_note_c' + category_).html(html);
                }
            }
        }).responseText;
    }else{
        if (call_type == 1) {
            $('#description_c' + category_ + '_elem' + index_).html('<option value="">Выберите модель</option>');
            $('#feature_c' + category_ + '_elem' + index_).html('<option value="">Значение</option>');
            $('#hw_note_c' + category_ + '_elem' + index_).html('<option value="">Примечание</option>');
        } else if (call_type == 'f') {
            $('#feature_c' + category_ + '_elem' + index_).html('<option value="">Значение</option>');
        } else if (call_type == 'n') {
            $('#hw_note_c' + category_ + '_elem' + index_).html('<option value="">Примечание</option>');
        } else if (call_type == '1_pd') {
            $('#pd_model_c' + category_ + '_elem' + index_).html('<option value="">Выберите описание</option>');
            $('#feature_c' + category_ + '_elem' + index_).html('<option value="">Значение</option>');
        } else if (call_type == 'f_pd') {
            $('#feature_c' + category_ + '_elem' + index_).html('<option value="">Значение</option>');
        } else if (call_type == 2) {
            $('#licence_type_elem' + index_).html('<option value="">Выберите тип лицензии</option>');
        } else if (call_type == 'v_sw') {
            $('#version_elem' + index_).html('<option value="">Выберите версию</option>');
        } else if (call_type == 'n_sw') {
            $('#sw_note_elem' + index_).html('<option value="">Примечание</option>');
        } else if (call_type == 3) {
            $('#feature_c' + category_).html('<option value="">Значение</option>');
            $('#hw_note_c' + category_).html('<option value="">Примечание</option>');
        } else if (call_type == '3n') {
            $('#hw_note_c' + category_).html('<option value="">Примечание</option>');
        }
    }
}

function set_value(id, data) {
    if (id != null) {
        document.getElementById(id).value = data;
    }
}

function get_available_id(name) {
    var elems = document.getElementsByTagName('*');
    var buff;
    for (i = 0; i < elems.length; i++) {
        buff = elems[i].getAttribute('name');
        if (buff != null) {
            if (buff.indexOf(name) != -1) {
                if ($('#' + buff).val() == '') {
                    return buff;
                }
            }
        }
    }
    buff = getCategory(name);
    if (name == 'sw_name') {
        document.getElementById('add_btn_sw').click();
    } else {
        document.getElementById('add_btn_c' + buff).click();
    }			
    return get_available_id(name);
}

function set_hw_item(data) {
    var category = data[4];
    buff = get_available_id('hw_name_c' + category);
    buff_elem = getIndex(buff);
    set_value(buff, data[0]);
    $('#' + buff).trigger('change');
    buff = 'description_c' + category + '_elem' + buff_elem;
    buff1 = 'feature_c' + category + '_elem' + buff_elem;
    buff2 = 'hw_note_c' + category + '_elem' + buff_elem;
    set_value(buff, data[1]);
    $('#' + buff).trigger('change');
    if (document.getElementById(buff1) != null) {
        set_value(buff1, data[2]);
    }
    set_value(buff2, data[3]);
    if (category == 2) {
        document.getElementById('div_pd_inv_num_c2_elem' + buff_elem).style.display = "none";
        document.getElementById('switch_btn_c2_elem' + buff_elem).style.display = "none";
    }
}

function get_hw_item(id_hw) {
    var buff;
    var wow = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            print_data: 'hw_id',
            ID_hw: id_hw},
        dataType: "json",
        success: function(data){
            var buff;
            for (i = 0; i < 5; i++) {
                if (data[i] == 'null') {
                    data[i] = '';
                }
            }
            if (data[4] == '0') {
                if (data[0] == 'Системная плата') {
                    set_value('mb_model', data[1]);
                    $('#mb_model').trigger('change');
                    set_value('mb_note', data[3]);
                } else if (data[0] == 'Оперативная память') {
                    set_value('ram_type', data[1]);
                    $('#ram_type').trigger('change');
                    set_value('ram_capacity', data[2]);
                    set_value('ram_note', data[3]);
                } else if (data[0] == 'ЦП') {
                    set_value('cpu_model', data[1]);
                    $('#cpu_model').trigger('change');
                    set_value('cpu_frequency', data[2]);
                    set_value('cpu_note', data[3]);
                }
                // размещаем данные в зависимости от категории
            } else if (data[4] == '1' || data[4] == '2' || data[4] == '3' || data[4] == '4') {
                set_hw_item(data);
            } else if (data[4] == '5') {
                set_value('description_c5', data[1]);
                $('#description_c5').trigger('change');
                set_value('feature_c5', data[2]);
                set_value('hw_note_c5', data[3]);
            }
        }
    }).responseText;
}

function get_hw_array(id_pc) {
    var wow = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            print_data: 'hw',
            ID_pc: id_pc},
        dataType: "json",
        success: function(data){
            data.forEach(function(item, i, data){
                get_hw_item(item);
            });
        }
    }).responseText;
}

function set_pd_item(data) {
    var category = data[4];
    if (category == 2) {
        buff = get_available_id('hw_name_c2');
        buff_elem = getIndex(buff);
        set_value(buff, data[0]);
        $('#' + buff).trigger('change');
        buff = 'description_c2_elem' + buff_elem;
        buff1 = 'feature_c2_elem' + buff_elem;
        buff2 = 'hw_note_c2_elem' + buff_elem;
        buff3 = 'pd_inv_num_c2_elem' + buff_elem;
        set_value(buff, data[1]);
        $('#' + buff).trigger('change');
        set_value(buff1, '');
        set_value(buff2, '');
        set_value(buff3, data[3]);
        document.getElementById('div_feature_c2_elem' + buff_elem).style.display = "none";
        document.getElementById('div_hw_note_c2_elem' + buff_elem).style.display = "none";
        document.getElementById('div_pd_inv_num_c2_elem' + buff_elem).style.display = "block";
        document.getElementById('switch_btn_c2_elem' + buff_elem).style.display = "none";
    } else if (category == 6) {
        set_value('description_c6', data[1]);
        set_value('pd_inv_num_c6', data[3]);
    } else if (category == 7) {
        buff = get_available_id('pd_name_c7');
        buff_elem = getIndex(buff);
        set_value(buff, data[0]);
        $('#' + buff).trigger('change');
        buff = 'pd_model_c7_elem' + buff_elem;
        buff1 = 'feature_c7_elem' + buff_elem;
        buff2 = 'pd_inv_num_c7_elem' + buff_elem;
        set_value(buff, data[1]);
        $('#' + buff).trigger('change');
        set_value(buff1, data[2]);
        set_value(buff2, data[3]);
    }
}

function get_pd_item(id_pd) {
    var x = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            print_data: 'pd_id',
            ID_pd: id_pd},
        dataType: "json",
        success: function(data){
            for (i = 0; i < 5; i++) {
                if (data[i] == 'null') {
                    data[i] = '';
                }
            }
            if (data[4] == '2') {
                set_pd_item(data);	
            } else if (data[4] == '6') {
                set_pd_item(data);
            } else if (data[4] == '7') {
                set_pd_item(data);
            }
            //console.log(data[0] + '   ' + data[1] + '   ' + data[2] + '   ' + data[3] + '   ' + data[4]);
        }
    }).responseText;
    return;
}

function get_pd_array(id_pc) {
    var x = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            print_data: 'pd',
            ID_pc: id_pc},
        dataType: "json",
        success: function(data){
            data.forEach(function(item, i, data){
                get_pd_item(item);
            });
        }
    }).responseText;
}

function get_sw_item(id_sw) {
    var x = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            print_data: 'sw_id',
            ID_sw: id_sw},
        dataType: "json",
        success: function(data){
            for (i = 0; i < 5; i++) {
                if (data[i] == 'null') {
                    data[i] = '';
                }
            }
            buff = get_available_id('sw_name');
            buff_elem = getIndex(buff);
            set_value(buff, data[0]);
            $('#' + buff).trigger('change');
            buff = 'licence_type_elem' + buff_elem;
            buff1 = 'licence_num_elem' + buff_elem;
            buff2 = 'licence_key_elem' + buff_elem;
            buff3 = 'version_elem' + buff_elem;
            buff4 = 'sw_note_elem' + buff_elem;
            set_value(buff, data[1]);
            set_value(buff1, data[2]);
            set_value(buff2, data[3]);
            set_value(buff3, data[4]);
            set_value(buff4, data[5]);
            //console.log(data[0] + ']   [' + data[1] + ']   [' + data[2] + ']   [' + data[3] + ']   [' + data[4] + ']   [' + data[5]);
        }
    }).responseText;
}

function get_sw_array(id_pc) {
    var x = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            print_data: 'sw',
            ID_pc: id_pc},
        dataType: "json",
        success: function(data){
            data.forEach(function(item, i, data){
                get_sw_item(item);
            });
        }
    }).responseText;
}

function get_data_via_id(id_, name_) {
    var wow = $.ajax({
        type: 'POST',
        url: '../php/fillprint.php',
        async: false,
        data: {
            id: id_,
            name: name_
        }, success: function(html){
            //document.getElementById(name_).value = html + "-01-01";
            if (name_ == 'manufacture_date') {
                document.getElementsByName(name_)[0].value = html + "-01-01";
            } else if (name_ == 'buying_method') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'balance_date_bookkeeping') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'balance_num') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'balance_date') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'pc_name') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'pc_place') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'position') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'pc_inv_num') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'responsible_person') {
                document.getElementsByName(name_)[0].value = html;
            } else if (name_ == 'responsible_since') {
                document.getElementsByName(name_)[0].value = html;
            }
        }
    }).responseText;
}

$(document).ready(function(){
            
});

$(document).on('change', function(e) {
    var parent_id = e.target.id;
    if (parent_id == "") {
        return;
    }
    var parent_var = $('#' + parent_id).val();
    var x;
    //console.log(parent_id);
    if (parent_id == 'mb_model') {
        if(parent_var != ""){
            x = $.ajax({
                type:'POST',
                url: '../php/ajaxData.php',
                async: false,
                data: {
                    mb_model: parent_var
                }, success:function(html){
                    $('#mb_note').html(html);
                }
            }).responseText;
        }else{
            $('#mb_note').html('<option value="">Примечание</option>'); 
        }
    } else if (parent_id == 'ram_type') {
        if(parent_var != ""){
            x = $.ajax({
                type:'POST',
                url: '../php/ajaxData.php',
                async: false,
                data: {
                    ram_type: parent_var,
                    need: 'rc'
                }, success:function(html){
                    $('#ram_capacity').html(html);
                }
            }).responseText;
            x = $.ajax({
                type:'POST',
                url: '../php/ajaxData.php',
                async: false,
                data: {
                    ram_type: parent_var,
                    need: 'rn'
                }, success:function(html){
                    $('#ram_note').html(html);
                }
            }).responseText; 
        }else{
            $('#ram_capacity').html('<option value="">Выберите объём</option>');
            $('#ram_note').html('<option value="">Примечание</option>'); 
        }
    } else if (parent_id == 'cpu_model') {
        if(parent_var != ""){
            x = $.ajax({
                type:'POST',
                url: '../php/ajaxData.php',
                async: false,
                data: {
                    cpu_model: parent_var,
                    need: 'cf'
                }, success:function(html){
                    $('#cpu_frequency').html(html);
                }
            }).responseText;
            x = $.ajax({
                type:'POST',
                url: '../php/ajaxData.php',
                async: false,
                data: {
                    cpu_model: parent_var,
                    need: 'cn'
                }, success:function(html){
                    $('#cpu_note').html(html);
                }
            }).responseText; 
        }else{
            $('#cpu_frequency').html('<option value="">Выберите частоту</option>');
            $('#cpu_note').html('<option value="">Примечание</option>'); 
        }
    } else {
        //console.log(e.target);
        var name_ = getName(parent_id);
        var category_ = getCategory(parent_id);
        var index_ = getIndex(parent_id);
        if (getTypeDynamicRow(parent_id) == 1) {
            if (name_ == 'hw_name') {
                eventChange(parent_id, '1', name_, category_, index_);
            } else if (name_ == 'description') {
                eventChange(parent_id, 'f', name_, category_, index_);
                eventChange(parent_id, 'n', name_, category_, index_);
            } else if (name_ == 'pd_name') {
                eventChange(parent_id, '1_pd', name_, category_, index_);
            } else if (name_ == 'pd_model') {
                eventChange(parent_id, 'f_pd', name_, category_, index_);
            }
        } else if (getTypeDynamicRow(parent_id) == 2) {
            if (name_ == 'sw_name') {
                eventChange(parent_id, '2', name_, category_, index_);
            }
        } else if (getTypeDynamicRow(parent_id) == 3) {
            if (name_ == 'description') {
                eventChange(parent_id, '3', name_, category_, null);
            }
        }
    }
});

function get_old_page(id) {
    get_data_via_id(id, 'manufacture_date');
    get_data_via_id(id, 'buying_method');
    get_data_via_id(id, 'balance_date_bookkeeping');
    get_data_via_id(id, 'balance_num');
    get_data_via_id(id, 'balance_date');
    get_data_via_id(id, 'pc_name');
    get_data_via_id(id, 'pc_place');
    get_data_via_id(id, 'position');
    get_data_via_id(id, 'pc_inv_num');
    get_data_via_id(id, 'responsible_person');
    get_data_via_id(id, 'responsible_since');
    get_hw_array(id);
    get_pd_array(id);
    get_sw_array(id);
    document.getElementById('save_btn').setAttribute('name', 'update_passport');			
}

$(document).on('click', function(e) {
    var id = e.target.id;
    var name = getName(id);
    if (name == 'switch_btn') {
        var category = getCategory(id);
        var index = getIndex(id);
        if (document.getElementById('div_feature_c' + category + '_elem' + index).style.display == "none") {
            document.getElementById('div_feature_c' + category + '_elem' + index).style.display = "block";
            document.getElementById('div_hw_note_c' + category + '_elem' + index).style.display = "block";
            document.getElementById('div_pd_inv_num_c' + category + '_elem' + index).style.display = "none";
        } else {
            document.getElementById('div_feature_c' + category + '_elem' + index).style.display = "none";
            document.getElementById('div_hw_note_c' + category + '_elem' + index).style.display = "none";
            document.getElementById('div_pd_inv_num_c' + category + '_elem' + index).style.display = "block";
        }
    }
});