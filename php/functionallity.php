<?php

require_once('../Database/Database.php');

$db = new Database();
$orders= $db->select('orders');



?>