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
  require_once('Template.php');
  require_once('Database/MySQL.php');

  class Controller extends Object {
  	
  	protected $_request = NULL;
  	protected $_template = NULL;
  	protected $_db = NULL;
  	
  	public function __construct(Request $request){
  		$this->_request = $request;
  		$this->_template = new Template;
  		$this->_template->_controller($request->controller())
  		                ->_module($request->module())
  		                ->_action($request->action());
		  $params = array('user' => 'sharpy', 'password' => 'sharpy', 'dbname' => 'sharpy', 'host' => 'localhost');
		  $this->_db = MySQL::getInstance($params);
  	}
  	
  	/**
  	 * Forward a request to another action
  	 * 
  	 * @param string $action Action to run
  	 */
  	public function forward($action = 'index', array $hud = array()){
  		$this->_request->action($action);
  		$this->_template->_action($action);
  		if(!empty($hud))
  		  $_SESSION['HUD'] = $hud;
  		// convert the action to the controller style
  		$action = $action . 'Action';
  		$this->$action();
  	}
  	
  	/** Not working ... infinite redirect loop **/
  	public function redirect($action, array $hud = array()){
  	  if(!is_array($action)){
        $this->_request->action($action);
        #$this->_template->_action($action);
      } else {
        $this->_request->module($action[0]);
        #$this->_template->_module($action[0]);
        if(!empty($action[1])){
          $this->_request->controller($action[1]);
          #$this->_template->_controller($action[1]);
        }
        if(!empty($action[2])){
          $this->_request->action($action[2]);
          #$this->_template->_action($action[2]);
        }
        $this->_template->noRender();
        $_SESSION['HUD'] = $hud;
        header('Location: /'.$this->_request->module().'/'.$this->_request->controller().'/'.$this->_request->action());
        #require_once('Dispatcher.php');
        #$Dispatcher = new Dispatcher($this->_request);
        #print_r($Dispatcher);exit;
        #$Dispatcher->dispatch();
      }
  	}
  	
  	public function __destruct(){
  		$this->_template->render();
  	}
  	
  }