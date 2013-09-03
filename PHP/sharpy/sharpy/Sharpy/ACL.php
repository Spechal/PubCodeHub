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
  
  class ACL extends Object {
  	
  	protected static $_permissions = array();
  	protected static $_super = FALSE;
  	
  	public function __construct($super = FALSE){
  		if($super)
  		  self::$_super = TRUE;
  	}
  	
  	public function allow($permission){
  		if(!in_array($permission, self::$_permissions))
  		  self::$_permissions[] = $permission;
  	}
  	
  	public function deny($permission){
  		if(($key = array_search($permission, self::$_permissions)) !== FALSE)
        unset(self::$_permissions[$key]);
  	}
  	
  	public function isAllowed($permission){
  		if(self::$_super || in_array($permission, self::$_permissions))
  		  return true;
  		return false;
  	}
  	
  	public function isSuper(){
  		return self::$_super;
  	}
  	
  	public function setSuper($bool = TRUE){
  		self::$_super = $bool;
  	}
  	
  	public function __toString(){
  		return print_r(self::$_permissions, true);
  	}
  	
  }