<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?=URL?>/public/css/styles.css"/>
    <title>Document</title>
</head>
<body>


<div id="Qm_wrapper">
    <div id="Qm_header">
        <div class="title">
            <h1><a href="<?=URL?>">Quick Minimalism</a></h1>

            <p>MVC PHP Framework</p>
        </div>
        <div class="navigation">
            <ul>
                <?php $this->chunk('topMenu'); ?>
            </ul>
        </div>
    </div>
    <div id="Qm_content_wrapper" class="clearfix">

        <div id="Qm_content">

            <!-- Use method $this->show -->
            <?php $this->renderTheme('BASE_OUT')?>

        </div><!--Qm_content-->

        <div id="Qm_sidebar">

            <?php if($this->userID): ?>
                <h2 class="sidebartitle suer"><a href="<?=URL?>/index/logout">LogOut</a></h2>
            <?php else: ?>
                <h2 class="sidebartitle suer"><a href="<?=URL?>/index/login">Login</a></h2>
            <?php endif; ?>

            <?php $this->chunk('sidebarOne'); ?>
            <?php $this->chunk('sidebarTwo'); ?>

        </div>
    </div>


    <div id="Qm_footer">
        <p>Copyright &copy; 1560 &mdash; 2013 SunLight, Inc. All rights reserved.</p>

        <p> Design by <a href="http://w-code.ru/" rel="bookmark" title="SimplyGold">QmPHP Theme OL Werdffelynir</a>.</p>

        <p>Работает на QmPHP Framework | Сгенерирован за: <?php echo timerStop(); ?> секунд.</p>
    </div>
</div>


</body>
</html>