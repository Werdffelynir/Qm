<?php

namespace Models;


class Pages extends \Core\Model
{

    const TBL = 'pages';

    /**
     * @param string $className
     * @return mixed
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /** USE **/
    public function getRecords($id=null)
    {
        if($id==null)
            $result = $this->db->getAll(self::TBL);
        else
            $result = $this->db->getById(self::TBL, $id);

        if(!empty($result))
            return $result;
        else
            return false;
    }

    public function getUser($email)
    {
        $result = $this->db->getByAttr('users', 'email', $email);

        if(!empty($result))
            return $result;
        else
            return false;
    }


} 