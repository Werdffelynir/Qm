<?php
//namespace ClLoader;

/**
 *
 * Author: OL Werdffelynir
 * Date: 07.12.13
 * Time: 14:36
 *
 * Класс загрузчик классов с директории app/Classes/*
 * Класс реализован по классическому паттерну Singleton
 * Настройки загрузи устанавлеваються с lib/configuration.php
 *
 * Инициализация происходит в абстрактном классе lib/Base.php
 *
 * Класс инициализуеться по методу:
 * ClLoader::run()->scan();
 *
 * Для вызова не подключенных классов используеться метод loadClasses()
 * ClLoader::run()->loadClasses('FileClassName');
 *
 */
class ClLoader {

    protected static $instance;

    public $classesData = array();

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    public static function run()
    {
        if (self::$instance === null)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * scan() Метод поиска возможных файлов в папках "Classes" преложения
     *
     */
    public function scan()
    {
        if(!realpath(PATH_APP_CLASS))
            return false;

        $dirResurse = scandir(PATH_APP_CLASS);
        foreach ($dirResurse as $file) {
            if(preg_match("|\w\d*\.php$|i", $file)){
                $this->classesData[$file] = false;
            }
        }

        $this->loadClasses();
    }

    public function loadClasses()
    {
        $classesAutoload = QmConf('classesAutoload');

        if($classesAutoload['autoload'])
        {
            foreach ($this->classesData as $className=>$classParam) {
                if(file_exists(PATH_APP_CLASS.$className) AND $classParam == false) {
                    include PATH_APP_CLASS.$className;
                    $this->classesData[$className] = true;
                }
            }
        }
        else
        {
            $classesExclusion = $classesAutoload['exclusion'];

            foreach ($classesExclusion as $ClassNameExc) {
                $ClassName = $ClassNameExc.'.php';

                if(file_exists(PATH_APP_CLASS.$ClassName) AND $this->classesData[$ClassName] == false) {
                    include PATH_APP_CLASS.$ClassName;
                    $this->classesData[$ClassName] = true;
                }
            }

        }

    }

    public function loadClass($inclFile)
    {
        $inclClassName = $inclFile.".php";

        if(file_exists(PATH_APP_CLASS.$inclClassName) AND $this->classesData[$inclClassName] == false) {
            include PATH_APP_CLASS.$inclClassName;
            $this->classesData[$inclClassName] = true;
        }

        return new $inclFile();
    }


}