<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $this->dataShow["pageTitle"]; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo URL_THEME ?>/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo URL_THEME ?>/css/justified-nav.css" rel="stylesheet">
</head>

<body>

<div class="container">
    <?php function activeMenu($request){
        if($request == App::$request)
            echo 'class="active"';
    } ?>
    <div class="masthead">
        <h3 class="text-muted">Quick Minimalism  <small>Bootstrap Theme</small></h3>
        <ul class="nav nav-justified">
            <li <?php echo activeMenu(""); ?> ><a href="#">Главная</a></li>
            <li <?php echo activeMenu("blog"); ?> ><a href="?blog">Блог</a></li>
            <li <?php echo activeMenu("portfolio"); ?> ><a href="?portfolio">Портфолио</a></li>
            <li <?php echo activeMenu("about"); ?> ><a href="?about">Обо мне</a></li>
            <li <?php echo activeMenu("contacts"); ?> ><a href="?contacts">Контакты</a></li>
        </ul>
    </div>

    <!-- Jumbotron -->
    <div class="jumbotron">
        <h1>Marketing stuff!</h1>
        <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet.</p>
        <p><a class="btn btn-lg btn-success" href="#" role="button">Get started today</a></p>
    </div>

    <!-- Example row of columns -->
    <div class="row">
        <?php $this->show($viewName, true, true); ?>
    </div>

    <!-- Site footer -->
    <div class="footer">
        <p><?php echo QmConf("appCopy")?></p>
    </div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
</body>
</html>
