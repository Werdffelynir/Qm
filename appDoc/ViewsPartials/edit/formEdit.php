<?php $editFilshMessage = App::flashArray('update'); if(!empty($editFilshMessage)): ?>
<div class="<?php echo $editFilshMessage['class']; ?>">
    <?php echo $editFilshMessage['message']; ?>
</div>
<?php endif;?>

<br/>
<div class="former">

    <form name="" action="<?php echo APP::$url.'/edit/save'; ?>" method="POST">
        <table border="0">
            <tr>
               <td colspan="2">
                    <div><lable>Заголовок</lable><br/>
                        <input name="title" type="text" value="<?php echo $title ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><lable>Категория</lable>
                        <br/>
                        <select name="category" size="1">
                            <option value="<?php echo $category ?>" selected="selected"><?php echo $category ?></option>
                            <?php foreach($catList as $cat): ?>
                                <option value="<?php echo $cat ?>"><?php echo $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div><lable>Тип страницы</lable>
                        <br/>
                        <select name="type" size="1">
                            <?php echo (isset($type)) ? '<option value="'.$type.'" selected="selected">'.$type.'</option>' : '<option value="-">-</option>' ?>
                            <option value="Top Menu">Top Menu</option>
                            <option value="Quick Start">Quick Start</option>
                            <option value="Page">Page</option>
                            <option value="Editor Gen">Editor Gen</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><lable>Под категория</lable>
                        <br/>
                        <select name="subcategory" size="1">
                            <option value="<?php echo $subcategory ?>" selected="selected"><?php echo $subcategory ?></option>
                            <?php foreach($subCatList as $subCat): ?>
                                <option value="<?php echo $subCat ?>"><?php echo $subCat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div><lable>Внутренняя ссылка</lable><br/>
                        <input name="link" type="text" value="<?php echo $link ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="textAreaContent"><lable>Содержание</lable><br/>
                        <textarea id="textAreaContent" name="content" ><?php echo $content ?></textarea>
                    </div>
                </td>
            </tr>
        </table>
        <input name="id" type="hidden" value="<?php echo $id ?>" />
        <input name="typeID" type="hidden" value="<?php echo $this->isExists($typeID); ?>" />
        <input type="submit" value="Сохранить" />
    </form>

</div>
<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        new nicEditor().panelInstance('textAreaContent');
        //iconsPath
    });
</script>