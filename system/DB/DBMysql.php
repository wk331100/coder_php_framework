<?php

namespace System\DB;
use PDO;
use PDOException;

class DBMysql{
    const CONNECT_MASTER    = 'master';
    const FROM              = 'FROM';
    const WHERE             = 'WHERE';
    const AND               = 'AND';
    const OR                = 'OR';
    const LIMIT             = 'LIMIT';
    const SELECT            = 'SELECT';
    const INSERT            = 'INSERT';
    const UPDATE            = 'UPDATE';
    const DELETE            = 'DELETE';
    const INTO              = 'INTO';
    const VALUES            = 'VALUES';
    const SET               = 'SET';
    const MARK              = '?';
    const ORDER_BY          = 'ORDER BY';
    const DESC              = 'desc';
    const GROUP_BY          = 'GROUP BY';

    private $_table;
    private $_where;
    private $_whereIn;
    private $_columns = '*';
    private $_connector;
    private $_take = 10;
    private $_skip = 0;
    private $_order = '';
    private $_group = '';
    private $_insert = [];
    private $_update = [];

    public static $connect = DBMysql::CONNECT_MASTER;

    public function __construct($connect){
        $this->conn($connect);
    }

    public static function connect($connect = DBMysql::CONNECT_MASTER){
        self::$connect = $connect;
    }

    public static function table($tableName){
        $DB = new self(self::$connect);
        $DB->_table = $tableName;
        return $DB;
    }

