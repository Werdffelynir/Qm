<?php
/**
 * Helpers Class
 */

class QmFunc {

    /**
     * Перенаправление по указаному роуту, относитльный URL. Метод настраивает ответ с кодом HTTP и время задержки.
     * Если время задержки задано, эта функция всегда будет возвращать логическое TRUE,
     * Если заголовок уже прошел код всеравно будет остановлен halt()
     *
     * @param string $url   Редирек URL
     * @param int $delay Редирек с задержкой с секунднах
     * @param int $code  HTTP код; по умолчанию 302
     *
     * @return bool Boolean true if time delay is given
     */
    public static function redirect($url, $delay = 0, $code = 302)
    {
        if (!headers_sent()){
            if ($delay)
                header('Refresh: ' . $delay . '; url=' . $url, true);
            else
                header('Location: ' . $url, true, $code);
        }else{
            return false;
        }
    }


    /**
     * @param $url
     */
    public static function redirectForce($url)
    {
        if (!headers_sent()) {
            header('Location: ' . $url);
        } else {
           // echo "<script>document.location.href='".$url."';</script>\n";
           // echo "<noscript><meta http-equiv='refresh' content='0; url='".$url."' /></noscript>\n";
            echo "<html><head><title>REDIRECT</title></head><body>";
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.$url.'";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
            echo '</noscript>';
            echo "</body></html>";
        }
        echo "Headers already!\n";
        echo "</body></html>";
        exit;
    }

}
