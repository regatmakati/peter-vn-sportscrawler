<?php
//多后台相互隔离
if (empty($_SERVER['REQUEST_URI'])) return;
if (strstr($_SERVER['REQUEST_URI'], '/AdminCenterSports') !== false)
    return require_once __DIR__ . "/adminCenterSports.php";

if (strstr($_SERVER['REQUEST_URI'], '/AdminOther') !== false)
    return require_once __DIR__ . "/adminOther.php";
