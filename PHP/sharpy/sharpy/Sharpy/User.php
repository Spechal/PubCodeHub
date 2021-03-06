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

  require_once(SHARPY_PATH.'/models/Blog.php');  
  require_once(SHARPY_PATH.'/Sharpy/Database/DataMapper.php');

  class User extends DataMapper {
  	
  	public function __construct(Database $db, $id = NULL){
  		parent::__construct($db);
  		$this->table('users');
  		$this->columns(array('id', 'login', 'peaches'));
  		
  		if($id){
  			$this->id($id);
  		}
  		
  		$this->has_many = array('Login' => new Login($db, $this), 'Blog' => new Blog($db, $this));
  		
  	}
  	
  	public function authenticate($user, $pass){
  		$res = $this->find(array('login' => $user, 'peaches' => sha1($pass)));
  		if(!empty($res))
  		  return $res[0];
  		return false;
  	}
  	
  	public function dump(){
  		echo '<pre>'; $this->_dump();
  	}
  	
  }
  
  class Login extends DataMapper {
    public function __construct(Database $db, $user){
      parent::__construct($db);
      $this->table('logins');
      $this->columns(array('id', 'users_id', 'unixtime'));
      
      $this->has_one = array($user);
    }
  }