    public function conn($connect){
        $config = require(ROOT_PATH . '/config/database.php');
        $mysqlConfig = $config['connections']['mysql'][$connect];
        try {
            $this->_connector = new PDO("mysql:host={$mysqlConfig['host']};dbname={$mysqlConfig['database']}", $mysqlConfig['username'], $mysqlConfig['password']);
            $this->_connector->query('set names ' . $mysqlConfig['charset']);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function where($where , $operator = null, $value = null){
        if(is_array($where)){
            foreach ($where as $key => $v){
                $this->_where[] = [
                    'key' => $key,
                    'op'  => '=',
                    'value' => $v
                ];
            }

        } elseif (is_null($value)){
            $this->_where[] = [
                'key' => $where,
                'op'  => '=',
                'value' => $operator
            ];
        } else {
            $this->_where[] = [
                'key' => $where,
                'op'  => $operator,
                'value' => $value
            ];
        }
        return $this;
    }

    public function whereIn(string $where, array $inArray = []){
        $inStr = [];
        $inValue = [];
        for ($i = 0; $i < count($inArray); $i++){
            $key = ':in_' . $i;
            $inStr[] = $key;
            $inValue[] = [
                'key' => $key,
                'value' => $inArray[$i]
            ];
        }
        $inStr = implode(',', $inStr);

        $this->_whereIn = [
            'prepare' =>  $where . " in ({$inStr})",
            'values'  => $inValue
        ];
        return $this;
    }

    public function whereNotIn(string $where, array $inArray = []){
        $inStr = implode(',', $inArray);
        $this->_whereIn = '`' . $where . "` not in ({$inStr})";
        return $this;
    }

    public function take($take){
        $this->_take = $take;
        return $this;
    }

    public function skip($skip){
        $this->_skip = $skip;
        return $this;
    }

    public function select(array $columnArray = []){
        $this->_columns = implode(',', $columnArray);
        var_dump($this->_columns);
        return $this;
    }

    public function orderBy($column, $direction = self::DESC){
        if(is_array($column)){
            $itemArray = [];
            foreach ($column as $col => $direction){
                $itemArray[] = $col . ' ' . $direction;
            }
            $this->_order = implode(',', $itemArray);
        } else {
            $this->_order = $column . ' ' . $direction;
        }
        return $this;
    }

    public function groupBy($column){
        if(is_array($column)){
            $this->_group = implode(',', $column);
        } else {
            $this->_group = $column;
        }
        return $this;
    }

    public function orderByDesc($column){
        if(is_array($column)){
            $itemArray = [];
            foreach ($column as $col){
                $itemArray[] = $col . ' ' . self::DESC;
            }
            $this->_order = implode(',', $itemArray);
        } else {
            $this->_order = $column . ' ' . self::DESC;
        }
        return $this;
    }

    public function count($column = '*', $as = ''){
        if($this->_columns == '*'){
            $this->_columns = "count({$column})";
        } else {
            $this->_columns .= ",count({$column})";
        }
        if(!empty($as)){
            $this->_columns .= " as {$as}";
        }
        return $this->exec(self::SELECT);
    }

    public function get($columns = []){
        if(!empty($columns)){
            $this->_columns = implode(',', $columns);
        }
        return $this->exec(self::SELECT);
    }

    public function first($columns = []){
        if(!empty($columns)){
            $this->_columns = implode(',', $columns);
        }
        $this->_take = 1;
        $result = $this->exec(self::SELECT);
        return $result['0'];
    }

    public function create(array $insertData = []){
        $this->_insert = $insertData;
        return $this->exec(self::INSERT);
    }

    public function update(array $updateData = []){
        $this->_update = $updateData;
        return $this->exec(self::UPDATE);
    }

    public function delete(){
        return $this->exec(self::DELETE);
    }

    public function exec($operation){
        $sql = $this->prepareSql($operation);
        $values = $this->prepareValues($operation);
        $stmt  = $this->_connector->prepare($sql);
//        print_r($sql);
//        var_dump($values);

        if($operation == self::SELECT){
            if(!empty($values)){
                foreach ($values as $key => $value){
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }
            $stmt->bindValue(':skip', $this->_skip, PDO::PARAM_INT);
            $stmt->bindValue(':take', $this->_take, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } elseif($operation == self::INSERT){
            $stmt->execute($values);
            return $this->_connector->lastInsertId();
        } else {
            return $stmt->execute($values);
        }

    }

    public function prepareSql($operation){
        switch ($operation) {
            case self::SELECT : $sqlArr = $this->prepareSelectSql(); break;
            case self::INSERT : $sqlArr = $this->prepareInsertSql(); break;
            case self::UPDATE : $sqlArr = $this->prepareUpdateSql(); break;
            case self::DELETE : $sqlArr = $this->prepareDeleteSql(); break;
            default: $sqlArr = $this->prepareSelectSql();
        }
        return implode(' ', $sqlArr);
    }

    public function prepareValues($operation){
        switch ($operation) {
            case self::SELECT : $valueArray = $this->prepareSelectValues(); break;
            case self::INSERT : $valueArray = $this->prepareInsertValues(); break;
            case self::UPDATE : $valueArray = $this->prepareUpdateValues(); break;
            case self::DELETE : $valueArray = $this->prepareDeleteValues(); break;
            default: $valueArray = $this->prepareSelectValues();
        }
        return $valueArray;
    }


    public function prepareInsertSql(){
        $sqlArr = [self::INSERT, self::INTO, '`'. $this->_table . '`'];
        if(!empty($this->_insert)){
            $keys = array_keys($this->_insert);
            $sqlArr[] = '('.implode(',', $keys).')';
            $sqlArr[] = self::VALUES;
            $values = array_values($this->_insert);
            $sqlArr[] = '(' . str_repeat(self::MARK .",", count($values)-1) . self::MARK .')';
        }
        return $sqlArr;
    }

    public function prepareInsertValues(){
        $valueArray = [];
        if(!empty($this->_insert)){
            $values = array_values($this->_insert);
            $valueArray = array_merge($valueArray,  $values);
        }
        return $valueArray;
    }

    public function prepareUpdateSql(){
        $sqlArr = [self::UPDATE, '`'.$this->_table.'`', self::SET];
        $keys = array_keys($this->_update);
        $updateArray = [];
        if(!empty($keys)){
            foreach ($keys as $key){
                $updateArray[] = $key . "= :" . $key;
            }
        }
        $sqlArr[] = implode(',', $updateArray);
        $sqlArr = array_merge($sqlArr, self::bindWhereSql());
        return $sqlArr;
    }

    public function prepareUpdateValues(){
        $valueArray = [];
        if(!empty($this->_update)){
            foreach ($this->_update as $key => $value){
                $valueArray[':'.$key] = $value;
            }
        }
        $valueArray = array_merge($valueArray, self::bindWhereValue());
        return $valueArray;
    }

    public function prepareDeleteSql(){
        $sqlArr = [self::DELETE, self::FROM, $this->_table];
        return $sqlArr = array_merge($sqlArr, self::bindWhereSql());
    }

    public function prepareDeleteValues() {
        $valueArray = [];
        return array_merge($valueArray, self::bindWhereValue());
    }

    public function prepareSelectSql(){
        $sqlArr = [self::SELECT, $this->_columns, self::FROM, '`'.$this->_table.'`'];
        $sqlArr = array_merge($sqlArr, self::bindWhereSql());

        if(!empty($this->_order)){
            $sqlArr[] = self::ORDER_BY;
            $sqlArr[] = $this->_order;
        }

        if(!empty($this->_group)){
            $sqlArr[] = self::GROUP_BY;
            $sqlArr[] = $this->_group;
        }

        $sqlArr[] = self::LIMIT;
        $sqlArr[] =  ":skip, :take";
        return $sqlArr;
    }

    public function prepareSelectValues(){
        return self::bindWhereValue();
    }

    public function bindWhereSql(){
        if(empty($this->_where) && empty($this->_whereIn)){
            return [];
        }
        $sqlArr[] = self::WHERE;
        if(!empty($this->_where) && empty($this->_whereIn)){
            foreach ($this->_where as $index => $item){
                if($index != 0){
                    $sqlArr[] = self::AND;
                }
                $sqlArr[] = '`' . $item['key'] . '`';
                $sqlArr[] = $item['op'];
                $sqlArr[] = ':' .$item['key'] ;
            }
        } elseif (!empty($this->_whereIn) && empty($this->_where)){
            $sqlArr[] = $this->_whereIn['prepare'];
        } elseif (!empty($this->_whereIn) && !empty($this->_where)){
            foreach ($this->_where as $index => $item){
                if($index != 0){
                    $sqlArr[] = self::AND;
                }
                $sqlArr[] = '`' . $item['key'] . '`';
                $sqlArr[] = $item['op'];
                $sqlArr[] = ':' .$item['key'] ;
            }
            $sqlArr[] = self::AND;
            $sqlArr[] = $this->_whereIn['prepare'];
        }
        return $sqlArr;
    }

    public function bindWhereValue(){
        $valueArray = [];
        if(!empty($this->_where) && empty($this->_whereIn)){
            foreach ($this->_where as $index => $item){
                $valueArray[':' .$item['key']] = $item['value'];
            }
        } elseif (!empty($this->_whereIn) && empty($this->_where)){
            foreach ($this->_whereIn['values'] as $value){
                $valueArray[$value['key']] = $value['value'];
            }
        } elseif (!empty($this->_whereIn) && !empty($this->_where)){
            foreach ($this->_where as $index => $item){
                $valueArray[':' .$item['key']] = $item['value'];
            }
            foreach ($this->_whereIn['values'] as $value){
                $valueArray[$value['key']] = $value['value'];
            }
        }
        return $valueArray;
    }



}
