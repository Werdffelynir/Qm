<?php
$url = Yii::app()->request->baseUrl;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8>
    <meta name="keywords" content="" />
    <meta name="description" content=""/>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/public/css/lite_min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/public/fontello/css/fontello.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/public/css/snipp_style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/public/css/menu_ul.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/public/css/perfect-scrollbar.css" />

    <script type="text/javascript" src="<?php echo $url; ?>/public/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $url; ?>/public/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo $url; ?>/public/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="<?php echo $url; ?>/public/js/perfect-scrollbar.js"></script>
    <script type="text/javascript" src="<?php echo $url; ?>/public/js/script.js"></script>



    <style rel="stylesheet" type="text/css">

    </style>

    <script type="text/javascript">
        $(document).ready(function() {});
    </script>

</head>


<div class="winBG"></div>

<?php $this->widget('application.widgets.Console'); ?>

<?php include "tpl.win.php"; ?>

<body>
<div class="warp lite">



