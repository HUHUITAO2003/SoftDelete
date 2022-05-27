<?php
require "Connessione.php";

class MySQL
{
  private $connessione;
  private $index;

  function __construct($index)
  {
    $sql = new Connessione();
    $this->connessione = $sql->connect();
    $this->index = $index;
  }

  function get($start, $length, $search, $ordercol, $order)
  {
    $ordercol++;
    $where = null;
    if(!is_null($search)){
      $where = "WHERE id LIKE '%{$search}%' OR birth_date LIKE '%{$search}%'OR first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%' OR gender LIKE '%{$search}%' OR hire_date LIKE '%{$search}%'";
    }
    /*
    $employees=array();
    $query = 'select * from employees limit '.$page*$size.','.$size;
    $result = $this->connessione->query($query)
    or die('Query fallita ' . mysqli_error($this->connessione) . '' . mysqli_errno($this->connessione));
    
    while($row = $result->fetch_assoc()){
      array_push($employees, array("id"=>$row['id'], "birthDate"=>$row['birth_date'], "firstName"=>$row['first_name'], "lastName"=>$row['last_name'], "gender"=>$row['gender'], "hireDate"=>$row['hire_date'], 
                  'links_' =>array( 'self'=>array('href'=>$this->index."?id=".$row['id']), 
                              'employee'=>array('href'=>$this->index."?id=".$row['id']))
                )); 
    }*/
    $table = array("data" => array());
    $query = "SELECT id, first_name, last_name, gender, hire_date, birth_date FROM employees {$where} ORDER BY {$ordercol} {$order} LIMIT {$start} , {$length}";

    $result = $this->connessione->query($query)
      or die('Query fallita ' . mysqli_error($this->connessione) . '' . mysqli_errno($this->connessione));
    while ($row = $result->fetch_assoc()) {
      array_push($table['data'], array(
        "id" => $row['id'],
        "birthDate" => $row['birth_date'],
        "firstName" => $row['first_name'],
        "lastName" => $row['last_name'],
        "gender" => $row['gender'],
        "hireDate" => $row['hire_date']
      ));
    }

    $query = "SELECT count(*) as conta FROM employees {$where}";
    $result = $this->connessione->query($query)
      or die('Query fallita ' . mysqli_error($this->connessione) . '' . mysqli_errno($this->connessione));
    while ($row = $result->fetch_assoc()) {
      $table["recordsFiltered"] = $row["conta"];
    }

    return $table;
  }


  function getID($id)
  {
    $query = 'select * from employees where id=' . $id;
    $result = $this->connessione->query($query)
      or die('Query fallita ' . mysqli_error($this->connessione) . '' . mysqli_errno($this->connessione));

    while ($row = $result->fetch_assoc()) {
      $employee = array(
        "id" => $row['id'], "birthDate" => $row['birth_date'], "firstName" => $row['first_name'], "lastName" => $row['last_name'], "gender" => $row['gender'], "hireDate" => $row['hire_date'],
        'links_' => array(
          'self' => array('href' => $this->index . "?id=" . $row['id']),
          'employee' => array('href' => $this->index . "?id=" . $row['id'])
        )
      );
    }
    return $employee;
  }

  function count()
  {
    $query = 'select count(id) as count from employees';
    $result = $this->connessione->query($query)
      or die('Query fallita ' . mysqli_error($this->connessione) . '' . mysqli_errno($this->connessione));

    while ($row = $result->fetch_assoc()) {
      return $row['count'];
    }
  }

  function post($data)
  {
    $query = "INSERT INTO employees(birth_date, first_name, gender, hire_date, last_name) VALUES ('{$data->birthDate}','{$data->firstName}','{$data->gender}','{$data->hireDate}','{$data->lastName}');";
    $result = $this->connessione->query($query)
      or die('Query fallita ' . mysqli_error($this->connessione) . '' . mysqli_errno($this->connessione));
  }

  function delete($id)
  {
    $query = "DELETE FROM employees WHERE id = '{$id}';";
    $result = $this->connessione->query($query)
      or die('Query fallita ' . mysqli_error($this->connessione) . '' . mysqli_errno($this->connessione));
  }

  function put($data)
  {
    $query = "UPDATE employees 
    SET birth_date = '{$data->birthDate}',
    first_name = '{$data->firstName}',
    gender = '{$data->gender}',
    hire_date = '{$data->hireDate}',
    last_name = '{$data->lastName}' 
    WHERE id = '{$data->id}';";
    $result = $this->connessione->query($query)
      or die('Query fallita ' . mysqli_error($this->connessione) . '' . mysqli_errno($this->connessione));
  }
}
