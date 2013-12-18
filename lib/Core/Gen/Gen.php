<?php

/**
 * Файл генерации Gen.php
 *
 * todo: Переписать по стилю ООП в следущей стабильной версии
 */


/*********************************************************************************************
 *
 *                                    Генерация Преложения
 *
 * Генератор создает несколько вложеных каталогов и файлов
 * [new_app_name]
 *      [Controllers]
 *          ControllerIndex.php
 *      [Classes]
 *          Func.php
 *      [Models]
 *          Base.php
 *      [Views]
 *          main.php
 *      [Structure]
 *          [administrator]
 *              [Controllers]
 *                  ControllerAdministrator.php
 *              [Models]
 *                  Base.php
 *              [Views]
 *                  main.php
 * [theme]
 *      [new_theme_name]
 *          main.php
 *          [css]
 *          [js]
 ********************************************************************************************/

if(!isset($_POST['newAppName']))
{
    $newAppTitle = "Генератор кода: Новое преложение";
    $newAppContent = '<p>Файлы нового преложения будут созданы в корне фреймворка. Чтобы активировать новое преложение
     измениете параметр "pathApp" на имя созданого каталога в конфиг-файле системы (__ROOT__/lib/configuration.php). </p>
                    <div class="form-gener">
                <form method="post">
                    <p><lable>Название нового каталога преложения:</lable></p>
                    <p><input name="newAppName" type="text" value="" placeholder="" /></p>
                    <p><lable>Генерация по шаблону:</lable></p>
                    <p><select name="newAppNameType" id="">
                        <option value="none">Стандартный</option>
                        <option value="full">Расширеный</option>
                    </select></p>
                    <p><input type="submit" value="Создать"/></p>
                </form>
    </div>
    ';
    $newAppContentExists = '
    <ul>
        <li><b>Название нового каталога преложения</b>
            <ul>
                <li>Название каталога, не распространяеться не на какие настройки
                кроме имени директории где будет создано само преложение.</li>
                <li>Название не есть окончательным и может быть изменено после создания.</li>
            </ul>
        </li>
        <li><b>Генерация по шаблону</b>
            <ul>
                <li>Стандартный - базовый скилет преложения, включает все каталоги и демо файлы</li>
                <li>Расширеный - кроме базового скилета содержит пример преложения,
                подключение к БД, AJAX запросы, форма авторизации. </li>
            </ul>
        </li>
    </ul>';
}else{

/** Создание каталогов */
    $newAppName = $_POST['newAppName'];
    if(is_dir(PATH.$newAppName)){

        $newAppTitle = "Каталог \"".PATH.$newAppName."\" уже существует!";
        $newAppContent = '<a href="'.URL.'/gen/app">Пересоздать.</a>';
    }else{

        $newApp = $newAppName.DS;

        $structureResult = true;
        $createClasses                 = $newApp.'Classes/';
        $createControllers             = $newApp.'Controllers/';
        $createModels                  = $newApp.'Models/';
        $createViews                   = $newApp.'Views/index/';
        $createStructureControllers    = $newApp.'Structure/administrator/Controllers/';
        $createStructureModels         = $newApp.'Structure/administrator/Models/';
        $createStructureViews          = $newApp.'Structure/administrator/Views/';

        /** Содержание для корневых файлов */
        $fileContentFunction    = file_get_contents(PATH_LIB_CORE.'Gen'.DS.'templates'.DS.'g_n_functions.tpl');
        $fileContentBootstrap   = file_get_contents(PATH_LIB_CORE.'Gen'.DS.'templates'.DS.'g_n_bootstrap.tpl');
        $fileContentConfig      = file_get_contents(PATH_LIB_CORE.'Gen'.DS.'templates'.DS.'g_n_configuration.tpl');
        /** Содержание для внутрених файлов в базовых каталогах Controllers, Models, Views и Classes  */
        $fileContentClasses     = file_get_contents(PATH_LIB_CORE.'Gen'.DS.'templates'.DS.'g_n_classes.tpl');
        $fileContentControllers = file_get_contents(PATH_LIB_CORE.'Gen'.DS.'templates'.DS.'g_n_controller.tpl');
        $fileContentModels      = file_get_contents(PATH_LIB_CORE.'Gen'.DS.'templates'.DS.'g_n_model.tpl');
        $fileContentView        = file_get_contents(PATH_LIB_CORE.'Gen'.DS.'templates'.DS.'g_n_view_main.tpl');
        /** Содержание для Структуры */
        $fileStructureControllersAdmin = file_get_contents(PATH_LIB_CORE.'Gen'.DS.'templates'.DS.'g_n_controller_structure_admin.tpl');


        if (!mkdir($createClasses, 0, true))
            $structureResult = false;
        if (!mkdir($createControllers, 0, true))
            $structureResult = false;
        if (!mkdir($createModels, 0, true))
            $structureResult = false;
        if (!mkdir($createViews, 0, true))
            $structureResult = false;
        if (!mkdir($createStructureControllers, 0, true))
            $structureResult = false;
        if (!mkdir($createStructureModels, 0, true))
            $structureResult = false;
        if (!mkdir($createStructureViews, 0, true))
            $structureResult = false;

        if($structureResult){
            /** Создание файлов корневых преложения */
            file_put_contents($newApp.'functions.php', $fileContentFunction);
            file_put_contents($newApp.'bootstrap.php', $fileContentBootstrap);
            file_put_contents($newApp.'configuration.php', $fileContentConfig);
            /** Создание файлов внутрених в базовых каталогах Controllers, Models, Views и Classes */
            file_put_contents($newApp.'Controllers/ControllersIndex.php', $fileContentControllers);
            file_put_contents($newApp.'Models/Base.php', $fileContentModels);
            file_put_contents($newApp.'Views/main.php', $fileContentView);
            file_put_contents($newApp.'Classes/QmClasses.php', $fileContentClasses);
            /** Создание файлов внутри структуры-модуля */
            file_put_contents($newApp.'Structure/functions.php', $fileContentFunction);
            file_put_contents($newApp.'Structure/administrator/Controllers/ControllerAdministrator.php', $fileStructureControllersAdmin);
            file_put_contents($newApp.'Structure/administrator/Models/Base.php', $fileContentModels);
            file_put_contents($newApp.'Structure/administrator/Views/main.php', $fileContentView);
        }

        $newAppTitle = "Генератор кода: Новое преложение создано!";
        $newAppContent = '
    <p>Файлы нового преложения созданы в корне фреймворка. Преложение активно после создания не будет, чтобы активировать
    измениете параметр "pathApp" на имя созданого каталога <b>'.$_POST["newAppName"].'</b> в конфиг-файле ядара системе (__ROOT__/lib/configuration.php). </p>
    ';
        $newAppContentExists = '
        <h2>Генератор кода: Дальнейшие действия</h2>
        <p>Структура преложения преложения</p>
<pre>
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
</pre>
    ';
    }



}

