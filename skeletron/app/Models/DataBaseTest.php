<?php
/**
 * Created by PhpStorm.
 * User: Comp-2
 * Date: 13.03.14
 * Time: 11:39
 */

namespace Models;


class DataBaseTest extends \Core\Model
{

    const TBL = 'docs';

    /**
     * @param string $className
     * @return mixed
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /** USE **/
    public function getAll()
    {
        /*$result = $this->db->getAll(self::TBL);
        if(!empty($result)) return $result;
        else return false;*/
        return 'ok';
    }



} 