<?php

/**
 * Файл был сгенерирован с помощю Gen Qm Framework
 *
 * Необходимо провести реорганизацию кода даной модели
 */


class Base extends Model
{
    public function getAll()
    {
        $sql = "SELECT * FROM pages";
        return $this->db->query($sql)->all();
    }


}