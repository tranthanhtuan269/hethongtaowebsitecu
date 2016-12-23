<?php

if(!defined('CMS_ADMIN')) {
    die("Illegal File Access");
}

$id = intval($_GET['id']);
delete_map($id);
header("Location: modules.php?f=products&do=map");

?>