<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 14.03.14
 * Time: 0:55
 */

namespace Classes;


class Ctrl extends \Core\Controller
{

    public function before()
    {
        $this->setChunk('topMenu','chunks/topMenu');
        $this->setChunk('sidebarOne','chunks/sidebarAbout');
        $this->setChunk('sidebarTwo','chunks/sidebarQuickStart');

        /* $this->scriptRegister(array('name'=>'js_one',   'url'=>'js/script_1', 'priority'=>3));
        $this->scriptRegister(array('name'=>'js_two',   'url'=>'js/script_2', 'priority'=>1));
        $this->scriptRegister(array('name'=>'js_three', 'url'=>'js/script_3', 'priority'=>2));
        $this->scriptRegister(array('name'=>'js_fore',  'url'=>'js/script_4', 'priority'=>4));

        $this->scriptRegister(array('name'=>'js_footer_one',  'url'=>'js/script_5', 'priority'=>6, 'group'=>'footer'));
        $this->scriptRegister(array('name'=>'js_footer_two',  'url'=>'js/script_6', 'priority'=>5, 'group'=>'footer'));

        $this->styleRegister(array('name'=>'css_one',   'url'=>'css/style_1', 'priority'=>10));
        $this->styleRegister(array('name'=>'css_two',   'url'=>'css/style_2', 'priority'=>8));
        $this->styleRegister(array('name'=>'css_three', 'url'=>'css/style_3', 'priority'=>21));
        $this->styleRegister(array('name'=>'css_fore',  'url'=>'css/fonts/style_4', 'priority'=>20));

        $this->scriptFixed('js_fore');
        $this->scriptFixed('js_one');
        $this->scriptFixed('js_two');
        $this->scriptFixed('js_three');

        $this->scriptFixed(array('js_footer_one','js_footer_two')); */

        $this->styleFixed();
        //$this->styleFixed(array('js_fore','js_one','js_two','js_three'));

        //var_dump($this->scripts);



    }


} 