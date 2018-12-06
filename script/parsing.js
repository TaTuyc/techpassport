function getName(string_) {
    var start_pos = string_.search(/_c\d+/i);
    if (start_pos == -1) {
        start_pos = string_.search(/_elem\d+/i);
    }
    return string_.substr(0, start_pos);
}
function getCategory(string_) {
    var start_pos = string_.search(/_c\d+/i) + 2;
    var start_pos_i = string_.search(/_elem\d+/i);
    var num;
    if (start_pos_i == -1) {
        num = string_.length - start_pos;
    } else {
        num = start_pos_i - start_pos;
    }
    return string_.substr(start_pos, num);
}
function getIndex(string_) {
    var start_pos = string_.search(/_elem\d+/i) + 5;
    var num = string_.length - start_pos;
    return string_.substr(start_pos, num);
}
function getTypeDynamicRow(string_) {
    if (string_.search(/_elem\d+/i) != -1) {       
        if (string_.search(/_c\d+/i) != -1) { // hardware and peripheral devices
            return 1;
        } else {                              // software
            return 2;
        }
    } else if (string_.search(/_c\d+/i) != -1) { // static category
        return 3;
    } else {
        return 0;
    }
}