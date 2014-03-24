<html>
<head><title>Error 404</title></head>
<body style="background-color: #3C3F41">
<div
    style="margin: 100px; text-align: center; font-family: \'Aeromatics\', Arial, Helvetica, sans-serif; text-shadow: 0 1px 1px #000;">
    <h1 style="color: #ff0f00">ERROR 404! Page not exists!</h1>

    <p>Linked to <a style="color: #5894CC" href="<?=\Core\App::$getURL['base'];?>">start page</a> or <a style="color: #5894CC" href="<?=$_SERVER['HTTP_REFERER'];?>">back</a></p>

    <div
        style="width:500px;margin:0 auto;padding:10px;background-color: #2B2B2B;color: #dce9ff;border-radius:6px;text-align: left; font-size: 12px;">
        <?php if(isset($content)):?>
            <?php echo $content; ?>
        <?php else:?>
            <p>К сожалению, запрашиваемой Вами страницы не существует на нашем сайте. Возможно, это случилось по одной из
                следующих причин:</p>
            <ul>
                <li>Вы ошиблись при наборе адреса страницы (URL)</li>
                <li>Перешли по «битой» (неработающей, неправильной) ссылке</li>
                <li>Запрашиваемой страницы никогда не было на сайте или она была удалена</li>
                <li>Злой бандеровец утащил</li>
            </ul>
        <?php endif;?>

    </div>
</div>
</body>
</html>