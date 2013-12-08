<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php echo $this->showData("pageTitle"); ?></title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" media="screen" href="<?=URL_THEME;?>/css/qmTheme.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?=URL_THEME;?>/css/systcom-icons.css" />
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



                        <?php echo $this->chunk['topMenu']; ?>



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


                    <?php echo $this->chunk['chunkAbout']; ?>

                    <?php echo $this->chunk['chunkQuickstart']; ?>

                    <h2 class="sidebartitle">GetSimple Features</h2>
                    <p>This is your sidebar text. Please change me in <em>Theme -&gt; Edit Components</em></p>
                    <p><a href="http://get-simple.info/download/">Download the Latest GetSimple</a></p>

                    <!-- Use method $this->render -->
                    <?php //echo $this->var['rightHello']; ?>


                    <!-- Use method $this->render -->
                    <?php //echo $this->var['rightHelloArray']['ch_title']; ?>
                    <?php //echo $this->var['rightHelloArray']['ch_content']; ?>


                    <!-- Use method $this->render -->
                    <?php //echo $this->chunk['firstChunk']; ?>

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