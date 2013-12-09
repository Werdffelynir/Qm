
<ul>
    <?php foreach($allPages as $page): ?>
    <li>
        <a href="<?php echo URL?>/edit/page/<?php echo $page['id']?>"><?php echo $page['title']?></a>
        <a class="confirum" href="<?=URL?>/index">Удалить</a>
    </li>
    <?php endforeach;?>
</ul>

<button id="button_1">Delete me</button>

