<?php

class Users extends Model
{
    public function all()
    {
        $sql = "SELECT * FROM pages";
        return $this->db->query($sql)->all();
    }
}