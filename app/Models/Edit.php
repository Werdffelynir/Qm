<?php

/**
 * Файл был сгенерирован с помощю Geany Qm Framework
 *
 * вам необходимо провести реорганизацию кода
 */

class Edit extends Model
{

    public function getMenu()
    {
        return  $this->getAll("pages", array("id","title"));
    }

    public function getPages()
    {
        return  $this->getAll("pages");
    }


    public function saveNewPage(array $data)
    {
        $saveResult = $this->db->query("INSERT INTO pages (title,category,content,datetime,author) VALUES (:title,:category,:content,:datetime,:author)", array(
                'title'     => $data['title'],
                'category'  => $data['category'],
                'content'   => $data['content'],
                'datetime'  => $data['datetime'],
                'author'    => $data['author']
            )
        );

        return $saveResult;

    }

}