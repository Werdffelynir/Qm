<p>Файл контролера будет создан в активном преложении (что указан в конфиг-файле)</p>
<p>Сейчас это: </p>
<p>"<b><?php echo App::$config["nameApp"]; ?>/Controllers/Controller</b>*<b>.php</b>"</p>
<p>(* - Название контролера )</p>

<div class="form-gener">
    <form action="" method="post">
        <p><lable>Название контролера:</lable></p>
        <p><input name="nameController" type="text"/></p>
        <p><lable>Генерация по шаблону:</lable></p>
        <p><select name="typeController">
                <option value="base">Базовый</option>
                <option value="full" selected="selected">Полный</option>
            </select>
        </p>
        <p><lable>Наследуеться от:</lable></p>
        <p><select name="parentController">
                <option value="Controller" selected="selected">Controller</option>
                <?php echo $controllerExtendsFiles; ?>
            </select>
        </p>
        <p><input type="submit" value="Создать Контролер"/></p>
    </form>
</div>