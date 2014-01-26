<?php

/**
 * Файл был сгенерирован с помощю Geany Qm Framework
 *
 * вам необходимо провести реорганизацию кода
 */

class Users extends Model
{
    public function searchUser($email, $password)
    {
        $sql = 'SELECT id, login, email, role, name FROM users WHERE email=:email AND password=:password';
        $searchUser = $this->db->query($sql, array("email"=>$email,"password"=>$password))->row();

        if($searchUser)
            return $searchUser;
        else
            return false;
    }
}