<div class="former">
    <p>&nbsp;</p>
    <form name="" action="http://" method="POST" enctype="multipart/form-data">

        <div><lable>Title</lable>
            <br/>
        <input name="name" type="text" value="" placeholder="title" />          </div><br />

        <div><lable>Category</lable>
            <br/>
            <select name="" size="1">
                <option value="" selected="selected">Категория</option>
                <?php foreach($category as $cat): ?>
                    <option value="<?php echo $cat ?>"><?php echo $cat ?></option>
                <?php endforeach; ?>
            </select>                                                            </div><br />

        <div><lable>Content</lable>
            <br/>
        <textarea name="name" placeholder="content" ></textarea>                 </div><br />

        <input name="name" type="hidden" value="" />
        <input type="submit" value="Сохранить" />
    </form>
</div>
