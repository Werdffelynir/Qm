<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=URL;?>/lib/Core/gen/theme/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?=URL;?>/lib/Core/gen/theme/main.js" />
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

            <?php if(App::$request == "gen"): ?>
                <h2>Генератор кода</h2>

                <p>Gen - это небольшей конструктор кода для фреймворка. Все что делает Geny это интим,
                    ибо производимый им файл являеться лишь каркасом с возможными свойстами и методами
                    которые разработчик будет использывать. Но позволяет бысрее упорядочить структуру
                    вашего нового приложения.</p>

                <p>Использывать конструктор Geny совсем не обезательно. Вы можете вручную создавать
                    все что необходимо в приложении. Но генератор может скоротиль на несколько минут
                    первоначальный этап разработки.</p>
            <?php endif; ?>




            <?php if(App::$request == "gen/app"): ?>

                <h2><?php echo $data['newAppContentTitle']; ?></h2>
                <?php echo $data['newAppContent']; ?>

                <?php echo $data['newAppContentExists']; ?>

            <?php endif; ?>




            <?php if(App::$request == "gen/structure"): ?>

                <h2><?php echo $data['newStructureTitle']; ?></h2>
                <?php echo $data['newStructureContent']; ?>

                <?php echo $data['newStructureContentExists']; ?>

            <?php endif; ?>




            <?php if(App::$request == "gen/controller"): ?>

                <h2>Генератор кода: Контролер</h2>
                <?php echo $data['controller']; ?>

                <h2>Существующие Контролеры:</h2>
                <?php echo $data['controllerExists']; ?>

            <?php endif; ?>

            <?php if(App::$request == "gen/model"): ?>

                <h2>Генератор кода: Модель</h2>
                <?php echo $data['model']; ?>

                <h2>Существующие Модели:</h2>
                <?php echo $data['modelExists']; ?>

            <?php endif; ?>

            <?php if(App::$request == "gen/structure"): ?>



            <?php endif; ?>


        </div><!--Qm_content-->

        <div id="Qm_sidebar">
            <h2 class="sidebartitle suer"><a href="/gen">Main</a></h2>
            <h2 class="sidebartitle">Что генерировать ?</h2>
            <ul>
                <li><a href="/gen/app">Application</a></li>
                <li><a href="/gen/structure">Structure</a></li>
                <li><a href="/gen/controller">Controller</a></li>
                <li><a href="/gen/model">Model</a></li>
                <!--                <li><a href="/gen/structure">Structure</a></li>-->
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