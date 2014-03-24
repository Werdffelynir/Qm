<?php

/**
 * Счетчик
 *
 * @since 0.3.0
 *
 * timerStart();
 * echo timerStart();
 */
if (!function_exists('timerStart')) {
    function timerStart()
    {
        define('START_TIME',microtime(true));
    }
}
if (!function_exists('timerStop')) {
    function timerStop()
    {
        return round(microtime(true)-START_TIME,4);
    }
}


/**
 * Get configuration settings
 *
 * @since 0.3.0
 */
if (!function_exists('config')) {

    /**
     * @param null $p
     * @return mixed
     */
    function config($p=null)
    {
        static $config;
        
        if (empty($config)) {
            $file = PATH_APP . 'config.php';
            $config = include $file;
            if($p==null) 
                return $config;
            else
                if(isset($config[$p]))
                    return $config[$p];
                else
                    die('Error! param ['.$p.'] not exists!');
        }else{
            if($p==null) 
                return $config;
            else 
                if(isset($config[$p]))
                    return $config[$p];
                else
                    die('Error! param ['.$p.'] not exists!');
        }
    }
}


/**
 * Quick echo text with data
 *
 * @since 0.3.0
 *
 * e('<p>test string variable: $key </p>',array('key'=>'TOOLS'));
 */
if (!function_exists('strRun')) {

    /**
     * @param   string  $value
     * @param   null    $data
     * @param   bool    $e
     * @return  mixed
     */
    function strRun($value, $data=null, $e=true)
    {
        if(!empty($data)){
            extract($data);
            @eval("\$value = \"$value\";");
        }
        if ($e)
            echo $value;
        else
            return $value;
    }
}


/**
 * Перевод строки в нижний регистр
 *
 * @since 0.3.0
 *
 * @param   string  $text
 * @return  string
 */
function toLower($text) {
    if (function_exists('mb_strtolower')) {
        $text = mb_strtolower($text, 'UTF-8');
    } else {
        $text = strtolower($text);
    }
    return $text;
}


/**
 * Перевод строки в верхний регистр
 *
 * @since 0.3.0
 *
 * @param   string  $text
 * @return  string
 */
function toUpper($text) {
    if (function_exists('mb_strtoupper')) {
        $text = mb_strtoupper($text, 'UTF-8');
    } else {
        $text = strtoupper($text);
    }
    return $text;
}



/* SECURITY FUNCTIONS
 **********************************************************************  */

/**
 * Очистка строки
 *
 * @since 0.3.0
 *
 * @param string    $data
 * @param bool      $max
 * @return string
 */
function clean($data,$max=true){
    if($max)
        $data = trim(stripslashes(strip_tags(html_entity_decode($data, ENT_QUOTES, 'UTF-8'))));
    else
        $data = trim(stripslashes(strip_tags($data, ENT_QUOTES, 'UTF-8')));

    return $data;
}


/**
 * Очистка URL строки
 *
 * @since 0.3.0
 *
 * @param   string  $text
 * @return  string
 */
function cleanUrl($text)  {
    $text = strip_tags(toLower($text));
    $codeEntitiesMatch = array(' ?',' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','/','*','+','~','`','=','.');
    $codeEntitiesReplace = array('','-','-','','','','','','','','','','','','','','','','','','','','','','','','');
    $text = str_replace($codeEntitiesMatch, $codeEntitiesReplace, $text);
    $text = urlencode($text);
    $text = str_replace('--','-',$text);
    $text = rtrim($text, "-");
    return $text;
}


/**
 * Очистка от тегов и кавычек по умолчанию,
 * или если указан второй аргумент кодирует кавычки
 *
 * @since 0.3.0
 *
 * @param string $text
 * @param bool $encode
 * @return string
 */
function clearQuotes($text, $encode=false)  {
    $text = strip_tags($text);
    if(!$encode){
        $codeEntitiesMatch = array('"','\'','&quot;');
        $text = str_replace($codeEntitiesMatch, '', $text);
    }else{
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8', false);
    }
    return trim($text);
}



/**
 * Очищает строку от небезопасных сиволов, обезопасит от XSS атак
 *
 * @since 0.3.0
 * @author Martijn van der Ven
 *
 * @param   string  $str    Строка для очистки
 * @return  string
 */
