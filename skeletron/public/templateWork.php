<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test build 0.30</title>

<?php $this->scriptTrigger(); ?>

<?php $this->styleTrigger(); ?>

</head>
<body>
<div class="warp">
    <div class="header"><h1>Builder Qm framework</h1></div>

    <div class="content">

        <!-- Use method $this->show -->
        <?php $this->renderTheme('BASE_OUT')?>


    </div>

    <div class="footer"><p><?php echo timerStop(); ?> s.</p></div>
</div>


<?php $this->scriptTrigger('footer'); ?>

</body>
</html>