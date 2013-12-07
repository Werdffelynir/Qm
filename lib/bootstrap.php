<?php

/** Hard-Core system */
include "Core/App.php";

/** Autoloader app classes*/
include "Classes/ClLoader.php";

/** Подключение класса работы с БД */
if(QmConf("db")){
    $classDB = QmConf("db");
    include "Classes/SafePDO.php";
    include "Classes/".$classDB['class'].".php";
}

/** Abstracted class Base class, uses Controller, Model */
include "Abstracts/Base.php";

/** Подключение внутрених файлов Преложения */
if(file_exists(PATH.QmConf("pathApp").DS."functions.php"))
    include PATH.QmConf("pathApp").DS."functions.php";
if(file_exists(PATH.QmConf("pathApp").DS."bootstrap.php"))
    include PATH.QmConf("pathApp").DS."bootstrap.php";
    
/** Abstracted class Controller*/
include "Abstracts/Controller.php";

/** Abstracted class Model */
include "Abstracts/Model.php";

