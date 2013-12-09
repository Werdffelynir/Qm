<br>
<div class="former">
    <form name="" action="<?php echo URL.'/edit/createpagesave'; ?>" method="POST" enctype="multipart/form-data">

        <div><lable>Заголовок</lable><br/>
            <input name="title" type="text" value="" />
        </div><br />

        <div><lable>Категория</lable>
            <br/>
            <select name="category" size="1">
                <option value="" selected="selected">Категория</option>
                <?php foreach($category as $cat): ?>
                    <option value="<?php echo $cat ?>"><?php echo $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div><br />

        <div><lable>Содержание</lable><br/>
        <textarea id="textAreaContent" name="content" ></textarea>
        </div><br />

        <input name="name" type="hidden" value="" />
        <input type="submit" value="Сохранить" />
    </form>
</div>
<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        new nicEditor().panelInstance('textAreaContent');
    });
</script>