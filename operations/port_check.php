<?php

include_once('Port.php'); // Adjust the path if necessary

$hostname = $_POST['hostname'];
$ports = $_POST['ports'];
$protocol = $_POST['protocol'];

$portChecker = new Port($ports);
$output = $portChecker->getOutput($hostname, $protocol);

echo $output;
?>
