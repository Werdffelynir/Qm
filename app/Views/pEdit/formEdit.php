<br>
<div class="former">
    <form name="" action="<?php echo URL.'/edit/save'; ?>" method="POST">

        <div><lable>Заголовок</lable><br/>
            <input name="title" type="text" value="<?php echo $title ?>" />
        </div><br />

        <div><lable>Внутренняя ссылка</lable><br/>
            <input name="link" type="text" value="<?php echo $link ?>" />
        </div><br />

        <div><lable>Категория</lable>
            <br/>
            <select name="category" size="1">
                <option value="<?php echo $category ?>" selected="selected"><?php echo $category ?></option>
                <?php foreach($catList as $cat): ?>
                    <option value="<?php echo $cat ?>"><?php echo $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div><br />

        <div><lable>Содержание</lable><br/>
        <textarea id="textAreaContent" name="content" ><?php echo $content ?></textarea>
        </div><br />

        <input name="id" type="hidden" value="<?php echo $id ?>" />
        <input name="type" type="hidden" value="<?php echo $type ?>" />
        <input type="submit" value="Сохранить" />
    </form>
</div>
<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        new nicEditor().panelInstance('textAreaContent');
    });
</script>