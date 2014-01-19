<?php

/**
 * Файл генерации Gen.php
 *
 * Author: OL Werdffelynir
 * Date: 07.12.13
 * Time: 14:36
 */


class Gen
{
    public $pathTemplates;
    public $pathTheme;
    public $pathPages;
    public $requestPost;
    public $actionController;
    public $templates;

    public $title;
    public $content;

    public function __construct()
    {
        $this->templates = array(
            'bindIndex' => 'index.tpl',
            'controllerBase' => 'controller_base.tpl',
            'controllerFull' => 'controller_full.tpl',
            'modelBase' => 'model_base.tpl',
            'modelFull' => 'model_full.tpl',
            'appBootstrap' => 'app_bootstrap.tpl',
            'appFunctions' => 'app_functions.tpl',
            'appConfigApp' => 'app_configApplication.tpl',
            'appConfigAut' => 'app_configAutoload.tpl',
            'appController' => 'app_controller.tpl',
            'appClasses' => 'app_classes.tpl',
            'appControllerBase' => 'app_controller_base.tpl',
            'appModel' => 'app_model.tpl',
            'appStrAdminController' => 'app_structure_admin_controller.tpl',
            'appViewError' => 'app_views_error.tpl',
            'appViewThMain' => 'app_views_theme_main.tpl',
            'appViewThCss' => 'app_views_theme_css_default.tpl',
            'appViewPtMain' => 'app_views_partial_main.tpl',
            'appViewPtChunkCopy' => 'app_views_partial_chunks_copyright.tpl',
            'appViewPtChunkMenu' => 'app_views_partial_chunks_topmenu.tpl',
            'appViewPtIndexLogin' => 'app_views_partial_index_login.tpl',
            'appViewPtSbAbout' => 'app_views_partial_sidebars_about.tpl',
            'appViewPtSbLm1' => 'app_views_partial_sidebars_leftmenu.tpl',
            'appViewPtSbLm2' => 'app_views_partial_sidebars_leftmenutwo.tpl',
        );
        $this->pathTemplates = PATH_SYS . 'Generator' . DS . 'templates' . DS;
        $this->pathTheme = PATH_SYS . 'Generator' . DS . 'theme' . DS;
        $this->pathPages = PATH_SYS . 'Generator' . DS . 'pages' . DS;
        if (isset($_POST))
            $this->requestPost = $_POST;

        if (isset(App::$requestArray[2]))
            $this->actionController = App::$requestArray[2];
        else
            $this->actionController = 'Index';
    }

    /**
     * Запус преложения
     */
    public function run()
    {
        $actionController = 'action' . ucfirst($this->actionController);

        if (method_exists($this, $actionController))
            $this->$actionController();
        else
            $this->error404($actionController);

        $this->view();
    }

    /**
     * Вывод вида страницы html, принимает массив который распаковует на переменные
     * @param array $data массив данных
     */
    public function view($data = array())
    {
        extract($data);
        include $this->pathTheme . 'main.php';
    }

    /**
     * Открытие страниц контента
     *
     * @param string $file название страницы
     * @param array $data данные в страницу
     * @return string   сгенерированый HTML
     */
    public function openPage($file, array $data = array())
    {
        ob_start();
        extract($data);
        include $this->pathPages . $file . ".php";
        return ob_get_clean();
    }

    /*******************************************************************************
     * Вызываемые контролеры ***************************************************** */

    /**
     * @param $url
     */
    public function error404($url)
    {
        $this->title = "ERROR 404";
        $this->content = 'Method <b>' . $url . '</b> not exists!';
    }

    /**
     * Индекстная главная страница
     */
    public function actionIndex()
    {
        $this->title = "Generator";
        $this->content = $this->openPage('index');
    }

    /**
     * @param $url
     */
    public function actionInfo()
    {
        $this->title = "Generator";
        $this->content = $this->openPage('structure');
    }

