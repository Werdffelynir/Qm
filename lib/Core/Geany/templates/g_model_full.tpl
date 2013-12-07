<?php

/**
 * Файл был сгенерирован с помощю Geany Qm Framework
 *
 * вам необходимо провести реорганизацию кода
 */

class [[MODELNAME]] extends Model{
{
    public function all()
    {
        $sql = "SELECT * FROM pages";
        return $this->db->query($sql)->all();
    }
}