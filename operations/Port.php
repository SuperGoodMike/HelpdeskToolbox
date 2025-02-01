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

    function getOutput($hostname, $protocol = 'tcp') {
        $portArray = [];
        foreach ($this->ports as $port) {
            if ($protocol == 'udp') {
                $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
                $result = @socket_connect($sock, $hostname, $port);
                if ($result) {
                    $status = '<span style="color:green; font-weight:bold;">open</span>';
                } else {
                    $status = '<span style="color:red; font-weight:bold;">closed</span>';
                }
                socket_close($sock);
            } else {
                $fp = @fsockopen($hostname, $port, $errno, $errstr, 5);
                if ($fp) {
                    $status = '<span style="color:green; font-weight:bold;">open</span>';
                    fclose($fp);
                } else {
                    $status = '<span style="color:red; font-weight:bold;">closed</span>';
                }
            }
            $portArray[] = [
                "port" => $port,
                "status" => $status,
                "protocol" => strtoupper($protocol)
            ];
        }
        return json_encode($portArray);
    }
}
?>
