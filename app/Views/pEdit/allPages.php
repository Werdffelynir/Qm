

<ul>
    <?php foreach($allPages as $page): ?>
    <li><a href="<?php echo URL?>/edit/page/<?php echo $page['id']?>"><?php echo $page['title']?></a></li>
    <?php endforeach;?>
</ul>