function clearXSS($str){
    // attributes blacklist:
    $attr = array('style','on[a-z]+');
    // elements blacklist:
    $elem = array('script','iframe','embed','object');
    // extermination:
    $str = preg_replace('#<!--.*?-->?#', '', $str);
    $str = preg_replace('#<!--#', '', $str);
    $str = preg_replace('#(<[a-z]+(\s+[a-z][a-z\-]+\s*=\s*(\'[^\']*\'|"[^"]*"|[^\'">][^\s>]*))*)\s+href\s*=\s*(\'javascript:[^\']*\'|"javascript:[^"]*"|javascript:[^\s>]*)((\s+[a-z][a-z\-]*\s*=\s*(\'[^\']*\'|"[^"]*"|[^\'">][^\s>]*))*\s*>)#is', '$1$5', $str);
    foreach($attr as $a) {
        $regex = '(<[a-z]+(\s+[a-z][a-z\-]+\s*=\s*(\'[^\']*\'|"[^"]*"|[^\'">][^\s>]*))*)\s+'.$a.'\s*=\s*(\'[^\']*\'|"[^"]*"|[^\'">][^\s>]*)((\s+[a-z][a-z\-]*\s*=\s*(\'[^\']*\'|"[^"]*"|[^\'">][^\s>]*))*\s*>)';
        $str = preg_replace('#'.$regex.'#is', '$1$5', $str);
    }
    foreach($elem as $e) {
        $regex = '<'.$e.'(\s+[a-z][a-z\-]*\s*=\s*(\'[^\']*\'|"[^"]*"|[^\'">][^\s>]*))*\s*>.*?<\/'.$e.'\s*>';
        $str = preg_replace('#'.$regex.'#is', '', $str);
    }
    return $str;
}


/**
 * Html в спец-символы
 *
 * @since 0.3.0
 *
 * @param   string  $text
 * @return  string
 */
function htmlEncode($text) {
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    $text = str_replace(chr(12), '', $text); // FF
    $text = str_replace(chr(3), ' ', $text); // ETX
    return $text;
}


/**
 * Спец-символы HTML Decode
 *
 * @since 0.3.0
 *
 * @param   string  $text
 * @return  string
 */
function htmlDecode($text) {
    $text = stripslashes(htmlspecialchars_decode($text, ENT_QUOTES));
    return $text;
}


/**
 * Осуществляет фильтрацию переменной, возвращается к htmlentities
 * использует стандартную функ filter_var.
 *
 * @since 0.3.0
 *
 * @param  string $var    Строка фильтрации
 * @param  string $filter Тип фильтра (string|int|float|url|email|special или decode)
 * @return string
 */
function specFilter($var,$filter = "special"){

    if($filter=="decode")
        return stripslashes(htmlspecialchars_decode($var, ENT_QUOTES));

    if(function_exists( "filter_var") ){
        $aryFilter = array(
            "string"  => FILTER_SANITIZE_STRING,
            "int"     => FILTER_SANITIZE_NUMBER_INT,
            "float"   => FILTER_SANITIZE_NUMBER_FLOAT,
            "url"     => FILTER_SANITIZE_URL,
            "email"   => FILTER_SANITIZE_EMAIL,
            "special" => FILTER_SANITIZE_SPECIAL_CHARS,
        );
        if(isset($aryFilter[$filter])) return filter_var( $var, $aryFilter[$filter]);
        return filter_var( $var, FILTER_SANITIZE_SPECIAL_CHARS);
    }
    else {
        return htmlentities($var);
    }
}



/* STRING AND ARRAY FUNCTIONS
 **********************************************************************  */



/**
 * Сортировка вложеных массивов массива
 * Сортирует вложеные вложеные массивы по ключю одного с волженых массивов
 *
 * @since 0.3.0
 *
 * @param array     $array
 * @param string    $subKey     Ключ вложеного массива, по котором будет сортировка
 * @param string    $order      order 'asc' или 'desc'
 * @param bool      $natural    использувать алгоритм сортировки "natural order"
 * @return array
 */
function sortBySubKey($array,$subKey,$order='asc',$natural=true){

    if (count($array) != 0 || (!empty($array))) {
        $result = array();
        foreach($array as $k=>$v) {
            if(isset($v[$subKey])) $temp[$k] = toLower($v[$subKey]);
        }

        if(!isset($temp)) return $array;

        if($natural){
            natsort($temp);
            if($order=='desc') $temp = array_reverse($temp,true);
        }
        else {
            ($order=='asc')? asort($temp) : arsort($temp);
        }

        foreach($temp as $key=>$val) {
            $result[] = $array[$key];
        }

        return $result;
    }
}
function sortBySubVal($array,$subKey, $order='asc',$natural = true) {}




/* DIRS AND FILES FUNCTIONS
 **********************************************************************  */

/**
 * Конвертирует число или строку в байты
 *
 * @since 0.3.0
 *
 * @param   $bytes
 * @param   int $precision
 * @return  string
 */
function toBytes($bytes, $precision = 2)
{
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;

    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';

    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';

    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';

    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';

    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}





