<?php function activeMenu($request){
    if($request == App::$request)
        echo 'class="active"';
} ?>

<li <?php echo activeMenu(""); ?> ><a href="<?=URL?>/">Home</a></li>
<li <?php echo activeMenu("docs"); ?> ><a href="<?=URL?>/docs">Documentation</a></li>
<li <?php echo activeMenu("controllers"); ?> ><a href="<?=URL?>/controllers">Controllers</a></li>
<li <?php echo activeMenu("models"); ?> ><a href="<?=URL?>/models">Models</a></li>
<li <?php echo activeMenu("views"); ?> ><a href="<?=URL?>/views">Views</a></li>
<li <?php echo activeMenu("download"); ?> ><a href="<?=URL?>/download">Download</a></li>
<?php if(QmUser::id()):?>
    <li <?php echo activeMenu("edit"); ?> ><a href="<?=URL?>/edit">Edit</a></li>
<?php endif;?>