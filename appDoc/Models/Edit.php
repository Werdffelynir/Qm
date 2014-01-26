<?php

/**
 * Файл был сгенерирован с помощю Geany Qm Framework
 *
 * вам необходимо провести реорганизацию кода
 */

class Edit extends Model
{

    /**
     * Get all pages to realise as main-menu
     * @return array
     */
    public function getMenu()
    {
        return  $this->getAll("pages", array("id", "title"), "type='Top Menu'");
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
     * Save new page
     * @param array $data
     * @return $this
     */
    public function saveNewPage(array $data)
    {
        $saveResult = $this->db->query("INSERT INTO pages (title,link,category,content,datetime,author) VALUES (:title,:link,:category,:content,:datetime,:author)", array(
                'title'     => $data['title'],
                'link'      => $data['link'],
                'category'  => $data['category'],
                'content'   => $data['content'],
                'datetime'  => $data['datetime'],
                'author'    => $data['author']
            )
        );

        return $saveResult;
    }


    /**
     * @param array $data
     * @return $this
     */
    public function updatePage(array $data)
    {
        $sql = "UPDATE pages
                SET title=:title,
                    type=:type,
                    link=:link,
                    category=:category,
                    subcategory=:subcategory,
                    content=:content,
                    datetime=:datetime
                WHERE id=:id;";

        $updateResult = $this->db->query($sql, array(
                'title'     => $data['title'],
                'type'      => $data['type'],
                'link'      => $data['link'],
                'category'  => $data['category'],
                'subcategory'=>$data['subcategory'],
                'content'   => $data['content'],
                'datetime'  => $data['datetime'],
                'id'        => $data['id']
            )
        );

        return $updateResult;
    }



    /**
     * Method delete page
     * @param $id
     * @return $this
     */
    public function deletePage($id)
    {
        $deleteResult = $this->db->query("DELETE FROM pages WHERE id=:id", array("id"=>$id));
        return $deleteResult;
    }

}