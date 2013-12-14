<?php

/*********************************************************************************************
 *
 *                                    Генерация Контролера
 *
 ********************************************************************************************/


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
 *
 * [theme]
 *      [new_theme_name]
 *          main.php
 *          [css]
 *          [js]
 ********************************************************************************************/







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

        $file = file_get_contents(PATH_LIB."Core".DS."Geany".DS.'templates'.DS.$tpl_controller);
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

        $file = file_get_contents(PATH_LIB."Core".DS."Geany".DS.'templates'.DS.$tpl_model);
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

include_once PATH_LIB."Core".DS."Geany".DS.'theme'.DS.'main.php';
