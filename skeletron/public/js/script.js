/** Приминение к всем елементам с классом confirm стандартного диалогового окна */
$(function () {
    $(".confirum").click(function () {
        if (confirm('Удалить эту страницу?')) {
            console.log('Страница удалина.');
        } else {
            return false;
        }
    });

});