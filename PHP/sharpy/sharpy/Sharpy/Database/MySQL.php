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

  require_once(SHARPY_PATH.'/Sharpy/Database.php');
  
  /**
   *  Usage:
   *  $db = MySQL::getInstance();
   *  print_r( $db->rows( "SELECT `something` FROM `table` WHERE `something` = ? ) , array( 'else' ) );
   *  print_r( $db->rows( "SELECT `something` FROM `table` WHERE `something` = ? AND `else` = ? ) , array( 'else', 'something' ) );
   *  print_r( $db->rows( "SELECT `something` FROM `table` WHERE `id` = ? ) , array( 42 ) );
   */
  
  class MySQL extends Database {
    
    protected $LOCATION = 'localhost';
    protected $PORT = 3306;
    protected $USER = '';
    protected $PASSWORD = '';
    protected $DEFAULT_DATABASE = '';
    protected $TABLE_PREFIX = '';
    
    protected $_link = NULL;
    protected static $_instance = NULL;
    
    
    /**
     * Creates a database connection and selects the default database
     */
    protected function __construct($params = NULL){
    if($params){
        if(!empty($params['host'])) $this->LOCATION = $params['host'];
        if(!empty($params['port'])) $this->PORT = $params['port'];
        if(!empty($params['user'])) $this->USER = $params['user'];
        if(!empty($params['password'])) $this->PASSWORD = $params['password'];
        if(!empty($params['dbname'])) $this->DEFAULT_DATABASE = $params['dbname'];
      }
      $this->_link = $this->_connect();
      $this->changeDatabase($this->_link, $this->DEFAULT_DATABASE);
    }
    
    /**
     * Close the MySQL connection
     * 
     * @return void
     */
    public function __destruct(){
    	mysql_close($this->_link);
    	$this->_link = NULL;
    	if($this->_debug){
    		echo '<pre>'.print_r(self::$_queries, true).'</pre>';
    	}
    }
    
    /**
     * Create a MySQL database connection
     */
    protected function _connect(){
      return mysql_connect($this->LOCATION.':'.$this->PORT, $this->USER, $this->PASSWORD);
    }
    
    /**
     * Selects a MySQL database
     */
    public function changeDatabase($link = NULL, $db = NULL){
      if(!$db)
        $db = $this->DEFAULT_DATABASE;
      if(!$link)
        $link = $this->_link;
      return mysql_select_db($db, $link);       
    }
    
    /**
     * Singleton accessor to the database instance
     * -- change this to use a md5 on the $params to allow for multiple single instances
     */
    public static function getInstance($params = NULL){
      if(self::$_instance == NULL)
        self::$_instance = new MySQL($params);
      return self::$_instance;
    }
    
    /** table prefixes **/
    public function tablePrefix($newPrefix = NULL){
      if($newPrefix)
        $this->TABLE_PREFIX = $newPrefix;
      return $this->TABLE_PREFIX;
    }
    
    /**
     * Executes a MySQL query
     */
    public function query($sql, array $bind = array()){
      $prepared = $this->_prepare($sql, $bind);
      self::$_queryCount++;
      self::$_queries[] = $prepared;
      // you must assign the query to a variable to get a resource
      // otherwise it returns boolean
      $q = mysql_query($prepared, $this->_link) or die(mysql_error() .' ON '.$prepared);
      return $q;
    }
    
    public function lastId(){
    	return $this->result("SELECT LAST_INSERT_ID()");
    }
    
    public function create($table, $bind = array()){
      $sql = "INSERT INTO";
      $sql .= " `$table` SET";
      foreach($bind as $column => $value):
        $sql .= " `$column` = ";
        if($value === NULL)
          $sql .= "NULL";
        elseif(is_string($value) && !is_numeric($value) || empty($value))
          $sql .= "'".$this->_escape($value)."'";
        elseif((is_string($value) && is_numeric($value)) || is_numeric($value))
          $sql .= $value;
        else
          throw new Exception("Invalid column value passed to ".__METHOD__.' -> '.$value);
        $sql .= ",";
      endforeach;
      if($sql[strlen($sql)-1] == ',')
        $sql = substr($sql, 0, -1);
      if($this->query($sql))
        return true;
    }
    
    public function update($table, $bind = array(), $id = array()){
    	$theId = current($id);
    	$sql = "UPDATE";
    	$sql .= " `$table` SET";
    	foreach($bind as $column => $value):
    	  $sql .= " `$column` = ";
    	  if($value === NULL)
    	    $sql .= "NULL";
    	  elseif(is_string($value) && !is_numeric($value) || empty($value))
    	    $sql .= "'".$this->_escape($value)."'";
    	  elseif((is_string($value) && is_numeric($value)) || is_numeric($value))
    	    $sql .= $value;
    	  else
    	    throw new Exception("Invalid column value passed to ".__METHOD__.' -> '.$value);
    	  $sql .= ",";
    	endforeach;
    	if($sql[strlen($sql)-1] == ',')
    	  $sql = substr($sql, 0, -1);
      $sql .= " WHERE `".key($id)."` = '".current($id)."'";
      if($this->query($sql))
        return $theId;
    }
    
    public function replace($table, $bind = array()){
      $sql = "REPLACE INTO";
      $sql .= " `$table` SET";
      foreach($bind as $column => $value):
        $sql .= " `$column` = ";
        if($value === NULL)
          $sql .= "NULL";
        elseif(is_string($value) && !is_numeric($value) || empty($value))
          $sql .= "'".$this->_escape($value)."'";
        elseif((is_string($value) && is_numeric($value)) || is_numeric($value))
          $sql .= $value;
        else
          throw new Exception("Invalid column value passed to ".__METHOD__.' -> '.$value);
        $sql .= ",";
      endforeach;
      if($sql[strlen($sql)-1] == ',')
        $sql = substr($sql, 0, -1);
        
      if($this->query($sql))
        return key($id);
    }
    
    /**
     * Get a query result
     */
    public function result($sql, array $bind = array(), $row = 0){
      $resource = $this->query($sql, $bind);
      if($resource)
        return mysql_result($resource, $row);
      return 0;
    }
    
    /**
     * Get a MySQL row
     */
    public function row($sql, array $bind = array()){
      $resource = $this->query($sql, $bind);
      return mysql_fetch_assoc($resource);
    }
    
    /**
     * Get a MySQL result set
     */
    public function rows($sql, array $bind = array()){
      $resource = $this->query($sql, $bind);
      $results = array();
      while(($row = mysql_fetch_assoc($resource)) !== FALSE){ $results[] = $row; }
      return $results;
    }
    
    /**
     * Prepares a query
     */
    protected function _prepare($sql, array $bind = array()){
      // check if there is anything to bind
      $dataCount = count($bind);
      if(!$dataCount) return $sql;
      // make sure there are elements to bind on
      $numToReplace = substr_count($sql, '?');
      if(!$numToReplace) return $sql;
      // make sure the number of elements being bound equals the number of elements to bind
      if($dataCount != $numToReplace) 
        throw new Exception('Data count ('.$dataCount.') does not match bind count ('.$numToReplace.') on '.$sql);
      
      // bind the elements
      for($i = 0; $i < $numToReplace; $i++){
        if(!isset($bind[$i]) && $bind[$i] !== NULL)
          throw new Exception('Element '.$i.' to be bound is not set');
        if($bind[$i] === NULL)
          $sql = preg_replace('#\?#', "NULL", $sql, 1);
        elseif(is_string($bind[$i]) && !is_numeric($bind[$i]))
          $sql = preg_replace('#\?#', "'".$this->_escape($bind[$i])."'", $sql, 1);
        elseif((is_string($bind[$i]) && is_numeric($bind[$i])) || is_numeric($bind[$i]))
          $sql = preg_replace('#\?#', $bind[$i], $sql, 1);
        else
          throw new Exception('You may only prepare int and string types, not this -> '.$bind[$i]);
      }
        
      return $sql;
    }
    
    public function prepare($string, array $bind = array()){
    	return $this->_prepare($string, $bind);
    }
    
    protected function _escape($string){
    	$replace = array(
                    "\x00"  => '\x00',
                    "\n"    => '\n',
                    "\r"    => '\r',
                    '\\'    => '\\\\',
                    "'"     => "\'",
                    '"'     => '\"',
                    "\x1a"  => '\x1a',
                    '?'     => '&#63;'
                  );
      return strtr($string, $replace);
    }
    
    /**
     * Begins a transaction
     */
    public function startTransaction($autoCommit = 0){
      $this->query("SET autocommit = ".(int)$autoCommit);
      $this->query("BEGIN");
    }

    /**
     * Commits a transaction
     */
    public function commit(){
      $this->query("COMMIT");
    }

    /**
     * Rolls back a transaction
     */
    public function rollback(){
      $this->query("ROLLBACK");
    }
    
    /**
     * Return rows found from SQL_CALC_FOUND_ROWS
     */
    public function foundRows(){
    	return $this->result("SELECT FOUND_ROWS()");
    }
    
    /**
     * Prepares MySQL for unicode characters
     */
    public function unicode(){
      $this->query("SET CHARACTER SET utf8");
      $this->query("SET NAMES utf8");
    }
    
  }