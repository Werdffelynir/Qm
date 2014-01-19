<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=App::$url;?>/system/Generator/theme/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?=App::$url;?>/system/Generator/theme/main.js" />
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
            <h2 class="title"><?php echo $this->title ?></h2>
            <div class="content"><?php echo $this->content ?></div>
        </div><!--Qm_content-->

        <div id="Qm_sidebar">
            <h2 class="sidebartitle suer"><a href="/generator">Main</a></h2>
            <h2 class="sidebartitle">Что генерировать ?</h2>
            <ul>
                <li><a href="/generator/info">App structure info</a></li>
                <li><a href="/generator/app">Create new Application</a></li>
                <li><a href="/generator/theme">Create new Theme</a></li>
                <li><a href="/generator/structure">Create new Structure</a></li>
                <li><a href="/generator/controller">Create new Controller</a></li>
                <li><a href="/generator/model">Create new Model</a></li>
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