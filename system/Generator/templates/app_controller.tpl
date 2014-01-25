<?php
/**
 * Файл был сгенерирован с помощю Gen Qm Framework
 *
 * Необходимо провести реорганизацию кода даного контролера
 */

class ControllerIndex extends BaseController
{

    public function actionIndex()
    {
        $this->data['title']   = "HOME PAGE";
        $this->data['content'] = "<p>Равным образом начало повседневной работы по формированию позиции требует от нас анализа идей по выходу из сложившейся ситуации. Значимость этих проблем настолько очевидна, что консультации с широким активом требует от нас анализа системы массового участия.</p><p>Не следует, однако, забывать, что новая модель организационной деятельности создает все необходимые предпосылки для утверждения системы массового участия.Не следует, однако, забывать, что дальнейшее развитие различных форм деятельности ни коим образом не противоречит нашей теории, а скорее наоборот, способствует улучшению новейших вариантов поиска решений.</p>";

        $this->show('main');
    }


    public function actionBlog()
    {
        $this->setChunk('leftmenu','sidebars/leftmenutwo');

        $this->data['title']   = "BLOG";
        $this->data['content'] = "<p>Не следует, однако, забывать, что сложившаяся структура организации укрепляет нас, в нашем стремлении улучшения форм воздействия.С другой стороны новая модель организационной деятельности укрепляет нас, в нашем стремлении улучшения системы массового участия.</p><p>Задача организации в особенности же новая модель организационной деятельности в значительной степени обусловливает создание правильных направлений развития.Повседневная практика показывает, что начало повседневной работы по формированию позиции требует определения и уточнения идей по выходу из сложившейся ситуации.</p><p>С другой стороны дальнейшее развитие различных форм деятельности позволяет выполнить важные задания по разработке новейших вариантов поиска решений.Значимость этих проблем настолько очевидна, что новая модель организационной деятельности создает все необходимые предпосылки для утверждения новых предложений.</p>";

        $this->show('main');
    }


    public function actionAbout()
    {
        $this->data['title']   = "ABOUT";
        $this->data['content'] = "<p>Не следует, однако, забывать, что движение в данном направлении позволяет выполнить важные задания по разработке правильных направлений развития.Повседневная практика показывает, что консультации с широким активом укрепляет нас, в нашем стремлении улучшения позиций, занимаемых участниками в отношении поставленных задач.</p><p>Повседневная практика показывает, что реализация намеченных плановых заданий играет важную роль в формировании и анализу необходимых данных для разрешения ситуации в целом.Задача организации в особенности же начало повседневной работы по формированию позиции ни коим образом не противоречит нашей теории, а скорее наоборот, способствует улучшению направлений прогрессивного развития.</p>";

        $result = $this->model('Base');
        $this->data['content'] .= '<div>'.$result->all().'</div>';

        $resultAdmin = $this->model('Admin/Edit');
        $this->data['content'] .= '<div>'.$resultAdmin->one().'</div>';

        $this->show('main');
    }


    public function actionContacts()
    {
        $this->data['title']   = "CONTACTS";
        $this->data['content'] = "<p>Не следует, однако, забывать, что движение в данном направлении позволяет выполнить важные задания по разработке правильных направлений развития.Повседневная практика показывает, что консультации с широким активом укрепляет нас, в нашем стремлении улучшения позиций, занимаемых участниками в отношении поставленных задач.</p><p>Повседневная практика показывает, что реализация намеченных плановых заданий играет важную роль в формировании и анализу необходимых данных для разрешения ситуации в целом.Задача организации в особенности же начало повседневной работы по формированию позиции ни коим образом не противоречит нашей теории, а скорее наоборот, способствует улучшению направлений прогрессивного развития.</p>";

        $this->show('main');
    }


    public function actionLogin()
    {
        $this->data['title']   = "actionLogin";
        $this->data['content'] = "";

        $this->show('index/login');
    }

}