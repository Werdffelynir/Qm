
<ul>
    <?php foreach($allPages as $page): ?>
    <li>
        <a href="<?php echo URL?>/edit/page/<?php echo $page['id']?>"><?php echo $page['title']?></a>
        <a class="confirum btn-delete" href="<?=URL?>/edit/delete/<?php echo $page['id']; ?>"> [ delete ]</a>
    </li>
    <?php endforeach;?>
</ul>
