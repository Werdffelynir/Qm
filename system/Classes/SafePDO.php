<?php


/**
 * Class SafePDO, обертка для встроеного расширения PHP PDO предотвращает вывод данных соединения при ошибках
 *
 * Author: OL Werdffelynir
 * Date: 07.12.13
 * Time: 14:36
 */
Class SafePDO extends PDO {

    public static function exception_handler($exception) {
        // Output the exception details
        die('Uncaught exception: '. $exception->getMessage());
        }

    public function __construct($dsn, $username='', $password='', $driver_options=array()) {

        // Temporarily change the PHP exception handler while we . . .
        set_exception_handler(array(__CLASS__, 'exception_handler'));

        // . . . create a PDO object
        parent::__construct($dsn, $username, $password, $driver_options);

        // Change the exception handler back to whatever it was before
        restore_exception_handler();
    }

}

// Connect to the database with defined constants
//$dbh = new SafePDO(PDO_DSN, PDO_USER, PDO_PASSWORD);