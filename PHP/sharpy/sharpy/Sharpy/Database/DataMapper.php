<?php

  /**
    The MIT License

    Copyright (c) 2010 Travis Crowder

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
   */

  require_once(SHARPY_PATH.'/Sharpy/Object.php');

  class DataMapper extends Object {
    
    protected $_db = NULL;
    protected $_prefix = NULL;
    protected $_table = NULL;
    protected $_pKey = 'id';
    protected $_columns = array('id');
    protected $_order = NULL;
    protected $_id = NULL;
    
    
    public $has_one = array();
    public $has_many = array();
    
    public function __get($x){
      if(isset($this->_xstore[$x])){
        return $this->_xstore[$x];
      } elseif($x == '_xstore') {
      	return false;
      } else {
      	
      	if(isset($this->has_many[$x]))
      	  return $this->has_many[$x];
      	
      	if(isset($this->has_one[$x]))
          return $this->has_one[$x];
        
        return false;

      }
    }
    
    
    public function __construct(Database $db){
      $this->_db = $db;
    }
    
    public function prefix($new = NULL){
      if($new)
        $this->_prefix = $new;
      return $this->_prefix;
    }
    
    public function table($new = NULL){
      if($new)
        $this->_table = $new;
      return $this->_prefix.$this->_table;
    }
    
    public function key($new = NULL){
      if($new)
        $this->_pKey = $new;
      return $this->_pKey;
    }
    
    public function columns($columns = array()){
      if(!empty($columns) && is_array($columns))
        $this->_columns = $columns;
      return $this->_columns;
    }
    
    public function placeHolders(){
      $total = count($this->columns());
      $return = '';
      for($i = 0; $i < $total; $i++){
        $return .= '?';
        if($i+1 != $total)
          $return .= ', ';
      }
      return $return;
    }
    
    public function id($new = NULL){
    	if($new){
    	  $this->_id = abs($new);
    	  return $this;
    	}
    	return $this->_id;
    }
    
    protected function _finder(array $params = array(), $order = array(), $skip = 0, $limit = NULL){
      $sql = "SELECT ";
      $sql .= $this->_buildColumns();
      if(!empty($this->_id)){
      	// add id to columns list
      	if(!in_array('id', $params)){
      		$params['id'] = $this->_id;
      	}
      }
      $sql .= $this->_buildWhere($params);
      $sql .= $this->_buildOrder($order);
      if($skip || $limit){
        $sql .= $this->_buildLimit($skip, $limit);
      }
      return $this->_db->rows($sql, array_values($params));
    }
    
    protected function _buildColumns(){
      $sql = "`".$this->table()."`.`".implode('`, `'.$this->table().'`.`', $this->columns())."`";
      if(!empty($this->has_many)){
      	foreach($this->has_many as $key => $class){
      		foreach($class->columns() as $name){
      			$sql .= ", `".$class->table()."`.`$name` AS `".$class->table()."_$name`";
      		}
      	}
      }
      if(!empty($this->has_one)){
        foreach($this->has_one as $key => $class){
          foreach($class->columns() as $name){
            $sql .= ", `".$class->table()."`.`$name` AS `".$class->table()."_$name`";
          }
        }
      }
      $sql .= " FROM `".$this->table()."`";
      ### something funky is going on here
      if(!empty($this->has_many)){
      	foreach($this->has_many as $key => $class){
      		#echo "checking ".$class->table()." against "; print_r($this->columns());
      	  if(in_array($class->table()."_".$this->key(), $this->columns())){
      	  	#echo "FOUND";
            $sql .= " LEFT JOIN `".$class->table()."` ON `".$class->table()."`.`".$this->table()."_".$class->key()."` = `".$this->table()."`.`".$this->key()."`";
          } else {
          	#echo "NOT FOUND";
            $sql .= " LEFT JOIN `".$class->table()."` ON `".$class->table()."`.`".$this->table()."_".$class->key()."` = `".$this->table()."`.`".$this->key()."`";
          }
      	}
      }
      if(!empty($this->has_one)){
        foreach($this->has_one as $key => $class){
        	#echo "checking ".$class->table()." against "; print_r($this->columns());
        	if(in_array($class->table()."_".$this->key(), $this->columns())){
        		#echo "FOUND";
            $sql .= " INNER JOIN `".$class->table()."` ON `".$class->table()."`.`".$class->key()."` = `".$this->table()."`.`".$class->table()."_".$this->key()."`";
        	} else {
        		#echo " NOTFOUND";
        		$sql .= " INNER JOIN `".$class->table()."` ON `".$class->table()."`.`".$class->key()."` = `".$this->table()."`.`".$class->table()."_".$this->key()."`";
        	}
        }
      }
      ### ending here
      $sql .= " WHERE `".$this->table()."`.`".$this->key()."` IS NOT NULL";
      return $sql;
    }
    
    /**
     * Builds the WHERE clause in the SQL statement
     * @param mixed $params Accepts key-value pair or array(column, operator, value)
     */
    protected function _buildWhere($params = array()){
      $sql = '';
      if(!empty($this->has_many)){
        foreach($this->has_many as $key => $class){
        	$id = $class->id();
          if(!empty($id)){
            $sql .= " AND `".$class->table()."`.`id` = ".$class->id();
          }
        }
      }
      if(!empty($this->has_one)){
        foreach($this->has_one as $key => $class){
        	$id = $class->id();
        	if(!empty($id)){
        		$sql .= " AND `".$class->table()."`.`id` = ".$class->id();
        	}
        }
      }
      foreach($params as $key => $val){
        if(!is_array($val)){
          $sql .= " AND `".$this->table()."`.`$key` = ?";
        } else {
          if(empty($val['column']) || empty($val['operator']))
            trigger_error('Invalid parameter passed to DataMapper::_buildWhere', E_USER_ERROR);
          if($val['value'] !== NULL){
            $sql .= ' AND `'.$this->table().'`.`'.$val['column'].'` '.$val['operator'].' ?';
            $params[$key] = $val['value'];
          } else {
            $sql .= ' AND `'.$this->table().'`.`'.$val['column'].'` IS NOT NULL';
            unset($params[$key]);
          }
        }
      }
      return $this->_db->prepare($sql, array_values($params));
    }
    
    protected function _buildOrder($order = array()){
      $sql = '';
      if(!empty($order) || (is_array($this->_order) && ($order = $this->_order))){
        $sql .= " ORDER BY ";
        foreach($order as $key => $val)
          $sql .= "`".$this->table()."`.`$key` $val,";
        if($sql[strlen($sql)-1] == ',')
          $sql = substr($sql, 0, -1);
      }
      return $sql;
    }
    
    protected function _buildLimit($skip = 0, $limit = NULL){
      $sql = '';
      if($skip || $limit)
        $sql .= " LIMIT ";
      if($skip)
        $sql .= $skip.","; 
      if($limit)
        $sql .= $limit;
      return $sql;
    }
    
    public function find($params = array(), $order = array(), $skip = 0, $limit = NULL){
    	if(!is_array($params) && !empty($params))
    	  $params['id'] = $params;
      return $this->_finder($params, $order, $skip, $limit);
    }
    
    protected function _create($bind){
      if($this->_db->create($this->table(), $bind))
        return true;
      return false;
    }
    
    public function create($bind, $id = NULL){
      if(!$id) $id = $this->_db->UUID();
      $prepend = array($this->key() => $id);
      if(!empty($this->has_many)){
        foreach($this->has_many as $key => $class){
          $id = $class->id();
          if(!empty($id)){
            $prepend[$class->table()."_id"] = $class->id();
          }
        }
      }
      if(!empty($this->has_one)){
        foreach($this->has_one as $key => $class){
          $id = $class->id();
          if(!empty($id)){
            $prepend[$class->table()."_id"] = $class->id();
          }
        }
      }
      $bind = array_merge($prepend, $bind);
      if($this->_create($bind))
        return $id;
      return false;
    }
    
    protected function _update($bind, $id){
      return $this->_db->update($this->table(), $bind, array($this->key() => $id));
    }
    
    public function update($bind, $id){
    	$prepend = array();
      if(!empty($this->has_many)){
        foreach($this->has_many as $key => $class){
          $tmp = $class->id();
          if(!empty($tmp)){
            $prepend[$class->table()."_id"] = $class->id();
          }
        }
      }
      if(!empty($this->has_one)){
        foreach($this->has_one as $key => $class){
          $tmp = $class->id();
          if(!empty($tmp)){
            $prepend[$class->table()."_id"] = $class->id();
          }
        }
      }
      $bind = array_merge($prepend, $bind);
      return $this->_update($bind, $id);
    }
    
    protected function _destroy($key){
      return $this->_db->query("DELETE FROM `".$this->table()."` WHERE `".$this->table()."`.`".$this->key()."` = ? LIMIT 1", array($key));
    }
    
    public function destroy($id){
      return $this->_destroy($id);
    }
    
    public function debug(){
    	$this->_db->debug();
    	return $this;
    }
    
  }