
/** Включение визуального редактора
 * Отображаеться на страницах:
 * edit/
 * */
//bkLib.onDomLoaded(function() {
//    new nicEditor().panelInstance('textAreaContent');
//});

/** Приминение к всем елементам с классом confirm стандартного диалогового окна */
$(function() {
    $(".confirum").click(function () {
        if (confirm('Are you sure to hide')) {
            $("#tmm1").hide("slow");
        }else{
            return false;
        }
    });

});