/**
 * @param {String} tableId ID элемента tbody таблицы
 * @param {String} selectId ID элемента select для установки кол-ва строк
 * @param {Object} fields Конфигурация строки таблицы: {1:"field_name1", 2:"field_name2", ... и т.д. }
 * @param {Function} creatorCallback - ф-ция обратного вызова принимающая два параметра:
 *      {Number} - номер строки таблицы,
 *      {String} - имя поля из объекта fields
 */
// function setupTable(tableId, selectId, fields, creatorCallback) {
//     var htmlTBody   = document.getElementById(tableId),
//         htmlSelect  = document.getElementById(selectId),
//         rowTpl      = document.createElement("TR"),
//         rowNum      = 0,
//         ELEMENT, TD;
//
//     /* Строим шаблон строки таблицы один раз, в дальнейшем будем просто его клонировать */
//     for(var field in fields) {
//         if (false === fields.hasOwnProperty(field)) { continue; }
//         TD = document.createElement("TD");
//
//         if (creatorCallback) {
//             ELEMENT = creatorCallback(rowNum, fields[field])
//         } else {
//             ELEMENT = document.createElement("INPUT");
//             ELEMENT.name = fields[field] + "[]";
//         }
//
//         TD.appendChild(ELEMENT);
//         rowTpl.appendChild(TD);
//
//         rowNum += 1;
//     }
//     // Вешаем обработчик на элемент управления кол-вом строк
//     htmlSelect.onchange = function (e) {
//         var numRows = htmlSelect.options[htmlSelect.selectedIndex].value;
//         /* Отслеживаем отрицательные значения а то мало ли какие там значения в option понаставят */
//         numRows = numRows < 0 ? 0 : numRows;
//         /* Удаляем те строки которые есть. */
//         while(htmlTBody.firstChild) {
//             htmlTBody.removeChild(htmlTBody.firstChild);
//         }
//         for (var i = 0; i < numRows; i++) {
//             htmlTBody.appendChild(rowTpl.cloneNode(true));
//         }
//     };
// }

var DynamicTable = (function (GLOB) {
    return function (tBody) {
        /* Если ф-цию вызвали не как конструктор фиксим этот момент: */
        if (!(this instanceof arguments.callee)) {
            return new arguments.callee.apply(arguments);
        }
        //Делегируем прослушку событий элементу tbody
        tBody.onclick = function(e) {
            var evt = e || GLOB.event,
                trg = evt.target || evt.srcElement;
                if (trg.className && trg.className.indexOf("del") !== -1) {
                    if (confirm("Вы уверены, что хотите удалить запись?")) {
                        tBody.rows.length > 0 && _delRow(trg.parentNode.parentNode, tBody);
                    }
                }
        };

        var _delRow = function (row, tBody) {
            tBody.removeChild(row);
        };
        _correctNames(tBody.rows[0]);
    };
})(this);
