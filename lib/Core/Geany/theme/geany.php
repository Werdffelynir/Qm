<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=URL;?>/lib/Core/Geany/theme/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?=URL;?>/lib/Core/Geany/theme/main.js" />
</head>
<body>
<div id="Qm_wrapper">
    <div id="Qm_header">
        <div class="title">
            <h1><a href="/">Quick Minimalism</a></h1>
            <p>Генератор кода</p>
        </div>
    </div>
    <div id="Qm_content_wrapper" class="clearfix">

        <!-- OutPut content -->
        <div id="Qm_content">

            <?php if(App::$request == "geany"): ?>
                <h2>Генератор кода</h2>

                <p>Geny - это небольшей конструктор кода для фреймворка. Все что делает Geny это интим,
                    ибо производимый им файл являеться лишь каркасом с возможными свойстами и методами
                    которые разработчик будет использывать. Но позволяет бысрее упорядочить структуру
                    вашего нового приложения.</p>

                <p>Использывать конструктор Geny совсем не обезательно. Вы можете вручную создавать
                    все что необходимо в приложении. Но генератор может скоротиль на несколько минут
                    первоначальный этап разработки.</p>
            <?php endif; ?>



            <?php if(App::$request == "geany/app"): ?>

                <form action="">

                    <input type="text" value="" placeholder="Название каталога" />
                    <select name="" id="">
                        <option value="none">Стандартный</option>
                        <option value="full">Расширеный</option>
                    </select>
                    <input type="submit" value="Создать"/>

                </form>

            <?php endif; ?>


            <?php if(App::$request == "geany/controller"): ?>

                <h2>Генератор кода: Контролер</h2>
                <?php if(isset($_POST['nameController']) AND !empty($_POST['nameController']) ):

                    if (preg_match("|^[a-zA-Z0-9]*$|", $_POST['nameController'])){

                        $nameController = mb_convert_case($_POST['nameController'], MB_CASE_TITLE, "UTF-8");

                        if( $_POST['typeController'] == 'base')
                            $tpl_controller = 'g_controller.tpl';
                        if( $_POST['typeController'] == 'full')
                            $tpl_controller = 'g_controller_full.tpl';

                        $file = file_get_contents(PATH_LIB."Core".DS."Geany".DS.'templates'.DS.$tpl_controller);
                        $file = str_replace("[[CONTROLLERNAME]]", $nameController, $file);


                        if(!file_exists(PATH_APP.DS.'Controllers'.DS.'Controller'.$nameController.'.php')) {

                            file_put_contents(PATH_APP.DS.'Controllers'.DS.'Controller'.$nameController.'.php', $file);

                            echo '<p>Файл контролера был создан в активном преложении.</p>';
                            echo '<p>"<b><?php echo QmConf("pathApp");?>/Controllers/Controller'.$nameController.'.php</b>"</p><br>';

                        } else {

                            echo "<p>Контролер с названием <b>".'Controller'.$nameController.'.php'."</b> уже существует!</p>";

                        }

                    } else {
                        echo "<p>Введены запрещенные символы!</p>";
                    }
                    ?>


                <p></p>
                <?php else: ?>
                    <p>Файл контролера будет создан в активном преложение (что указан в конфи-файле)</p>
                    <p>"<b><?php echo QmConf("pathApp");?>/Controllers/Controller</b> * <b>.php</b>"</p>
                    <p>* название что вы зададите.</p><br>

                    <form action="" method="post">
                        <p><lable>Название контролера:</lable></p>
                        <p><input name="nameController" type="text"/></p>
                        <p><lable>Тип контролера:</lable></p>
                        <p><select id="typeController" name="typeController" size="2">
                                <option value="base">Базовый</option>
                                <option value="full" selected="selected">Полный</option>
                            </select></p>
                        <p><input type="submit" value="Создать Контролер"/></p>
                    </form>
                    <p></p>
                <?php endif; ?>

            <?php endif; ?>

            <?php if(App::$request == "geany/model"): ?>


                <h2>Генератор кода: Модель</h2>
                <?php if(isset($_POST['nameModel']) AND !empty($_POST['nameModel']) ):

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

                            echo '<p>Файл модели был создан в активном преложении.</p>';
                            echo '<p>"<b><?php echo QmConf("pathApp");?>/Models/'.$nameModel.'.php</b>"</p><br>';
                        } else {
                            echo "<p>Модель с названием <b>".$nameModel.'.php'."</b> уже существует!</p>";
                        }

                    }
                    else
                    {
                        echo "<p>Введены запрещенные символы!</p>";
                    }
                    ?>


                    <p></p>
                <?php else: ?>
                    <p>Файл модели будет создан в активном преложение (что указан в конфи-файле)</p>
                    <p>"<b><?php echo QmConf("pathApp");?>/Models/</b> * <b>.php</b>"</p>
                    <p>* название что вы зададите.</p><br>
                    <form action="" method="post">
                        <p><lable>Название модели:</lable></p>
                        <p><input name="nameModel" type="text"/></p>
                        <p><lable>Генерация по шаблону:</lable></p>
                        <p><select name="typeModel" size="2">
                                <option value="base">Базовый</option>
                                <option value="full" selected="selected">Полный</option>
                            </select></p>
                        <p><input type="submit" value="Создать Модель"/></p>
                    </form>
                    <p></p>
                <?php endif; ?>

            <?php endif; ?>

            <?php if(App::$request == "geany/structure"): ?>



            <?php endif; ?>


        </div><!--Qm_content-->

        <div id="Qm_sidebar">
            <h2 class="sidebartitle suer"><a href="/geany">Main</a></h2>
            <h2 class="sidebartitle">Что генерировать ?</h2>
            <ul>
                <li><a href="/geany/controller">Controller</a></li>
                <li><a href="/geany/model">Model</a></li>
<!--                <li><a href="/geany/structure">Structure</a></li>-->
            </ul>
        </div>
    </div>

    <div id="Qm_footer">
        <p>Copyright &copy; 1560 &mdash; 2013 SunLight, Inc. All rights reserved.
        </p>
        <p> Design by
            <a href="http://www.nikhedonia.com/" rel="bookmark" title="SimplyGold">QmPHP Theme OL Werdffelynir</a>.
        </p>
        <p>Работает на QmPHP Framework |  <?php echo 'Сгенерирован за:' .round(microtime(true) - (int)$_SERVER["REQUEST_TIME_FLOAT"], 4). ' сек'; ?>
        </p>
    </div>
</div>
</body>
</html>