$data['newAppContentTitle'] = $newAppTitle;
$data['newAppContent'] = $newAppContent;
$data['newAppContentExists'] = $newAppContentExists;




/*********************************************************************************************
 *
 *                                    Генерация Контролера
 *
 ********************************************************************************************/
$controllerData = '';
$controllerScandir = scandir(PATH_APP.DS.'Controllers'.DS);
foreach ($controllerScandir as $controllerFile){

    //if($controllerFile != "." AND $controllerFile != ".."){
    if(preg_match("|^Controller*|",$controllerFile)){
        $controllerFiles .= '<li>'.$controllerFile.'</li>';
    }elseif($controllerFile != "." AND $controllerFile != ".."){
        $controllerExtendsFiles .= '<option value="'.$controllerFile.'" >'.$controllerFile.'</option>';
    }
}

if(isset($_POST['nameController']) AND !empty($_POST['nameController']) )
{


    if (preg_match("|^[a-zA-Z0-9]*$|", $_POST['nameController'])){

        $nameController = mb_convert_case($_POST['nameController'], MB_CASE_TITLE, "UTF-8");

        if( $_POST['typeController'] == 'base')
            $tpl_controller = 'g_controller.tpl';
        if( $_POST['typeController'] == 'full')
            $tpl_controller = 'g_controller_full.tpl';

        $file = file_get_contents(PATH_LIB."Core".DS."Gen".DS.'templates'.DS.$tpl_controller);
        $file = str_replace("[[CONTROLLERNAME]]", $nameController, $file);

        if(preg_match('|^(.*)\.php$|', $_POST['parentController'], $parentControllerFind)){
            $parentController = $parentControllerFind[1];
        }else{
            $parentController = $_POST['parentController'];
        }



        $file = str_replace("[[CONTROLLEREXTENDS]]", $parentController, $file);


        if(!file_exists(PATH_APP.DS.'Controllers'.DS.'Controller'.$nameController.'.php')) {

            file_put_contents(PATH_APP.DS.'Controllers'.DS.'Controller'.$nameController.'.php', $file);

            $controllerData .= '<p>Файл контролера был создан в активном преложении.</p>';
            $controllerData .= '<p>"<b><?php echo QmConf("pathApp");?>/Controllers/Controller'.$nameController.'.php</b>"</p><br>';
        } else {
            $controllerData .= "<p>Контролер с названием <b>".'Controller'.$nameController.'.php'."</b> уже существует!</p>";
        }

    } else {
        $controllerData .= "<p>Введены запрещенные символы!</p>";
    }
}else{
    $controllerData .= '<p>Файл контролера будет создан в активном преложении (что указан в конфиг-файле)</p>
                    <p>"<b><?php echo QmConf("pathApp");?>/Controllers/Controller</b> * <b>.php</b>" (* - название )</p>
                    <div class="form-gener">
                    <form action="" method="post">
                        <p><lable>Название контролера:</lable></p>
                        <p><input name="nameController" type="text"/></p>
                        <p><lable>Генерация по шаблону:</lable></p>
                        <p><select name="typeController">
                                <option value="base">Базовый</option>
                                <option value="full" selected="selected">Полный</option>
                            </select>
                            </p>
                        <p><lable>Наследуеться от:</lable></p>
                        <p><select name="parentController">
                                <option value="Controller" selected="selected">Controller</option>
                                '.$controllerExtendsFiles.'
                            </select>
                            </p>
                        <p><input type="submit" value="Создать Контролер"/></p>
                    </form>
                    </div>
                    ';
}

