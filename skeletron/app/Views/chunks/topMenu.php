
    <li class="<?php activeMenu(""); ?>" ><a href="<?=URL?>/">Home</a></li>
    <li class="<?php activeMenu("index/doc"); ?>" ><a href="<?=URL?>/index/doc">Documentation</a></li>
    <li class="<?php activeMenu("index/controllers"); ?>" ><a href="<?=URL?>/index/controllers">Controllers</a></li>
    <li class="<?php activeMenu("index/models"); ?>" ><a href="<?=URL?>/index/models">Models</a></li>
    <li class="<?php activeMenu("index/views"); ?>" ><a href="<?=URL?>/index/views">Views</a></li>
    <li class="<?php activeMenu("index/download"); ?>" ><a href="<?=URL?>/index/download">Download</a></li>

<?php if (isset($this->id)): ?>
    <li <?php activeMenu("edit"); ?> ><a href="<?=URL?>/index/edit">Edit</a></li>
<?php endif; ?>