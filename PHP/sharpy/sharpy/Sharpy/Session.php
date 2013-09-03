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

  /**
   * This class is currently only used for testing purposes.
   */

  require_once('Object.php');
  
  class Session extends Object {
  	
  	protected $_save_type = 'file';
  	
  	public $data = NULL;
  	
  	public function __construct(){
  		$this->_load();
  	}
  	
    public function __destruct(){
      $this->_save();
    }
  	
  	public function __toString(){
  		$this->_dump();
  	}
  	
  	protected function _load(){
  	  switch($this->_save_type){
        case 'db':
          break;
        case 'file':
        default:
          $this->data = $_SESSION;
      }
  	}
  	
  	protected function _save(){
  		switch($this->_save_type){
  			case 'db':
  				break;
  			case 'file':
  			default:
  				$_SESSION = $this->data;
  		}
  	}
  	
  }