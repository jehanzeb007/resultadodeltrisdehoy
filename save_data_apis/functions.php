<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);

$hostname='localhost';
$dbname = 'resultadodeltrisdehoy';
$username ='dbAdmin';
$password ='sfs@$5q4q0i5mngfaQ#@fsAG';


class Operations
{

  protected $db;


  function __construct()
  {
    global $db;
    global $hostname;
    global $username;
    global $dbname;
    global $password;

    try {
      $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    } catch (PDOException $pe) {
      die("Could not connect to the database $dbname :" . $pe->getMessage());
    }
  }
  
    public function insert($table, $data)
  {

    global $db;

    $fields = array_keys($data);


    $sql = "INSERT IGNORE INTO " . $table . "(" . implode(',', $fields) . ")";


    $sets = array();

    foreach ($data as $column => $value) {
      $sets[] =  ":" . $column;
    }


    $sql .= "values(" . implode(', ', $sets) . ")";

    $stmt = $db->prepare($sql);


    $final_bind = array();



    foreach ($data as $column => $value) {

      $final_bind[] =  $value;
    }

    $stmt->execute($final_bind);

    $id = $db->lastInsertId();
    return $id;
  }
  
  
    public function getTable($tableName)
  {
    global $db;
    $sel  =  $db->query("select * from " . $tableName);
    return $sel->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getTopRowByDate($tableName, $dateColumn)
  {
    global $db;
    $sel  =  $db->query("SELECT * FROM " . $tableName . " ORDER BY ". $dateColumn . " DESC LIMIT 1");
    return $sel->fetch(PDO::FETCH_ASSOC);
  }

  public function getSingleRow($table, $where)
  {
    global $db;
    $sel  =  $db->query("select * from " . $table . " where " . $where);
    return $sel->fetch(PDO::FETCH_ASSOC);
  }

  public function getSingleColumn($table, $column, $query)
  {
    global $db;
    $sel  =  $db->query("select " . $column . " from " . $table . " where " . $column . "='" . $query . "'");
    return $sel->fetch(PDO::FETCH_ASSOC);
  }
  
    public function clearTable($table)
  {
    
    global $db;
    $query = "DELETE  FROM " . $table;
    $sel  =  $db->query($query);
    $row = $sel->fetchColumn();

  }
  
   public function getCategoryId($column_name , $result_type)
  {
    
    global $db;
    $query = "Select id From categories WHERE " . $column_name . "='" . $result_type . "'";
    $sel  =  $db->query($query);
    $row = $sel->fetchColumn();
    
    // if($row == false){
    //     $query = "Insert INTO categories(resultadostris_map) VALUES ('". $result_type . "')";
    //     $sel  =  $db->query($query);
    //     $row = $sel->fetchColumn();
    // }
    
    return $row;

  }
  
  
  
  
  
  
  
}
