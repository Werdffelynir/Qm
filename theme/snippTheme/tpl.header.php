
<!-- HEADER TOP -->
    <header class="header lite clear">

        <!-- Логотип, название -->
        <div class="logo first lite_3">
            <h2>Lite <span class="colorBlue">{</span><a class="colorYell" href="<?php echo $url; ?>">Snippets</a><span class="colorBlue">}</span> Notes</h2>
        </div>

        <!-- Поиск -->
        <div class="search lite_3">
            <form id="#" name="#" action="http://">
                <input name="#" type="text">
                    <span class="btn-seach">
                        <a href="#"><i class="icon-search"></i></a>
                    </span>
                <!--<input name="" type="submit" value="Поиск">-->
            </form>
        </div>

        <!-- Верхнее меню -->
        <div class="topmenu lite_4">
            <nav>
                <ul class="btn-line">
                    <li class="active"><a href="#" class="wineOpen" title="Добавить сниппет"><i class="icon-plus"></i></a></li>
                    <li><a href="#" class="wineOpen" title="Загрузить файл с сниппетом"><i class="icon-inbox"></i></a></li>
                    <li><a href="#" class="wineOpen" title="Последние просмотренные"><i class="icon-fire"></i></a></li>
                    <li><a href="#" class="wineOpen" title="Запомнить снипет"><i class="icon-target"></i></a></li>
                    <li><a href="#" class="wineOpen" title="Настройки"><i class="icon-cog"></i></a></li>
                    <li><a href="#" class="consoleOpen" title="Констоль"><i class="icon-credit-card"></i></a></li>
                </ul>
            </nav>
        </div>

        <!-- Форма Входа -->
        <div class="auth lite_2">
            <ul class="btn-line">

                <?php if(!empty(Yii::app()->user->id)): ?>
                    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/index/logout">Выйти</a></li>
                    <p class="s_title">Привет <b><?php echo Yii::app()->user->name; ?></b></p>
                <?php else: ?>
                    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/index/login">Вход</a></li>
                    <li><a href="#">Регистрация</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

<!-- TOP-MENU CATEGORIES  class="active"-->
    <div class="category lite clear">
        <ul class="">
            <?php $this->widget('application.widgets.CatMenu'); ?>
        </ul>
    </div>