    /**
     * Страница генерация нового контролера
     */
    public function actionController()
    {
        $contentText = '';

        if (empty($this->requestPost)) {
            $controllerFiles = '';
            $controllerExtendsFiles = '';
            $controllerScan = scandir(PATH_APP_CONTROLLERS);
            foreach ($controllerScan as $controllerFile) {
                if (preg_match("|^Controller*|", $controllerFile)) {
                    $controllerFiles .= '<li>' . $controllerFile . '</li>';
                } elseif ($controllerFile != "." AND $controllerFile != "..") {
                    $controllerExtendsFiles .= '<option value="' . $controllerFile . '" >' . $controllerFile . '</option>';
                }
            }
            $this->title = "Create new Controller";
            $this->content = $this->openPage('newcontroller',
                array(
                    'controllerExtendsFiles' => $controllerExtendsFiles,
                )
            );
        } else {
            //array(3) { ["nameController"]=> string(4) "Jopa" ["typeController"]=> string(4) "full" ["parentController"]=> string(10) "Controller" }

            if (preg_match("|^[a-zA-Z0-9]*$|", $_POST['nameController'])) {
                $nameController = mb_convert_case($_POST['nameController'], MB_CASE_TITLE, "UTF-8");

                if ($_POST['typeController'] == 'base')
                    $tpl_controller = $this->templates['controllerBase'];
                if ($_POST['typeController'] == 'full')
                    $tpl_controller = $this->templates['controllerFull'];

                $file = file_get_contents($this->pathTemplates . $tpl_controller);
                $file = str_replace("[[CONTROLLERNAME]]", $nameController, $file);

                if (preg_match('|^(.*)\.php$|', $_POST['parentController'], $parentControllerFind)) {
                    $parentController = $parentControllerFind[1];
                } else {
                    $parentController = $_POST['parentController'];
                }

                $file = str_replace("[[CONTROLLEREXTENDS]]", $parentController, $file);

                if (!file_exists(PATH_APP_CONTROLLERS . 'Controller' . $nameController . '.php')) {
                    file_put_contents(PATH_APP_CONTROLLERS . 'Controller' . $nameController . '.php', $file);

                    $contentText .= '<div class="g-success">Файл контролера был создан в активном преложении.</div>';
                    $contentText .= '<p>"<b>' . App::$config["nameApp"] . '/Controllers/Controller' . $nameController . '.php</b>"</p><br>';
                } else {
                    $contentText .= '<div class="g-error">Контролер с названием <b>' . 'Controller' . $nameController . '.php' . '</b> уже существует!</div>';
                }

            } else {
                $contentText .= '<div class="g-error">Введены запрещенные символы!</div>';
            }

            $this->title = "Create new Controller";
            $this->content = $this->openPage('newcontrollersuccess',
                array(
                    'nameController' => $_POST['nameController'],
                    'contentText' => $contentText,
                )
            );
        }
    }


    /**
     * Страница генерация новой модели
     */
    public function actionModel()
    {
        $contentText = '';

        if (empty($_POST['nameModel'])) {

            $this->title = "Create new Model";
            $this->content = $this->openPage('newmodel',
                array(//'controllerExtendsFiles'=>$controllerExtendsFiles,
                )
            );
        } elseif (isset($_POST['nameModel']) AND !empty($_POST['nameModel'])) {

            if (preg_match("|^[a-zA-Z0-9]*$|", $_POST['nameModel'])) {

                $nameModel = mb_convert_case($_POST['nameModel'], MB_CASE_TITLE, "UTF-8");

                if ($_POST['typeModel'] == 'base')
                    $tpl_model = $this->templates['modelBase'];
                if ($_POST['typeModel'] == 'full')
                    $tpl_model = $this->templates['modelFull'];

                $file = file_get_contents($this->pathTemplates . $tpl_model);
                $file = str_replace("[[MODELNAME]]", $nameModel, $file);

                if (!file_exists(PATH_APP_MODELS . $nameModel . '.php')) {

                    file_put_contents(PATH_APP_MODELS . $nameModel . '.php', $file);

                    $contentText .= '<div class="g-success">Файл модели был создан в активном преложении.</div>';
                    $contentText .= '<p>"<b>' . App::$config['nameApp'] . '/Models/' . $nameModel . '.php</b>"</p><br>';
                } else {
                    $contentText .= '<div class="g-error">Модель с названием <b>' . $nameModel . '.php' . '</b> уже существует!</div>';
                    $contentText .= '<p><a href="' . App::$url . '/generator/controller">Пересоздать.</a></p>';
                }

            } else {
                $contentText .= '<div class="g-error">Введены запрещенные символы!</div>';
                $contentText .= '<p><a href="' . App::$url . '/generator/controller">Пересоздать.</a></p>';
            }

            $this->title = "Create new Model";
            $this->content = $this->openPage('newmodelsuccess',
                array(
                    'nameController' => $_POST['nameModel'],
                    'contentText' => $contentText,
                )
            );
        }
    }


