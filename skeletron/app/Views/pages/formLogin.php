<h1>Вход в систему</h1><p>Для входа в систему используйте демо акаунт</p>

    <br/>email: <b>admin@admin.com</b>
    <br/>password: <b>admin</b>

<div class="formlogin">

    <?php if($formMess['eMessage'] ):?>
        <div style="color:#DD0000; font-weight: bold;"><?php echo $formMess['eMessage'];?></div>
    <?php endif; ?>

    <form method="post" action="<?=URL?>/index/login">

        <div>
            <lable>Email</lable>
            <br/>
            <input name="email" class="input" type="email" placeholder="Email" value="<?php echo $formMess['fEmail'] ?>"></div>

        <div>
            <lable>Password</lable>
            <br/>
            <input name="password" class="input" type="password" placeholder="Password" value="<?php echo $formMess['fPassword'] ?>"></div>

        <div><input class="input" type="submit" value="Войти"></div>

    </form>
</div>