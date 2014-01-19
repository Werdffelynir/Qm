<p>Файл модели будет создан в активном преложении (что указан в конфиг-файле)</p>
<p>"<b><?php echo App::$config['nameApp'];?>/Models/</b> * <b>.php</b>" (* - название )</p>
<div class="form-gener">
    <form action="" method="post">
        <p><lable>Название модели:</lable></p>
        <p><input name="nameModel" type="text"/></p>
        <p><lable>Генерация по шаблону:</lable></p>
        <p><select name="typeModel" size="1">
                <option value="base">Базовый</option>
                <option value="full" selected="selected">Полный</option>
            </select></p>
        <p><input type="submit" value="Создать Модель"/></p>
    </form>
</div>