    /**
     * Страница генерация нового приложения
     */
    public function actionApp()
    {
        $contentText = '';

        if (!isset($_POST['newAppName']) OR empty($_POST['newAppName'])) {

            $this->title = "Генератор: Новое преложение";
            $this->content = $this->openPage('newapp', array());

        } else {

            if (preg_match("|^[a-zA-Z0-9]*$|", trim($_POST['newAppName']))) {

                $newAppName = trim($_POST['newAppName']);
                if (is_dir(PATH . $newAppName)) {

                    $contentText .= '<div class="g-error">Каталог "' . PATH . $newAppName . '" уже существует!</div>';
                    $contentText .= '<p><a href="' . App::$url . '/generator/app">Пересоздать.</a></p>';

                // Установка прав на директорию
                } elseif (mkdir(PATH . $newAppName, 0, true)) {

                    $newApp = PATH . $newAppName . DS;


                    $createClasses = $newApp . 'Classes/';
                    $createControllers = $newApp . 'Controllers/';
                    $createDataBase = $newApp . 'DataBase/';
                    $createExtension = $newApp . 'Extension/';
                    $createHelpers = $newApp . 'Helpers/';
                    $createModels = $newApp . 'Models/Admin';
                    $createStructureControllers = $newApp . 'Structure/administrator/Controllers/';
                    $createStructureModels = $newApp . 'Structure/administrator/Models/';
                    $createStructureViews = $newApp . 'Structure/administrator/ViewsPartials/';
                    $createViewsPaIndex = $newApp . 'ViewsPartials/index/';
                    $createViewsPaChunk = $newApp . 'ViewsPartials/chunks/';
                    $createViewsPaSb = $newApp . 'ViewsPartials/sidebars/';
                    $createViewsThCss = $newApp . 'ViewsTheme/default/css/';
                    $createViewsThJs = $newApp . 'ViewsTheme/default/js/';
                    $createViewsThImg = $newApp . 'ViewsTheme/default/images/';

                    /** Содержание для корневых файлов */
                    $fileIndex = file_get_contents($this->pathTemplates . 'app_index.tpl');
                    $fileFunction = file_get_contents($this->pathTemplates . 'app_functions.tpl');
                    $fileBootstrap = file_get_contents($this->pathTemplates . 'app_bootstrap.tpl');
                    $fileConfigApp = file_get_contents($this->pathTemplates . 'app_configApplication.tpl');
                    $fileConfigAvl = file_get_contents($this->pathTemplates . 'app_configAutoload.tpl');

                    /** Содержание для внутрених файлов в базовых каталогах Controllers, Models, Helpers и Classes  */
                    $fileClasses = file_get_contents($this->pathTemplates . 'app_classes.tpl');
                    $fileHelpers = file_get_contents($this->pathTemplates . 'app_helper.tpl');
                    $fileModelsBase = file_get_contents($this->pathTemplates . 'app_model_base.tpl');
                    $fileModelsEdit = file_get_contents($this->pathTemplates . 'app_model_edit.tpl');
                    $fileControllers = file_get_contents($this->pathTemplates . 'app_controller.tpl');
                    $fileControllersBase = file_get_contents($this->pathTemplates . 'app_controller_base.tpl');

                    /** Содержание для Структуры */
                    $fileStructureAdminControllers = file_get_contents($this->pathTemplates . 'app_structure_admin_controller.tpl');
                    $fileStructureAdminModel = file_get_contents($this->pathTemplates . 'app_structure_admin_model.tpl');

                    /** Содержание для файлов вида каталогов ViewsPartials, ViewsTheme  */
                    $fileViewsPaMain = file_get_contents($this->pathTemplates . 'app_views_partial_main.tpl');
                    $fileViewsPaChunkCopy = file_get_contents($this->pathTemplates . 'app_views_partial_chunks_copyright.tpl');
                    $fileViewsPaChunkTopmenu = file_get_contents($this->pathTemplates . 'app_views_partial_chunks_topmenu.tpl');
                    $fileViewsPaIndexLogin = file_get_contents($this->pathTemplates . 'app_views_partial_index_login.tpl');
                    $fileViewsPaSidebarAbout = file_get_contents($this->pathTemplates . 'app_views_partial_sidebars_about.tpl');
                    $fileViewsPaSidebarLMenu = file_get_contents($this->pathTemplates . 'app_views_partial_sidebars_leftmenu.tpl');
                    $fileViewsPaSidebarLMenu2 = file_get_contents($this->pathTemplates . 'app_views_partial_sidebars_leftmenutwo.tpl');
                    $fileViewsThError = file_get_contents($this->pathTemplates . 'app_views_error.tpl');
                    $fileViewsThDefMain = file_get_contents($this->pathTemplates . 'app_views_theme_main.tpl');
                    $fileViewsThDefCssDef = file_get_contents($this->pathTemplates . 'app_views_theme_css_default.tpl');


                    if (!mkdir($createClasses, 0, true))
                        goto notCreate;
                    if (!mkdir($createControllers, 0, true))
                        goto notCreate;
                    if (!mkdir($createDataBase, 0, true))
                        goto notCreate;
                    if (!mkdir($createExtension, 0, true))
                        goto notCreate;
                    if (!mkdir($createHelpers, 0, true))
                        goto notCreate;
                    if (!mkdir($createModels, 0, true))
                        goto notCreate;

                    if (!mkdir($createStructureControllers, 0, true))
                        goto notCreate;

                    if (!mkdir($createStructureModels, 0, true))
                        goto notCreate;
                    if (!mkdir($createStructureViews, 0, true))
                        goto notCreate;
                    if (!mkdir($createViewsPaIndex, 0, true))
                        goto notCreate;
                    if (!mkdir($createViewsPaChunk, 0, true))
                        goto notCreate;
                    if (!mkdir($createViewsPaSb, 0, true))
                        goto notCreate;
                    if (!mkdir($createViewsThCss, 0, true))
                        goto notCreate;
                    if (!mkdir($createViewsThJs, 0, true))
                        goto notCreate;
                    if (!mkdir($createViewsThImg, 0, true))
                        goto notCreate;


                    /** Создание файлов корневых преложения */
                    file_put_contents($newApp . 'index.html', $fileIndex);
                    file_put_contents($newApp . 'functions.php', $fileFunction);
                    file_put_contents($newApp . 'bootstrap.php', $fileBootstrap);
                    file_put_contents($newApp . 'configApplication.php', $fileConfigApp);
                    file_put_contents($newApp . 'configAutoload.php', $fileConfigAvl);
                    /** Создание файлов внутрених в базовых каталогах Controllers, Models, и Classes */
                    file_put_contents($newApp . 'Classes/QmClasses.php', $fileClasses);
                    file_put_contents($newApp . 'Controllers/ControllerIndex.php', $fileControllers);
                    file_put_contents($newApp . 'Controllers/BaseController.php', $fileControllersBase);
                    file_put_contents($newApp . 'Helpers/QmHelpers.php', $fileHelpers);
                    file_put_contents($newApp . 'Models/Base.php', $fileModelsBase);
                    file_put_contents($newApp . 'Models/Admin/Edit.php', $fileModelsEdit);
                    /** Создание файлов внутри структуры-модуля */
                    file_put_contents($newApp . 'Structure/functions.php', $fileFunction);
                    file_put_contents($newApp . 'Structure/administrator/Controllers/ControllerIndex.php', $fileStructureAdminControllers);
                    file_put_contents($newApp . 'Structure/administrator/Models/BaseAdmin.php', $fileStructureAdminModel);
                    file_put_contents($newApp . 'Structure/administrator/ViewsPartials/main.php',  $fileViewsPaMain);
                    /** Создание файлов внутри ViewsPartials */
                    file_put_contents($newApp . 'ViewsPartials/main.php', $fileViewsPaMain);
                    file_put_contents($newApp . 'ViewsPartials/chunks/copyright.php', $fileViewsPaChunkCopy);
                    file_put_contents($newApp . 'ViewsPartials/chunks/topmenu.php', $fileViewsPaChunkTopmenu);
                    file_put_contents($newApp . 'ViewsPartials/index/login.php', $fileViewsPaIndexLogin);
                    file_put_contents($newApp . 'ViewsPartials/sidebars/about.php', $fileViewsPaSidebarAbout);
                    file_put_contents($newApp . 'ViewsPartials/sidebars/leftmenu.php', $fileViewsPaSidebarLMenu);
                    file_put_contents($newApp . 'ViewsPartials/sidebars/leftmenutwo.php', $fileViewsPaSidebarLMenu2);
                    /** Создание файлов внутри ViewsTheme */
                    file_put_contents($newApp . 'ViewsTheme/error404.php', $fileViewsThError);
                    file_put_contents($newApp . 'ViewsTheme/default/main.php', $fileViewsThDefMain);
                    file_put_contents($newApp . 'ViewsTheme/default/css/default.css', $fileViewsThDefCssDef);

                    $contentText .= '<div class="g-success">Файлы нового приложения успешно созданы в корне фреймворка. </div>
<p>Для активировации приложения измениете параметр "nameApp" на имя созданого каталога: <b>'. $newAppName.'</b> в конфиг-файле
ядара системе (system/configSystem.php). </p>';
                    $contentText .= $this->openPage('structure', array('newAppName' => $newAppName));
                } else {
                    notCreate:
                    $contentText .= '<div class="g-error">ОШИБКА! <br>Файлы не возможно создать! Проверкте права на директорию.</div>';
                }

            } else {
                $contentText .= '<div class="g-error">ОШИБКА! Введены запрещенные символы!</div>';
                $contentText .= "<p>Еще раз <a href='" . URL . "/gen/app'>создать преложение</a>. </p>";
            }


            $this->title = "Генератор: Новое преложение";
            $this->content = $this->openPage('newappsuccess',
                array(
                    'newAppName' => $newAppName,
                    'contentText' => $contentText,
                )
            );
        }
    }


}