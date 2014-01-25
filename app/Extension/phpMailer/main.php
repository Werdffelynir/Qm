<?php

require './class.phpmailer.php';

function init($objName)
{
    $objName = new PHPMailer();
    return $objName;
}
