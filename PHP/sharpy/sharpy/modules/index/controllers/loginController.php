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

  require_once(SHARPY_PATH.'/Sharpy/Controller.php');
  require_once(SHARPY_PATH.'/Sharpy/User.php');
  require_once(SHARPY_PATH.'/Sharpy/Session.php');

  class loginController extends Controller {
  	
  	public function indexAction(){
  		if(!empty($_POST['submit'])){
  			if(!empty($_POST['user']) && !empty($_POST['pass'])){
  				$User = new User($this->_db);
  				if(($user = $User->authenticate($_POST['user'], $_POST['pass']))){
  					$User->Login->create(array('users_id' => $user['id'], 'unixtime' => time()));
  					// set data and redirect
  					$Session = new Session;
  					$Session->data['user']['id'] = $user['id'];
  					header('Location: /');
  					exit;
  				} else {
  					// bad login
  				}
  			} else {
  				// empty fields
  			}
  		}
  		// login form
  	}
  	
  }