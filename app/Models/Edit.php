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




}