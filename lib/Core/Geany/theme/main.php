<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=URL_THEME;?>/css/qmTheme.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?=URL_THEME;?>/css/systcom-icons.css" />
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

            <?php if(App::$request == "geany/controller"): ?>

                <h2>Генератор кода: Контролер</h2>
                <?php echo $data['controller']; ?>

                <h2>Существующие Контролеры:</h2>
                <?php echo $data['controllerExists']; ?>

            <?php endif; ?>

            <?php if(App::$request == "geany/model"): ?>

                <h2>Генератор кода: Модель</h2>
                <?php echo $data['model']; ?>

                <h2>Существующие Модели:</h2>
                <?php echo $data['modelExists']; ?>

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