<?php
/**
 * Base Helpers functions
 */


/** ********************************************************************************************************
 **
 **                                         REQUEST & URL
 **
 ********************************************************************************************************** */

if (!function_exists('redirect')) {
    /**
     * Перенаправление по указаному роуту, относитльный URL. Метод настраивает ответ с кодом HTTP и время задержки.
     * Если время задержки задано, эта функция всегда будет возвращать логическое TRUE,
     * Если заголовок уже прошел код всеравно будет остановлен halt()
     *
     * @param string $url Редирек URL
     * @param int $delay Редирек с задержкой с секунднах
     * @param int $code HTTP код; по умолчанию 302
     *
     * @return bool Boolean true if time delay is given
     */
    function redirect($url, $delay = 0, $code = 302)
    {
        if (!headers_sent()) {
            if ($delay)
                header('Refresh: ' . $delay . '; url=' . $url, true);
            else
                header('Location: ' . $url, true, $code);
        } else {
            return false;
        }
    }
}

if (!function_exists('redirectForce')) {
    /**
     * @param $url
     */
    function redirectForce($url)
    {
        if (!headers_sent()) {
            header('Location: ' . $url);
        } else {
            // echo "<script>document.location.href='".$url."';</script>\n";
            // echo "<noscript><meta http-equiv='refresh' content='0; url='".$url."' /></noscript>\n";
            echo "<html><head><title>REDIRECT</title></head><body>";
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0; url=' . $url . '" />';
            echo '</noscript>';
            echo "</body></html>";
        }
        echo "Headers already!\n";
        echo "</body></html>";
        exit;
    }
}

/** ********************************************************************************************************
 **
 **                                         STRING FUNCTIONS
 **
 ********************************************************************************************************** */


if (!function_exists('convertStr')) {
    /**
     * Функция переобразования строк
     *
     * @param $string
     * @param $format
     *      MB_CASE_UPPER   - В верхний регистр
     *      MB_CASE_LOWER   - В нижний регистр
     *      MB_CASE_TITLE   - Первый символ каждого слова в верхний регистр
     *      UPPER           - В верхний регистр
     *      LOWER           - В нижний регистр
     *      UPPER_FIRST     - В верхний регистр первый символ строки. (по умолчанию)
     *      UPPER_FIRST_E   - В верхний регистр первый символ строки? каждого приложения.
     * @return bool|string
     */

    function convertStr($string, $format = "UPPER_FIRST")
    {

        switch ($format) {
            case "MB_CASE_UPPER":
                $string = mb_convert_case($string, MB_CASE_UPPER, "UTF-8");
                break;
            case "MB_CASE_LOWER":
                $string = mb_convert_case($string, MB_CASE_LOWER, "UTF-8");
                break;
            case "MB_CASE_TITLE":
                $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
                break;
            case "UPPER":
                $string = mb_strtoupper($string, "UTF-8");
                break;
            case "LOWER":
                $string = mb_strtolower($string, "UTF-8");
                break;
            case "UPPER_FIRST":
                preg_match_all("|^(.)(.*)$|u", self::convertStr($string, "MB_CASE_LOWER"), $strArr);
                return mb_strtoupper($strArr[1][0], "UTF-8") . $strArr[2][0];
                break;
            case "UPPER_FIRST_E":
                $tempStr = explode(".", $string);
                $tempJoin = '';
                foreach ($tempStr as $value) {
                    $tempJoin .= self::convertStr(trim($value), "UPPER_FIRST") . ". ";
                }
                $string = mb_substr($tempJoin, 0, -3);
                break;
            default:
                $string = false;
        }

        return $string;
    }
}

if (!function_exists('cutText')) {
    /**
     * Обрезать строку по количеству слов.
     *
     * @param $string строка для обработки
     * @param $maxLen количество слов
     * @param string $more окончание more '...'
     * @param $linkMore ссылка на more
     * @param bool $encode strip_tags()
     * @return string обрезаная строка
     *
     */
    function cutText($string, $maxLen, $more = '...', $linkMore = null, $encode = true)
    {
        if ($encode == true) {
            $string = strip_tags($string);
            $string = trim(preg_replace('~\s+~s', ' ', $string));
        }

        if (!is_null($linkMore))
            $more = '<a href="' . $linkMore . '">' . $more . '</a>';

        $words = explode(' ', $string);

        if ($maxLen < 1 || sizeof($words) <= $maxLen) {
            return $string;
        }

        $words = array_slice($words, 0, $maxLen);

        $out = implode(' ', $words);

        return $out . ' ' . $more;
    }
}

