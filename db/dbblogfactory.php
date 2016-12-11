<?php

include_once __DIR__.'\db.php';
include_once __DIR__.'\dbblogmysqli.php';
include_once __DIR__.'\dbblogpdo.php';

function getdbblog() {
    $pdo = true;
    return $pdo ? new blogpdo(new DbPDO) : new blogmysqli(new DbMysqli);
}