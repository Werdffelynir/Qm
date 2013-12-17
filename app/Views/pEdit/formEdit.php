<br>
<div class="former">
    <form name="" action="<?php echo URL.'/edit/createpagesave'; ?>" method="POST" enctype="multipart/form-data">

        <div><lable>Заголовок</lable><br/>
            <input name="title" type="text" value="<?php $this->showData("form_title") ?>" />
        </div><br />

        <div><lable>Категория</lable>
            <br/>
            <select name="category" size="1">
                <option value="" selected="selected"><?php $this->showData("form_category") ?></option>
                <?php foreach($category as $cat): ?>
                    <option value="<?php echo $cat ?>"><?php echo $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div><br />

        <div><lable>Содержание</lable><br/>
        <textarea id="textAreaContent" name="content" ><?php $this->showData("form_content") ?></textarea>
        </div><br />

        <input name="id" type="hidden" value="<?php $this->showData("form_title") ?>" />
        <input type="submit" value="Сохранить" />
    </form>
</div>
<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        new nicEditor().panelInstance('textAreaContent');
    });
</script>