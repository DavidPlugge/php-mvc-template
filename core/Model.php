<?php

class Model
{
    protected $_db, $_table, $_modelName, $_softDelete = false, $_columnNames = [];
    public $id;

    public function __construct($table)
    {
        $this->_db = DB::getInstance();
        $this->_table = $table;
        $this->_setTableColumns();
        $this->_modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_table)));
    }

    protected function _setTableColumns() {
        $columns = $this->get_columns();
        foreach ($columns as $column) {
            $columnName = $column->Field;
            $this->_columnNames[] = $columnName;
            $this->{$columnName} = null;
        }
    }

    public function get_columns() {
        return $this->_db->getColumns($this->_table);
    }

    public function find($params = []) {
        $results = [];
        $resultsQuery = $this->_db->select($this->_table, '*', $params);
        foreach ($resultsQuery as $result) {
            $obj = new $this->_modelName($this->_table);
            $obj->populateObjData($result);
            $results[] = $obj;
        }
        return $results;
    }

    public function findFirst($params = []) {
        $resultQuery = $this->_db->selectFirst($this->_table, '*', $params);
        $result = new $this->_modelName($this->_table);
        $result->populateObjData($resultQuery);
        return $result;
    }

    public function findById($id) {
        return $this->findFirst(['conditions'=>['id'=>$id]]);
    }

    public function save() {
        $fields = [];
        foreach ($this->_columnNames as $column) {
            $fields[$column] = $this->$column;
        }
        if (property_exists($this, 'id') && $this->id != '') {
            return $this->updateById($this->id, $fields);
        } else {
            return $this->insert($fields);
        }
    }

    public function insert($fields) {
        if (empty($fields)) return false;
        return $this->_db->insert($this->_table, $fields);
    }

    public function update($fields, $conditions) {
        if (empty($fields)) return false;
        return $this->_db->update($this->_table, $fields, $conditions);
    }

    public function updateById($id, $fields) {
        if (empty($fields)) return false;
        return $this->_db->update($this->_table, $fields, ['id'=>$id]);
    }

    public function delete($conditions = []) {
        if (empty($conditions)) return $this->deleteById();
        if ($this->_softDelete) {
            return $this->update('*', $conditions);
        } else {
            return $this->_db->delete($this->_table, $conditions);
        }
    }

    public function deleteById($id = '') {
        if ($id == '' && $this->id == '') return false;
        $id = ($id == '')? $this->id : $id;
        if ($this->_softDelete) {
            return $this->update('*', ['id'=>$id]);
        } else {
            return $this->_db->delete($this->_table, ['id'=>$id]);
        }
    }

    public function data() {
        $data = new stdClass();
        foreach ($this->_columnNames as $column) {
            $data->$column = $this->$column;
        }
        return $data;
    }

    public function assign($params = []) {
        if (!empty($params)) {
            foreach ($params as $key=>$val) {
                if (in_array($key, $this->_columnNames)) {
                    $this->$key = sanitize($val);
                }
            }
            return true;
        }
        return false;
    }

    protected function populateObjData($result) {
        foreach ($result as $key => $val) {
            $this->$key = $val;
        }
    }
}