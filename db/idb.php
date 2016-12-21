<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface idb {
    //function prepare($sql, $options = null);
    function fetchAll($sql, $array, $objectName = null);
    function fetchColumn($sql);
    function exec($sql, $array = null);
}

interface idbstatement {
    //function execute($array);
    function fetch();
    function closeCursor();
}
