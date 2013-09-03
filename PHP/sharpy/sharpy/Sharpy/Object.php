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

  class Object {
  	
  	/**
  	 * Store various things in this variable
  	 * @var mixed
  	 */
  	private $_xstore = array();
  	
  	/**
  	 * Set a new variable
  	 */
  	public function __set($x, $v){
  		$this->_xstore[$x] = $v;
  	}
  	
  	/**
     * Get a variable
     */
  	public function __get($x){
  		if(isset($this->_xstore[$x]))
  		  return $this->_xstore[$x];
  		return false;
  	}
  	
  	/**
     * Check if a variable exists
     */
  	public function __isset($x){
  		return isset($this->_xstore[$x]);
  	}
  	
  	/**
     * Remove a variable, if exists
     */
  	public function __unset($x){
  		if(isset($this->_xstore[$x]))
  		  unset($this->_xstore[$x]);
  		return false;
  	}
  	
  	/**
  	 * Call a class method if exists
  	 * Otherwise set the class property to the value of $args
  	 * If $args is empty, return the class property named $method
  	 */
  	public function __call($method, $args){
  		if(method_exists($this, $method)){
  		  call_user_func_array(array($this, get_called_class().'::'.$method), $args);
  		} elseif(isset($args[0]) && !isset($args[1])){
  		  $this->$method = $args[0];
  		  return $this;
  		} elseif(!empty($args)) {
  		  $this->$method = $args;
  		  return $this;
  		} else {
  			return $this->$method;
  		}
  	}
  	
  	protected function _dump(){
  		var_dump($this);
  	}
  	
  	protected function xstore($key){
  		return $this->_xstore[$key];
  	}
  	
  }