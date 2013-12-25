<?php

class Pages extends Model
{

    /**
     * @return array
     */
    public function all()
    {
        $sql = "SELECT * FROM pages";
        return $this->db->query($sql)->all();
    }



    /**
     * Get all pages to realise as main-menu
     * @return array
     */
    public function getMenu()
    {
        return  $this->getAll("pages", array("id", "title"));
    }



    /**
     * Get all pages
     * @return array
     */
    public function getPages()
    {
        return  $this->getAll("pages");
    }



    /**
     * Выбрать строку из базы, по ключу или по столбцу.
     *
     * @param $id Параметр может принимать номер (string) ID строки,
     *                  Или (array) 'name_column'=>'value_column'
     * @return array
     */
    public function getPageById($id)
    {
        $page = $this->getById("pages", $id);

        return array(
            'title'       =>$page['title'],
            'link'        =>$page['link'],
            'category'    =>$page['category'],
            'subcategory' =>$page['subcategory'],
            'content'     =>$page['content'],
            'datetime'    =>$page['datetime'],
            'author'      =>$page['author']
        );
    }
    public function getPageByLink($getPage)
    {
        //$this->db->close();
        $page = $this->getByAttr("pages", 'link', $getPage );

        return array(
            'title'       =>$page['title'],
            'link'        =>$page['link'],
            'category'    =>$page['category'],
            'subcategory' =>$page['subcategory'],
            'content'     =>$page['content'],
            'datetime'    =>$page['datetime'],
            'author'      =>$page['author']
        );
    }









}