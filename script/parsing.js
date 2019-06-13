function getName(string_) {
    var start_pos = string_.search(/_c\d+/);
    if (start_pos == -1) {
        start_pos = string_.search(/_elem\d+/);
    }
    return string_.substr(0, start_pos);
}
function getCategory(string_) {
    var start_pos = string_.search(/_c\d+/) + 2;
    var start_pos_i = string_.search(/_elem\d+/);
    var num;
    if (start_pos_i == -1) {
        num = string_.length - start_pos;
    } else {
        num = start_pos_i - start_pos;
    }
    return string_.substr(start_pos, num);
}
function getIndex(string_) {
    var start_pos = string_.search(/_elem\d+/) + 5;
    var num = string_.length - start_pos;
    return string_.substr(start_pos, num);
}
function getTypeDynamicRow(string_) {
    if (string_.search(/_elem\d+/) != -1) {       
        if (string_.search(/_c\d+/) != -1) { // hardware and peripheral devices
            return 1;
        } else {                              // software
            return 2;
        }
    } else if (string_.search(/_c\d+/) != -1) { // static category
        return 3;
    } else {
        return 0;
    }
}
function deleteScriptTag(string_) {
    start_pos = string_.search(/<script/);
    while (start_pos != -1) {
        end_pos = string_.search(/<\/script>/);
        string_ = string_.substr(0, start_pos) + string_.substr(end_pos + 9);
        start_pos = string_.search(/<script/);
    }
    return string_;
}
function deleteTbodyTag(string_) {
    start_pos = string_.search(/<tbody>/);
    while (start_pos != -1) {
        string_ = string_.replace(/<tbody>/, '');
        string_ = string_.replace(/<\/tbody>/, '');
        start_pos = string_.search(/<tbody>/);
    }
    
    start_pos = string_.search(/<tbody/);
    while (start_pos != -1) {
        string_ = string_.replace(/<tbody.+?>/, '');
        string_ = string_.replace(/<\/tbody>/, '');
        start_pos = string_.search(/<tbody/);
    }
    return string_;
}
function deleteSpaces(string_) {
    start_pos = string_.search(/&nbsp;/);
    while (start_pos != -1) {
        string_ = string_.replace(/&nbsp;/, '');
        start_pos = string_.search(/&nbsp;/);
    }
    return string_;
}
function deleteInputTag(string_) {
    start_pos = string_.search(/<input/);
    while (start_pos != -1) {
        string_ = string_.replace(/<input.+?>/, '');
        start_pos = string_.search(/<input/);
    }
    return string_;
}