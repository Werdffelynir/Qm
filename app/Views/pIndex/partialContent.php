


<p>
<b>Quick Minimalism</b> - это PHP-фреймворк для создания сайтов и разработки разнообразных веб-приложений. <b>Quick Minimalism</b> предоставляет готовые инструменты для авторизации пользователей, регулирования прав доступа, маршрутизации, CSS-инструментарий для быстрого создания выразительных веб-интерфейсов и много другое. Фреймворк прекрасно подходит для разработки веб-приложений для бизнеса и командного использования.
</p>

<p>
Лежит в основе многих популярных веб-приложений. Например, Shop-Script 5.
</p>

<p>
Работает на основе PHP, MySQL, Smarty и jQuery.
</p>


<h2>Стстемные требования.</h2>
<p>
Фреймворк прекрасно подходит для разработки веб-приложений для бизнеса и командного использования.
</p>

<h2 class="btn_blue"><a href="/?link">Скачать</a></h2>

<p>
Фреймворк прекрасно подходит для разработки веб-приложений для бизнеса и командного использования.
</p>
<h2 class="btn_black"><a href="/?link">Пример преложения</a></h2>

<h2>Что внутри.</h2>
<p>
Лежит в основе многих популярных веб-приложений. Например, CSS-инструментарий для быстрого создания выразительных веб-интерфейсов и много другое.
</p><code></code>
<div class="code">
<pre>
APPLICATION                         <span class="commentos">Корень фреймворка</span>
¦   .htaccess                       <span class="commentos">Стандартно, настройки для Apache</span>
¦   index.php                       <span class="commentos">Точка вхождения.</span>
¦
+---app                             <span class="commentos">Установленные приложения.</span>
¦   ¦   bootstrap.php               <span class="commentos">Возможные загрузки в преложении.</span>
¦   ¦   configuration.php           <span class="commentos">Конфигурация преложения</span>
¦   ¦   functions.php               <span class="commentos">Возможные процедурные функции преложения.</span>
¦   ¦
¦   +---Classes                     <span class="commentos">Классы для дополнительного расширения <a href="#">Детальней</a>.</span>
¦   +---Controllers                 <span class="commentos">Логика преложения. <a href="#">В документацию</a>.</span>
¦   ¦       ControllerIndex.php     <span class="commentos">Основной контрелер.</span>
¦   ¦
¦   +---Models                      <span class="commentos">Логика работы с БД SQL запросов <a href="#">В документацию</a>.</span>
¦   +---Structure                   <span class="commentos">Структуры модулей</span>
¦   ¦   L---administrator           <span class="commentos">Например. Сруктура адмиен части преложения <a href="#">В документацию</a>.</span>
¦   ¦       +---Controllers         <span class="commentos">...</span>
¦   ¦       +---Models              <span class="commentos">...</span>
¦   ¦       +---Views               <span class="commentos">...</span>
¦   ¦
¦   +---Views                       <span class="commentos">Представления или виды преложения. <a href="#">Работа с видами</a>.</span>
¦   ¦   +---main.php                <span class="commentos">Основной вид.</span>
¦   ¦
¦   +---_protected                  <span class="commentos">Каталог для необходимых дополнительных файлов.</span>
¦       ¦   AppConfig.php           <span class="commentos">Напр. файл конфигурации.</span>
¦       ¦
¦       +---DATABASE                <span class="commentos">Или БД</span>
¦               QmDataBase.sqlite   <span class="commentos"></span>
¦
+---lib                             <span class="commentos">Системная часть (ядро) фреймворка <a href="#">В документацию</a></span>
¦   ¦   bootstrap.php               <span class="commentos">Запус всех необходимых частей фреймворка</span>
¦   ¦   configuration.php           <span class="commentos">Базовая и системная конфигурация преложения и фреймворка.</span>
¦   ¦   functions.php               <span class="commentos">Системные процедурные функции.</span>
¦   ¦
¦   +---Abstracts                   <span class="commentos">Каталог базовых абстрактных классов.</span>
¦   ¦       Base.php                <span class="commentos">Абстрактный базовый файл.</span>
¦   ¦       Controller.php          <span class="commentos">Абстрактный контролер.</span>
¦   ¦       Model.php               <span class="commentos">Абстрактная модель.</span>
¦   ¦
¦   +---Classes                     <span class="commentos">Системные Классы расширения</span>
¦   ¦       ClLoader.php            <span class="commentos"></span>
¦   ¦       SafePDO.php             <span class="commentos"></span>
¦   ¦       SimplePDO.php           <span class="commentos"></span>
¦   ¦
¦   +---Core                        <span class="commentos">Директория ядра</span>
¦   ¦   ¦   App.php                 <span class="commentos">Файл ядра</span>
¦   ¦   ¦
¦   ¦   +---Gen                     <span class="commentos">Генератор кода</span>
¦   ¦
¦   +---DATABASE                    <span class="commentos">Каталог с файлами базы-данных SQLite и Файловой БД</span>
¦           QmDataBase.sqlite       <span class="commentos">БД SQLite</span>
¦
+---theme                           <span class="commentos">Директория шаблонов layout (темы)</span>
¦   error404.php                    <span class="commentos"></span>
¦
+---defaultTheme                    <span class="commentos">Тема (шаблон) по умолчанию.</span>
¦   ¦   main.php                    <span class="commentos">Фреймворк запускает файл main.php по умолчанию.</span>
¦   ¦
¦   +---css                         <span class="commentos">Вложеные необходимые каталоги для шаблона</span>
¦   +---fonts                       <span class="commentos"></span>
¦   +---js                          <span class="commentos"></span>
¦
+---qmTheme                         <span class="commentos">Может размежать множество тем.</span>
    </pre>
</div>

<h2>Разработка.</h2>
<p>
Например, Лежит в основе многих популярных CSS-инструментарий для быстрого создания выразительных веб-интерфейсов и много другое.
</p>