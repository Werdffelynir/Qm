<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php //$this->show("pageTitle"); ?></title>
        <meta charset="utf-8">

        <?php //echo $this->showScripts('header','nicEdit'); ?>
        <?php //echo $this->showScripts('header','jquery'); ?>
        <?php //echo $this->showScripts('header'); ?>
        <?php //echo $this->showStyles(); ?>
        <?php //echo $this->showHeader(); ?>

        <?php echo $this->showScripts('header'); ?>
        <?php echo $this->showStyles(); ?>

        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo App::$urlTheme;?>/css/qmTheme.css" />
    </head>
    <body>
        <div id="Qm_wrapper">
            <div id="Qm_header">
                <div class="title">
                    <h1><a href="/">Quick Minimalism</a></h1>
                    <p>MVC PHP Framework</p>
                </div>
                <div class="navigation">
                    <ul>
                        <?php echo $this->chunk('topMenu'); ?>
                    </ul>
                </div>
            </div>
            <div id="Qm_content_wrapper" class="clearfix">

                <!-- OutPut content -->
                <div id="Qm_content">

                    <!-- Use method $this->show -->
                    <?php $this->show($viewName, true, true); ?>

                    <!-- Use method $this->out -->
                    <?php //$this->outTheme("content", $OmData); ?>
                    <?php //$this->outTheme("contentFooter", $OmData); ?>


                    <!-- Use method $this->render -->
                    <?php //echo $this->blockOne; ?>
                    <?php //echo $this->blockTwo; ?>

                </div><!--Qm_content-->

                <div id="Qm_sidebar">

                    <?php if(QmUser::id()):?>
                        <h2 class="sidebartitle suer"><a href="/index/logout">LogOut</a></h2>
                    <?php else:?>
                        <h2 class="sidebartitle suer"><a href="/index/login">Login</a></h2>
                    <?php endif;?>

                    <?php $this->chunk('sidebarFirst'); ?>
                    <?php $this->chunk('sidebarSecond'); ?>

                </div>
            </div>



            <div id="Qm_footer">
                <p>Copyright &copy; 1560 &mdash; 2013 SunLight, Inc. All rights reserved.
                </p>
                <p> Design by
                    <a href="http://werd.id1945.com/" rel="bookmark" title="SimplyGold">QmPHP Theme OL Werdffelynir</a>.
                </p>
                <p>Работает на QmPHP Framework | <?php global $timeLoader; list($microtime, $sec) = explode(chr(32), microtime()); echo 'Сгенерирован за: '.round(($sec + $microtime) - $timeLoader, 4).' секунд'; ?>

                </p>
            </div>
        </div>

        <?php //echo $this->showScripts('footer','nicEdit'); ?>
        <?php //echo $this->showScripts('footer','jquery'); ?>
        <?php //echo $this->showScripts('footer'); ?>
    </body>
</html>