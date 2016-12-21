<?php
define('USEPDOPOST', 'usePDO');
define('USEPDOCOOKIE', 'usePDO');

class dbblogfactory {
    const USEPDOPOST = 'usePDO';
    const USEPDOCOOKIE = 'usePDO';
    
    public static function isUsePDO() {
        $isUsePDO = true;
        if(isset($_COOKIE[USEPDOCOOKIE])) {
            $isUsePDO = $_COOKIE[USEPDOCOOKIE] === 'true' ? true : false;
        } else {
            self::setUseDbCookie();
        }
        return $isUsePDO;    
    }
    
    public static function setUseDbCookie($usePDO = true) {
        setcookie(USEPDOCOOKIE, $usePDO ? 'true' : 'false', 2147483647, '/');
    }
    
    public static function getdbblog() {
        $usePDO = self::isUsePDO();
        return $usePDO === true ? new dbblogpdo(new DbPDO) : new dbblogmysqli(new DbMysqli);
    }    
}

