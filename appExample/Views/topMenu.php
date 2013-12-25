<?php function activeMenu($request){
    if($request == App::$request)
        echo 'class="active"';
} ?>
<li <?php echo activeMenu(""); ?> ><a href="/">Home</a></li>
<li <?php echo activeMenu("docs"); ?> ><a href="/?docs">Documentation</a></li>
<li <?php echo activeMenu("controllers"); ?> ><a href="/?controllers">Controllers</a></li>
<li <?php echo activeMenu("models"); ?> ><a href="/?models">Models</a></li>
<li <?php echo activeMenu("views"); ?> ><a href="/?views">Views</a></li>
<li <?php echo activeMenu("download"); ?> ><a href="/?download">Download</a></li>