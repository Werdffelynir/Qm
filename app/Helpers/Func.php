<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 08.01.14
 * Time: 2:50
 */

class Func {



    /** ********************************************************************************************************
     **                                         REQUEST & URL
     ********************************************************************************************************** */





    /** ********************************************************************************************************
     **
     **                                         STRING FUNCTIONS
     **
     ********************************************************************************************************** */




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

    public static function convertStr($string, $format="UPPER_FIRST"){

        switch($format)
        {
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
                return mb_strtoupper($strArr[1][0], "UTF-8").$strArr[2][0];
                break;
            case "UPPER_FIRST_E":
                $tempStr = explode(".", $string);
                $tempJoin = '';
                foreach ($tempStr as $value) {
                    $tempJoin .= self::convertStr(trim($value), "UPPER_FIRST").". ";
                }
                $string = mb_substr($tempJoin, 0, -3);
                break;
            default:
                $string = false;
        }

        return $string;
    }


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
    public static function cutText($string, $maxLen, $more='...', $linkMore=null, $encode=true)
    {
        if($encode==true)
        {
            $string = strip_tags($string);
            $string = trim(preg_replace('~\s+~s', ' ', $string));
        }

        if(!is_null($linkMore))
            $more = '<a href="'.$linkMore.'">'.$more.'</a>';

        $words = explode(' ', $string);

        if ($maxLen < 1 || sizeof($words) <= $maxLen) {
            return $string;
        }

        $words = array_slice($words, 0, $maxLen);

        $out = implode(' ', $words);

        return $out.' '.$more;
    }





    /** ********************************************************************************************************
     **
     **                                         EVENTS
     **
     ********************************************************************************************************** */


    /** ********************************************************************************************************
     **
     **                                  FLASH SESSION STORAGE
     **
     ********************************************************************************************************** */


    /**
     * Writes flash values to the session for persistence.
     *
     * This function is called automatically and *should never* be called manually.
     *
     * @return bool Boolean true on success, false otherwise

    function flash_write()
    {
        if (!isset($_SESSION)) return false;

        $flash = config('_flash');

        $data = flash();

        if (empty($data)) {
            unset($_SESSION[$flash]);
        } else {
            $_SESSION[$flash] = $data;
        }

        return true;
    }*/


} 