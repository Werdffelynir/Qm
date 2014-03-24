<?php


namespace App;

class JS
{


    /**
     *
     * Метод отправки формы через AJAX для случаев когда конструкциями Yii невозможно
     * справиться.
     * <pre>
     * $ajax = formAJAX("idForm", "tickets/sendmsg",
     * array(
     * 'success'   =>'function (data) { console.log(data); }',
     * 'beforeSend'=>'function (data) { console.log(data); }',
     * 'error'     =>'function (data) { console.log(data); }',
     * )
     * );
     * </pre>
     * @param $idForm
     * @param $url
     * @param $functions
     * @return string
     */
    public static function formPostAJAX($idForm, $url, $functions)
    {
        $javascript = '
                <script type="text/javascript">
                        $("#' . $idForm . '").submit(function () {
                            var url = "' . URL . '/' . $url . '";
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: $("#' . $idForm . '").serialize()';
        $javascript .= ($functions['success']) ? ', success: ' . $functions['success'] : null;
        $javascript .= ($functions['beforeSend']) ? ', beforeSend: ' . $functions['beforeSend'] : null;
        $javascript .= ($functions['error']) ? ', success: ' . $functions['error'] : null;
        $javascript .= '});
                            return false;
                        });
                </script>
            ';
        return $javascript;
    }


    /**
     *
     * Еквевалентен предведущему но берет больше параметров
     *
     * $ajax = formAJAX("idForm", "tickets/sendmsg",
     * array(
     * 'type'      =>'GET',
     * 'url'       =>'http://other/other/',
     * 'dataType'  =>'json',
     * 'data'      =>'{param: data1, param2: data2 }',
     * 'success'   =>'function (data) { console.log(data); }',
     * 'beforeSend'=>'function (data) { console.log(data); }',
     * 'error'     =>'function (data) { console.log(data); }',
     * )
     * );
     *
     * @param $idForm
     * @param $url
     * @param $functions
     * @return string
     */
    public static function formAJAX($idForm, $url, $functions)
    {
        $javascript = '
        <script type="text/javascript">
                $("#' . $idForm . '").submit(function () {
                    var url = "' . URL . '/' . $url . '";
                    $.ajax({';
        $javascript .= ($functions['type']) ? 'type: "' . $functions['type'] . '"' : 'type: "POST",';
        $javascript .= ($functions['url']) ? ', url: "' . $functions['url'] . '"' : 'url: url';
        $javascript .= ($functions['dataType']) ? ', dataType: "' . $functions['dataType'] . '"' : null;
        $javascript .= ($functions['data']) ? ', data: "' . $functions['data'] . '"' : 'data: $("' . $idForm . '").serialize()';
        $javascript .= ($functions['success']) ? ', success: ' . $functions['success'] : null;
        $javascript .= ($functions['beforeSend']) ? ', beforeSend: ' . $functions['beforeSend'] : null;
        $javascript .= ($functions['error']) ? ', error: ' . $functions['error'] : null;
        $javascript .= '});
                    return false;
                });
        </script>
        ';
        return $javascript;
    }

    /**
     * Мнтод для проверки являеться ли запрос через AJAX
     * @return bool
     */
    public static function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            return true;
        } else {
            return false;
        }
    }


}