<?php function activeMenu($request){
    if($request == App::$request)
        echo 'class="active"';
} ?>

    <li <?php activeMenu(""); ?> ><a href="<?=App::$url?>/">Home</a></li>
    <li <?php activeMenu("doc"); ?> ><a href="<?=App::$url?>/doc">Documentation</a></li>
    <li <?php activeMenu("controllers"); ?> ><a href="<?=App::$url?>/controllers">Controllers</a></li>
    <li <?php activeMenu("models"); ?> ><a href="<?=App::$url?>/models">Models</a></li>
    <li <?php activeMenu("views"); ?> ><a href="<?=App::$url?>/views">Views</a></li>
    <li <?php activeMenu("download"); ?> ><a href="<?=App::$url?>/download">Download</a></li>
    <?php if(QmUser::id()):?>
        <li <?php activeMenu("edit"); ?> ><a href="<?=App::$url?>/edit">Edit</a></li>
    <?php endif;?>