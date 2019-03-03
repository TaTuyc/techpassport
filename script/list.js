function get_delete_confirmation(id_pc) {
    var answer = confirm("Удалить паспорт?");
    if (answer) {
        var x = $.ajax({
            type: 'POST',
            url: '../php/ajaxData.php',
            async: false,
            data: {
                delete_passport: id_pc},
            dataType: "json",
            success: function(){}
        }).responseText;
        document.location.reload(true);
    }
}

function is_pc_exist(id_pc, call_type) {
    var answer = false;
    var x = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            is_pc_exist: id_pc},
        dataType: "json",
        success: function(data){
            answer = data;
        }
    }).responseText;
    if (!answer) {
        alert("Паспорт удалён, страница будет перезагружена.");
        document.location.reload(true);
    } else {
        if (call_type == 'edit') {
            document.location.href = '../passport/index.php?id=' + id_pc;
        } else if (call_type == 'repair') {
            document.location.href='../repair/index.php?id=' + id_pc;
        } else if (call_type == 'history') {
            get_history(id_pc);
        } else if (call_type == 'get_repair') {
            document.location.href='../repair_list/index.php?id=' + id_pc;
        }
    }
}

function get_history(id_pc) {
    var answer = '';
    var x = $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        async: false,
        data: {
            get_history: id_pc},
        dataType: "json",
        success: function(data){
            data_size = data.length;
            for (i = 0; i < data_size; i++) {
                if (data[i] == null) {
                    data[i] = '';
                }
            }
            data_size = data_size / 3;
            for (i = 0; i < data_size; i++) {
                answer += data[i * 3] + '\t' + data[i * 3 + 1] + '\t' + data[i * 3 + 2] + '\n';	
            }
            alert(answer);
        }
    }).responseText;
}

function get_portion(id_page) {
    $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        data: {
            print_data: 'page_id',
            ID_page: id_page},
        dataType: "json",
        success: function(data){
            data_size = data.length;
            for (i = 0; i < data_size; i++) {
                if (data[i] == null) {
                    data[i] = '';
                }
            }
            data_size = data_size / 4;
            var buff = '';
            $('#pas_list').html('');
            for (i = 0; i < data_size; i++) {
                //buff = document.getElementById('pas_list').innerHTML;
                buff += 
                    '<tr><td>' + data[i * 4] + '</td><td>' + data[i * 4 + 1] + '</td><td>' + data[i * 4 + 2] + '</td>' + 
                    '<td><button type="button" class="del btn btn-danger" name="delete_button" onclick="get_delete_confirmation(' + data[i * 4 + 3] + ')">Удалить</button></td>' +
                    '<td><button type="button" class="chng btn btn-primary" onclick="is_pc_exist(' + data[i * 4 + 3] + ', \'edit\')">Изменить</button></td>' +
                    '<td><button type="button" class="fix btn btn-primary" onclick="is_pc_exist(' + data[i * 4 + 3] + ', \'repair\')">Ремонт</button></td>' +
                    '<td><button type="button" class="exp btn btn-success" name="expbtn_' + data[i * 4 + 3] + '">Экспорт</button></td>' +
                    '<td><button type="button" class="chng btn btn-primary" onclick="is_pc_exist(' + data[i * 4 + 3] + ', \'history\')">Ответственный</button></td>' +
                    '<td><button type="button" class="chng btn btn-primary" onclick="is_pc_exist(' + data[i * 4 + 3] + ', \'get_repair\')">Ремонты</button></td>';
            }
            $('#pas_list').html(buff);
        }
    });
    
    $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        data: {
            print_data: 'page_list',
            ID_page: id_page},
        dataType: "json",
        success: function(data){
            var page_size = data[1];
            var page_this = id_page;
            var num = (data[0] - data[0] % page_size) / page_size + 1;
            if (data[0] % page_size != 0) {
                num++;
            }
            var buff = "";
            if (page_this != 1) {
                buff = '<a onclick="get_portion(' + (page_this - 1) + ');">&nbsp;Предыдущая&nbsp;</a>';
            }
            for (i = 1; i < num; i++) {
                buff += '<a onclick="get_portion(' + i + ');">&nbsp;' + i + '</a>';
            }
            if (page_this != num - 1) {
                buff += '<a onclick="get_portion(' + (page_this + 1) + ');">&nbsp;&nbsp;Следующая</a>';
            }
            $('#num_pages').html('<tr><td colspan="9">' + buff + '</td></tr>');
        }
    });
    
    set_block();
}

function set_portion_size(new_size) {
    $.ajax({
        type: 'POST',
        url: '../php/ajaxData.php',
        data: {
            update_cookie: 'portion_size',
            portion_size: new_size},
        dataType: "json",
        success: function(data) {
            get_portion(1);
        }
    });
}

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
                document.getElementsByName('delete_button').forEach(function(item, i, data){
                    item.setAttribute('disabled', 'disabled');
                });
            }
        }
    }).responseText;
}