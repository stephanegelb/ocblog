<?php

require_once __DIR__.'\dbblogmysqli.php';
require_once __DIR__.'\dbblogpdo.php';

function getdbblog($usePDO = true) {
    return $usePDO === true ? new blogpdo(new DbPDO) : new blogmysqli(new DbMysqli);
}