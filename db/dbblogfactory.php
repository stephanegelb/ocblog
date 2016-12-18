<?php
define('USEPDOPOST', 'usePDO');
define('USEPDOCOOKIE', 'usePDO');

function isUsePDO() {
    $isUsePDO = true;
    if(isset($_COOKIE[USEPDOCOOKIE])) {
        $isUsePDO = $_COOKIE[USEPDOCOOKIE] === 'true' ? true : false;
    }
    return $isUsePDO;    
}

require_once __DIR__.'\dbblogmysqli.php';
require_once __DIR__.'\dbblogpdo.php';

function getdbblog() {
    $usePDO = isUsePDO();
    return $usePDO === true ? new blogpdo(new DbPDO) : new blogmysqli(new DbMysqli);
}    
