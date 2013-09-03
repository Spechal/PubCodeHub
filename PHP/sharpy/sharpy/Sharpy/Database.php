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

  require_once('Object.php');

  abstract class Database extends Object {
  	
  	protected static $_queryCount = 0;
    protected static $_queries = array();
    protected $_debug = FALSE;
  	
  	abstract protected function __construct();
  	abstract protected function _connect();
  	abstract public function query($sql, array $bind = array());
  	abstract public function row($sql, array $bind = array());
  	abstract public function rows($sql, array $bind = array());
  	abstract protected function _prepare($sql, array $bind = array());
  	abstract protected function _escape($string);
  	
    /**
     * Turns on debugging
     */
    public function debug(){
      $this->_debug = TRUE;
      return $this;
    }
    
    /**
     * Get the number of queries issued
     */
    public static function queryCount(){
      return self::$_queryCount;
    }
  	
    /**
     * Generates Universal Unique Identifier
     * 
     * @return string
     */
    public function UUID(){
      return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }
    
    /**
     * Validates a UUID
     * 
     * @param string $uuid
     */
    public function validUUID($uuid){
      return preg_match("#^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$#", $uuid);
    }
  	
  }