$data['controller'] = $controllerData;
$data['controllerExists'] = '<ul>'.$controllerFiles.'</ul>';



/*********************************************************************************************
 *
 *                                    Генерация Модели
 *
 ********************************************************************************************/
$modelScandir = scandir(PATH_APP.DS.'Models'.DS);
foreach ($modelScandir as $modelFile){
    if($modelFile != "." AND $modelFile != ".."){
        $modelFiles .= '<li>'.$modelFile.'</li>';
    }
}
$modelData = '';
if(isset($_POST['nameModel']) AND !empty($_POST['nameModel']) ){

    if (preg_match("|^[a-zA-Z0-9]*$|", $_POST['nameModel'])){

        $nameModel = mb_convert_case($_POST['nameModel'], MB_CASE_TITLE, "UTF-8");

        if( $_POST['typeModel'] == 'base')
            $tpl_model = 'g_model.tpl';
        if( $_POST['typeModel'] == 'full')
            $tpl_model = 'g_model_full.tpl';

        $file = file_get_contents(PATH_LIB."Core".DS."Gen".DS.'templates'.DS.$tpl_model);
        $file = str_replace("[[MODELNAME]]", $nameModel, $file);


        if(!file_exists(PATH_APP.DS.'Models'.DS.$nameModel.'.php')) {

            file_put_contents(PATH_APP.DS.'Models'.DS.$nameModel.'.php', $file);

            $modelData .= '<p>Файл контролера был создан в активном преложении.</p>';
            $modelData .= '<p>"<b><?php echo QmConf("pathApp");?>/Models/'.$nameModel.'.php</b>"</p><br>';
        } else {
            $modelData .= "<p>Модель с названием <b>".$nameModel.'.php'."</b> уже существует!</p>";
        }

    } else {
        $modelData .= "<p>Введены запрещенные символы!</p>";
    }
}else{
    $modelData .= '<p>Файл модели будет создан в активном преложении (что указан в конфиг-файле)</p>
                    <p>"<b><?php echo QmConf("pathApp");?>/Models/</b> * <b>.php</b>" (* - название )</p>
                    <div class="form-gener">
                    <form action="" method="post">
                        <p><lable>Название модели:</lable></p>
                        <p><input name="nameModel" type="text"/></p>
                        <p><lable>Генерация по шаблону:</lable></p>
                        <p><select name="typeModel" size="1">
                                <option value="base">Базовый</option>
                                <option value="full" selected="selected">Полный</option>
                            </select></p>
                        <p><input type="submit" value="Создать Модель"/></p>
                    </form>
                    </div>
                    ';
}

$data['model'] = $modelData;
$data['modelExists'] = '<ul>'.$modelFiles.'</ul>';

include_once PATH_LIB."Core".DS."Gen".DS.'theme'.DS.'main.php';
