<?php
require "MySQL.php";
$index = "http://localhost:8080/index.php";
$sql = new MySQL($index);


$method = $_SERVER['REQUEST_METHOD'];
$table = array("data" => array());
switch ($method) {

  case 'POST':
    //post per popolazione tabella
    $ordercol = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $search = $_POST['search']['value'];
    $start = $_POST['start'];
    $length = $_POST['length'];

    header('Content-Type: application/hal+json;charset=UTF-8');
    //post dati per post, put e delete
    if(isset($_POST['action'])){
    $data = array();
    $data['id'] = array_keys($_POST['data'])[0];
    $data['lastName'] = $_POST['data'][$data['id']]['lastName'];
    $data['firstName'] = $_POST['data'][$data['id']]['firstName'];
    $data['gender'] = $_POST['data'][$data['id']]['gender'];
    $data['hireDate'] = $_POST['data'][$data['id']]['hireDate'];
    $data['birthDate'] = $_POST['data'][$data['id']]['birthDate'];
    $data['removed_date'] = $_POST['data'][$data['id']]['users']['removed_date'];
    $data['action'] = $_POST['action'];
    }

    switch ($_POST['action']) {

      case 'create':
        $sql->post($data);
        break;

      case 'edit':
        if ($data['removed_date'] != "") {
          $sql->delete($data['id']);
        } else {
          $sql->put($data);
        };
        break;

      default:
            $table = $sql->get($table,$start,$length,$search,$ordercol, $order);
            $table['recordsTotal'] = $sql->count();
        ;
    }

    echo json_encode($table, JSON_UNESCAPED_SLASHES);
    break;

  case 'GET':
    $table = array();
    header('Content-Type: application/hal+json;charset=UTF-8');
    $table = $sql->get($table,0, 30, "", 0, "asc");
    echo json_encode($table, JSON_UNESCAPED_SLASHES);
    break;

}
