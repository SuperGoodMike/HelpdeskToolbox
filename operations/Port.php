<?php

include_once('./OperationInterface.php');


class Port implements OperationInterface{
  private $ports = [];
  
  public function processRequest() {
    header('Content-Type: application/json');
    error_reporting(0);
    ini_set('display_errors', 0);
    
    try {
      if (!isset($_POST['target'])) {
        throw new Exception('Missing target parameter');
      }
      
      $hostname = filter_var($_POST['target'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $ports = isset($_POST['ports']) ? $_POST['ports'] : '';
      
      $this->ports = $this->parsePortInput($ports);
      echo $this->getOutput($hostname);
      
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  function __construct($input = '') {
    $this->ports = $this->parsePortInput($input);
  }

  private function parsePortInput($input) {
    $ports = [];
    $parts = preg_split('/[\s,]+/', $input);

    foreach ($parts as $part) {
      if (strpos($part, '-') !== false) {
        list($start, $end) = explode('-', $part, 2);
        $start = (int)$start;
        $end = (int)$end;
        if ($this->isValidPort($start) && $this->isValidPort($end)) {
          $ports = array_merge($ports, range(min($start,$end), max($start,$end)));
        }
      } elseif (is_numeric($part)) {
        $port = (int)$part;
        if ($this->isValidPort($port)) {
          $ports[] = $port;
        }
      }
    }
    
    $ports = array_unique($ports);
    sort($ports);
    return empty($ports) ? [25, 53, 80, 443, 465, 587, 993] : $ports;
  }

  private function isValidPort($port) {
    return is_numeric($port) && $port >= 1 && $port <= 65535;
  }
  
  function getOutput($hostname) {
    $results = [];
    
    foreach ($this->ports as $port) {
      $start = microtime(true);
      $fp = @fsockopen($hostname, $port, $errno, $errstr, 2);
      $responseTime = microtime(true) - $start;
      
      if ($fp) {
        $results[] = [
          'port' => $port,
          'status' => 'open',
          'response_time' => $responseTime
        ];
        fclose($fp);
      } else {
        $results[] = [
          'port' => $port,
          'status' => 'closed',
          'response_time' => $responseTime
        ];
      }
    }

    header('Content-Type: application/json');
    return json_encode($results);
  }
    
}
  
  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $portScanner = new Port($_POST['ports'] ?? '');
    $portScanner->processRequest();
    exit;
}