/**
 * Сканирует указаную директорию $path на выявление указаных $type (файлов или директрорий) по умолчанию ито и другое
 *
 * @since 0.3.0
 *
 * @param string    $path   путь
 * @param bool      $type   false - все, 'd'||'dir' - каталоги, 'f'||'file' - файлы
 * @return array
 */
function openFiles($path, $type=false) {

    $handle = opendir($path) or die("getFiles: Unable to open $path");
    $fileArr = array();
    while ($file = readdir($handle)) {
        if ($file != '.' && $file != '..' ) {

            if($type==false){
                $fileArr[] = $file;
            } elseif( ($type=='d' || $type=='dir') && is_dir($path.DIRECTORY_SEPARATOR.$file) ) {
                $fileArr[] = $file;
                break;
            } elseif( ($type=='f' || $type=='file') && is_file($path.DIRECTORY_SEPARATOR.$file) ) {
                $fileArr[] = $file;
                break;
            }
        }
    }
    closedir($handle);
    return $fileArr;
}



/**
 * Возвращает с указаной директрии массив полных путей всех каталогов и файлов
 *
 * @since 0.3.0
 *
 * @param   string      $directory  путь для сканирования
 * @param   boolean     $recursive  усли true сканирование рекурсивное
 * @return  array
 */
function directoryToArray($directory, $recursive) {

    $directory = rtrim($directory, DIRECTORY_SEPARATOR);

    $array_items = array();
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($directory. "/" . $file)) {
                    if($recursive) {
                        $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
                    }
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                } else {
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                }
            }
        }
        closedir($handle);
    }
    return $array_items;
}




/* ПРОВЕРОЧНЫЕ FUNCTIONS
 **********************************************************************  */


/**
 * Alias for checking for debug constant
 * @since 3.2.1
 * @return  bool true if debug enabled
 */
function isDebug(){
    return config('debug');
}


/**
 * Check if request is an ajax request
 * @since  3.3.0
 * @return bool true if ajax
 */
function isAjax(){
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_GET['ajax']);
}



/**
 * Check if server is Apache
 *
 * @returns bool
 */
function isApache() {
    return( strpos(strtolower($_SERVER['SERVER_SOFTWARE']),'apache') !== false );
}


/**
 * Проверяет являеться ли каталог пустым
 *
 * @since 0.3.0
 *
 * @param   string $folder
 * @return  bool
 */
function isEmptyFolder($folder) {
    $files = array ();
    if ( $handle = opendir ( $folder ) ) {
        while ( false !== ( $file = readdir ( $handle ) ) ) {
            if ( $file != "." && $file != ".." ) {
                $files [] = $file;
            }
        }
        closedir ( $handle );
    }
    return ( count ( $files ) > 0 ) ? FALSE : TRUE;
}



/* COOKIE FUNCTIONS
 **********************************************************************  */


/**
 * Генератор соли
 *
 * @since 0.3.0
 *
 * @param $name
 * @param $value
 * @return string
 */
function cookieSalt($name, $value){

    $salt = (config('salt'))?config('salt'):false;

    if (!$salt)
        \Core\App::ExceptionError('A valid cookie salt is required. Please set "salt" in your config.php. For more information check the documentation');

    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : 'unknown';

    return sha1($agent.$name.$value.$salt);
}


/**
 * Установитель
 *
 * @since 0.3.0
 *
 * @param   $name
 * @param   null    $value
 * @param   int     $expire
 * @param   array   $param      Установить дополнительные параметры array('expire'=>'','path'=>'','domain'=>'');
 * @return  bool
 */
function addCookie($name, $value=null, $expire=600, array $param=array()) {

    if(empty($param)){
        $path = \Core\App::$getURL['nude'].'/';
        $domain = $_SERVER['HTTP_HOST'];
    }else{
        $path = $param['path'];
        $domain = $param['domain'];
    }

    $encodeCookie = \Core\App::$config['encodeCookie'];
    $expire = $expire+time();

    if($encodeCookie)
        $value = cookieSalt($name, $value).'~'.$value;

    return setcookie($name, $value, $expire, $path, $domain);
}


/**
 * Доставатель cookie по имени,
 * вторым параметром значение по умолчани,
 * третим параметром установить удаление после выборки
 *
 * @since 0.3.0
 *
 * @param   $key
 * @param   null    $default
 * @param   bool    $delete
 * @return null
 */
function getCookie($key, $default = NULL, $delete = false){

    if (!isset($_COOKIE[$key]))
        return $default;

    $cookie = $_COOKIE[$key];

    $split = strlen(cookieSalt($key, NULL));

    if (isset($cookie[$split]) AND $cookie[$split] === '~') {
        list ($hash, $value) = explode('~', $cookie, 2);

        if (cookieSalt($key, $value) === $hash)
            return $value;

        if ($delete)
            deleteCookie($key);
    }

    return $default;
}


