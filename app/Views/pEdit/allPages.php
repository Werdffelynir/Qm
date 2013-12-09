
<ul>
    <?php foreach($allPages as $page): ?>
    <li>
        <a href="<?php echo URL?>/edit/page/<?php echo $page['id']?>"><?php echo $page['title']?></a>
        <a class="confirum btn-delete" href="<?=URL?>/edit/deletepage/<?php echo $page['id']; ?>"> [ delete ]</a>
        <a class="confirum btn-edit" href="<?=URL?>/edit/page/<?php echo $page['id']; ?>"> [ edit ]</a>
    </li>
    <?php endforeach;?>
</ul>
