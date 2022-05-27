<?php
require "MySQL.php";
$index = "http://localhost:8080/index.php";
$sql = new MySQL($index);


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'PUT':
    $data = getData();
    $sql->put($data);
    echo json_encode($data);
    break;

  case 'POST':
    $ordercol = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $search = $_POST['search']['value'];
    $start = $_POST['start'];
    $length = $_POST['length'];

    header('Content-Type: application/hal+json;charset=UTF-8');

    
    $table=$sql->get($start,$length,$search,$ordercol, $order);
    $table['recordsTotal'] = $sql->count();
    
    echo json_encode($table, JSON_UNESCAPED_SLASHES);
    break;

  case 'GET':
    $table = array();

    header('Content-Type: application/hal+json;charset=UTF-8');
    $table=$sql->getID(10001);
    $table['recordsTotal'] = $sql->count();

    echo json_encode($table, JSON_UNESCAPED_SLASHES);
    break;

  case 'DELETE':
    header("HTTP/1.1 200 OK");
    $id = $_GET['id'];
    $sql->delete($id);
    echo json_encode(true);
    break;
}

function getData(){
  $data = file_get_contents('php://input');
  return json_decode($data);
}

?>