/**
 * Удалятель cookie
 *
 * @since 0.3.0
 *
 * @param   $name
 * @param   array   $param  Дополнительные параметры array('expire'=>'','path'=>'','domain'=>'');
 * @return  bool
 */
function deleteCookie($name, array $param=array()){
    if(empty($param)){
        $path = \Core\App::$getURL['nude'].'/';
        $domain = $_SERVER['HTTP_HOST'];
    } else {
        $path = $param['path'];
        $domain = $param['domain'];
    }
    unset($_COOKIE[$name]);
    return setcookie($name, NULL, -86400, $path, $domain);
}


/* i18n языковые FUNCTIONS
 **********************************************************************  */


/**
 *
 * @since 0.3.0
 *
 * @param $name
 * @param bool $echo
 * @return null|string
 */
function lang($name, $echo=true) {

    static $langData = array();
    $lang = \Core\App::$langCode;

    if(!isset($lang))
        return null;

    if(empty($langData)){
        $data = include PATH_APP.'Languages/'.$lang.'.php';
        $langData = $data['words'];
    }

    if (array_key_exists($name, $langData)) {
        $myVar = $langData[$name];
    } else {
        if (array_key_exists($name, $langData)) {
            $myVar = $langData[$name];
        } else {
            $myVar = '{'.$name.'}';
        }
    }

    if (!$echo) {
        return $myVar;
    } else {
        echo $myVar;
    }
}






/* OTHER FUNCTIONS
 **********************************************************************  */


/**
 * Redirect URL. Первый аргумент URL относительны по приложению, если необходимо
 * редирект сделать на другой домен или приложение необходимо указывать полную строку
 * например "http://other=site.com/index/hello"
 * Второй аргумент если numeric - время задержки, или bool true для force режима
 * Третий параметр если режим не force код заголовка страницы после переадрисации
 *
 * @since 0.3.0
 *
 * @param string    $url        Переадресация на URL
 * @param int|bool  $delayForce Редирек с задержкой с секунднах. Или если true - принудительно.
 * @param int       $code       HTTP код заголовка; по умолчанию 302
 * @return bool
 */
function redirect($url, $delayForce = 0, $code = 302)
{
    if(isAjax()){
        header('HTTP/1.1 401 Unauthorized', true, 401);
        header('WWW-Authenticate: FormBased');
        die();
    }

    if( !(strpos($url,'http://') > -1) )
        $url = \Core\App::$getURL['base'].'/'.$url;

    if($delayForce===true){
        if (!headers_sent()) {
            header('Location: ' . $url);
        } else {
            echo "<html><head><title>REDIRECT</title></head><body>";
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0; url=' . $url . '" />';
            echo '</noscript>';
            echo "</body></html>";
        }
        echo "<!--Headers already!\n-->";
        echo "</body></html>";
        exit;
    }

    if (!headers_sent($file, $line)) {
        if ($delayForce)
            header('Refresh: ' . $delayForce . '; url=' . $url, true);
        else
            header('Location: ' . $url, true, $code);
        exit();
    } else {
        if (isDebug())
            \Core\App::ExceptionError('Headers sent. Redirect impossible!', '<span style="color:red">File: <b>'.$file.'</b><br>Line: <b>'.$line.'</b><span>');
        else
            return true;
    }

    exit;
}



/**
 * Простая отправка email
 * Отправитель администратор по умолчанию, указан в конфигурационных настройках приложения
 *
 * @since 0.3.0
 *
 * @param  string    $to
 * @param  string    $subject
 * @param  string    $message
 * @param  bool      $fromEmail
 * @return string
 */
function sendMail($to, $subject, $message, $fromEmail=false) {

    if(!$fromEmail)
        if (config('email'))
            $fromEmail = config('email');
        else
            $fromEmail =  'noreply@'.$_SERVER['SERVER_NAME'];

    $headers  ='"MIME-Version: 1.0' . PHP_EOL;
    $headers .= 'Content-Type: text/html; charset=UTF-8' . PHP_EOL;
    $headers .= 'From: '.$fromEmail . PHP_EOL;
    $headers .= 'Reply-To: '.$fromEmail . PHP_EOL;
    $headers .= 'Return-Path: '.$fromEmail . PHP_EOL;

    if( @mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',"$message",$headers) ) {
        return true;
    } else {
        return false;
    }
}


/**
 * Возвращает текущий URL адрес страницы

 * @since 0.3.0
 *
 * @param bool $echo
 * @return string
 */
function selfUrl($echo=true) {
    $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
    $url .= ( $_SERVER["SERVER_PORT"] !== 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
    $url .= $_SERVER["REQUEST_URI"];

    if ($echo)
        echo $url;
    else
        return $url;
}
