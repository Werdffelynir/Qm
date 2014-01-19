<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
        <title><?php $this->data("pageTitle"); ?></title>
        <link rel="stylesheet" href="<?php echo App::$urlTheme; ?>/css/default.css" type="text/css" />
        <?php $this->showStyles();?>
        <?php  $this->showScripts();?>

    </head>
    <body>

    <div id="wrapper" class="lite">

        <div id="header" class="lite">
            <div class="first lite_9"> <span class="logo">Qm </span> <span class="logoText">php farmework</span></div>
            <div class="lite_3"><div class="loginBox"></span> <a href="#">Login</a> | <a href="#">Registration</a></div></div>
        </div>

        <div id="topMenu" class="lite">
            <?php $this->chunk("topmenu"); ?>
        </div>

        <div id="page" class="lite clear">

            <div id="content" class="first lite_9">
                <?php $this->show($viewName, true, true); ?>
            </div>

            <div id="sidebar" class="lite_3">
                <?php $this->chunk("leftmenu"); ?>
                <?php $this->chunk("about"); ?>
            </div>

        </div>

        <div id="footer" class="lite">
            <?php $this->chunk("copyright"); ?>
        </div>

    </div>

    </body>
</html>