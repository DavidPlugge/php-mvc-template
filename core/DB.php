<?php
class DB {
  private static $_instance = null;
  private $_pdo, $_query, $_error = false, $_results, $_count = 0, $_lastInsertID = '';

  public function __construct() {
    try {
      $this->_pdo = new PDO('mysql:host='.DBHOST.';dbname=user',DBUSER,DBPASSWORD);
      if (DEBUG) {
          $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      }
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  public static function getInstance() {
      if (!isset(self::$_instance)) {
          self::$_instance = new DB();
      }
      return self::$_instance;
  }

  // ***usage***
  // $db->query( * any valid sql statement * );
  public function query($sql, $params = []) {
    $this->_error = false;

    if ($this->_query = $this->_pdo->prepare($sql)) {
      if (count($params)) {
        $i = 1;
        foreach ($params as $param) {
          $this->_query->bindValue($i, $param);
          $i++;
        }
      }
      if ($this->_query->execute()) {
        $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
        $this->_count = $this->_query->rowCount();
        $this->_lastInsertID = $this->_pdo->lastInsertId();
      } else {
        $this->_error = true;
      }
    }
    return $this;
  }

  // ***usage***
  // $db->select( 'table' , 'wanted' , * conditions, order, limit * );
  //  
  //  ['conditions'=> [
  //                   'username'=>'chris',
  //                   'email'=>'chris-miller@test.com'
  //                  ],
  //  'order'=>'email ASC',   *** ASC | DESC ***
  //  'limit'=>5
  //  ]
  //
  public function select($table, $wanted = '*', $params = []) {
    $frame = "SELECT {$wanted} FROM {$table}";
    $sqlBuild = $this->buildSQLStatement($frame, $params);
    return $this->query($sqlBuild['statement'], $sqlBuild['bind'])->results();
  }

  public function selectFirst($table, $wanted = '*', $params = []) {
      $this->select($table, $wanted, $params);
      return $this->first();
  }

  // ***usage***
  // $db->insert('table', * values to insert *);
  //
  //  * values to insert *
  //  [
  //   'column_1'=>'nobody',
  //   'column_2'=>'somebody'
  //  ]
  public function insert($table, $fields = []) {
    $fieldString = '';
    $valueString = '';
    $values = [];
    foreach ($fields as $field => $value) {
      $fieldString .= '`'.$field.'`,';
      $valueString .= '?,';
      $values[] = $value;
    }
    $valueString = rtrim($valueString, ',');
    $fieldString = rtrim($fieldString, ',');
    $sql = "INSERT INTO {$table} ({$fieldString}) VALUES ({$valueString})";
    if ($this->query($sql, $values)) {
      return true;
    }
    return false;
  }

  // ***usage***
  // $db->update('table', * values to update *, * conditions*);
  //
  //  * values to update *
  //  [
  //   'username'=>'somebody',
  //   'brother'=>'nobody'
  //  ]
  //
  //  * conditions *
  //  [
  //   'username'=>'nobody',
  //   'brother'=>'somebody'
  //  ]
  public function update($table, $fields = [], $conditions = []) {
    $fieldString = '';
    $values = [];
    foreach ($fields as $field => $value) {
      $fieldString .= ' '.$field.' = ?,';
      $values[] = $value;
    }
    $fieldString = trim($fieldString);
    $fieldString = rtrim($fieldString, ',');
    $frame = "UPDATE {$table} SET {$fieldString}";
    $sqlBuild = $this->buildSQLStatement($frame, ['conditions'=>$conditions]);
    $bind = array_merge($values, $sqlBuild['bind']);
    if ($this->query($sqlBuild['statement'], $bind)) {
      return true;
    }
    return false;
  }

  // ***usage***
  // $db->delete('table', 'target (delete all if empty)', * conditions *) 
  //
  //  * conditions *
  //  [
  //   'username'=>'nobody',
  //   'brother'=>'somebody'
  //  ]
  public function delete($table, $conditions = []) {
    $frame = "DELETE FROM {$table}";
    $sqlBuild = $this->buildSQLStatement($frame, ['conditions'=>$conditions]);
    if (!$this->query($sqlBuild['statement'], $sqlBuild['bind'])->error()) {
      return true;
    }
    return false;
  }

  //creates condition string * WHERE x = y AND z = a ... *
  private function conditions($conditions = []) {
    $out = " WHERE";
    $arOut = [];
    foreach ($conditions as $key => $condition) {
      $out .= ' '.$key. ' = ? AND';
      $arOut[] = $condition;
    }
    $out = rtrim($out, ' AND');
    return ['statement'=>$out, 'bind'=>$arOut];
  }

  //creates limit string
  // * LIMIT 7 *
  private function limit($limitString) {
    return ' LIMIT '.$limitString;
  }

  // creates order string
  // * ORDER BY test ASC | DESC *
  private function order($orderString){
    return ' ORDER BY '.$orderString;
  }

  // creates sql-statement + bind-parameter
  // * frame + WHERE x = y ORDER BY test DESC LIMIT 3 * 
  private function buildSQLStatement($frame, $params) {
    $sql = $frame;
    $bind = [];
    if (!empty($params)) {
      if (array_key_exists('conditions', $params)) {
        $conditions = $this->conditions($params['conditions']);
        $sql .= $conditions['statement'];
        $bind = $conditions['bind'];
      }
      if (array_key_exists('order', $params)) $sql .= $this->order($params['order']);
      if (array_key_exists('limit', $params)) $sql .= $this->limit($params['limit']);
    }
    return ['statement'=>$sql, 'bind'=>$bind];
  }

  //returns table columns as object
  public function getColumns($table) {
    return $this->query("SHOW COLUMNS FROM {$table}")->results();
  }

  //returns last sql-query-result
  public function results() {
    return $this->_results;
  }

  //returns last sql-query-result-row-count
  public function count() {
    return $this->_count;
  }

  //returns first object from query
  public function first () {
    if (!empty($this->_results)) {
      return $this->_results[0];
    } return [];
  }

  //returns error boolean
  public function error() {
    return $this->_error;
  }

  //returns last insertID
  public function lastId() {
    return $this->_lastInsertID;
  }
}