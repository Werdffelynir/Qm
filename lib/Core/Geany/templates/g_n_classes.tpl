<?php
/**
 * Файл был сгенерирован с помощю Geany Qm Framework
 * Необходимо провести реорганизацию кода даного контролера.
 */

class QmClasses

    public $someVar;

    public function __construct() {
        # ...Code
    }

}


<?php
/*
[new_app_name]
     [Controllers]
         ControllerIndex.php
     [Classes]
         Func.php
     [Models]
         Base.php
     [Views]
         main.php
     [Structure]

[theme]
     [new_theme_name]
         main.php
         [css]
         [js]
*/
// Желаемая структура папок

$newApp = "./my_app/";

$structureResult = true;
$creacteClasses                 = $newApp.'Classes/';
$creacteControllers             = $newApp.'Controllers/';
$creacteModels                  = $newApp.'Models/';
$creacteViews                   = $newApp.'Views/index/';
$creacteStructureControllers    = $newApp.'Structure/Controllers/';
$creacteStructureModels         = $newApp.'Structure/Models/';
$creacteStructureViews          = $newApp.'Structure/Views/';

$fileFunction = 'functions.php';
$fileBoot     = 'bootstrap.php';
$fileConfig   = 'configuration.php';
$fileClasses      = 'QmClasses.php';
$fileControllers  = 'ControllersIndex.php';
$fileModels       = 'Base.php';
$fileView         = 'main.php';

$fileContent = "testString";



if (!mkdir($creacteClasses, 0, true))
    $structureResult = false;
if (!mkdir($creacteControllers, 0, true))
    $structureResult = false;
if (!mkdir($creacteModels, 0, true))
    $structureResult = false;
if (!mkdir($creacteViews, 0, true))
    $structureResult = false;
if (!mkdir($creacteStructureControllers, 0, true))
    $structureResult = false;
if (!mkdir($creacteStructureModels, 0, true))
    $structureResult = false;
if (!mkdir($creacteStructureViews, 0, true))
    $structureResult = false;

if($structureResult){
    file_put_contents($newApp.$fileFunction, $fileContent);
    file_put_contents($newApp.$fileBoot, $fileContent);
    file_put_contents($newApp.$fileConfig, $fileContent);
    file_put_contents($creacteClasses.$fileClasses, $fileContent);
    file_put_contents($creacteControllers.$fileControllers, $fileContent);
    file_put_contents($creacteModels.$fileModels, $fileContent);
    file_put_contents($newApp.'Views/'.$fileView, $fileContent);
}


/*
$file = 'people.txt';
// Новый человек, которого нужно добавить в файл
$person = "John Smith\n";
// Пишем содержимое в файл,
// используя флаг FILE_APPEND flag для дописывания содержимого в конец файла
// и флаг LOCK_EX для предотвращения записи данного файла кем-нибудь другим в данное время

*/

















