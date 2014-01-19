<p>Структура нового приложения</p>

<pre>
[new_app_name]
¦
¦   bootstrap.php
¦   configApplication.php
¦   configAutoload.php
¦   functions.php
¦   index.html
¦
+---Classes
¦       QmClasses.php
¦
+---Controllers
¦       BaseController.php
¦       ControllerIndex.php
¦
+---DataBase
¦
+---Extension
¦
+---Helpers
¦       QmHelper.php
¦
+---Models
¦       Base.php
¦
+---Structures
¦   +---administrator
¦       ¦   functions.php
¦       ¦
¦       +---Controllers
¦       ¦       ControllerIndex.php
¦       ¦
¦       +---Models
¦       ¦       Base.php
¦       ¦
¦       L---ViewsPartials
¦               main.php
¦
¦
+---ViewsPartials
¦   ¦   main.php
¦   ¦
¦   +---chunks
¦   ¦       copyright.php
¦   ¦       topmenu.php
¦   ¦
¦   +---index
¦   ¦       login.php
¦   ¦
¦   L---sidebars
¦           about.php
¦           leftmenu.php
¦           leftmenutwo.php
¦
L---ViewsTheme
    ¦   error404.php
    ¦
    L---default
        ¦   main.php
        ¦
        +---css
        ¦       default.css
        ¦
        +---images
        L---js
</pre>
