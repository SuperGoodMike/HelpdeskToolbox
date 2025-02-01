<?php

include_once('./OperationInterface.php');

class Port implements OperationInterface {
    private $ports = [25, 53, 80, 443, 465, 587, 993]; // Default ports

    function __construct($ports = "80") {
        if ($ports == "") {
            $this->ports = $this->ports;
        } else {
            $this->ports = $this->parsePorts($ports);
        }
    }

    private function parsePorts($ports) {
        $portArray = [];
        $ranges = explode(',', $ports);
        foreach ($ranges as $range) {
            if (strpos($range, '-') !== false) {
                list($start, $end) = explode('-', $range);
                $portArray = array_merge($portArray, range($start, $end));
            } else {
                $portArray[] = (int) $range;
            }
        }
        return $portArray;
    }

    function getOutput($hostname) {
        $portArray = "";
        foreach ($this->ports as $port) {
            $fp = @fsockopen($hostname, $port, $errno, $errstr, 5);
            if ($fp) {
                $result = '<span style="color:green; font-weight:bold;">open</span>';
                fclose($fp);
            } else {
                $result = '<span style="color:red; font-weight:bold;">closed</span>';
            }
            $portArray .= "\"$port\": \"Is $result\",\n";
        }
        $result = "[{\n";
        $result .= rtrim($portArray, ",\n");
        $result .= "\n}]";
        return $result;
    }
}
?>
