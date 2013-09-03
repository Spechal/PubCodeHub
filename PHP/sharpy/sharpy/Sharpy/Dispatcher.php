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

  class Dispatcher extends Object {
  	
  	protected $_request = NULL;
  	public $queryString = NULL;
  	
  	public function __construct(Request $request){
  		$this->_request = $request;
  	}
  	
  	/**
  	 * Parse _SERVER[REQUEST_URI] based on forward slashes
  	 * i.e.
  	 * admin/users/view/id/123
  	 * module = admin
  	 * controller = users
  	 * action = view
  	 * queryVars = array(id => 123)
  	 * $_GET = $_GET[id] = 123
  	 * 
  	 * Creates the MVC and query string variables needed for processing
  	 */
  	private function _disector(){
	  	$this->queryVars = preg_replace("#([^/]+)?[/]?([^\?]*)#", "\\2", preg_replace('#.*\.php#', '', $_SERVER['REQUEST_URI']));
		  $this->queryString = array();
		  $this->module = 'index';
		  $this->controller = 'index';
		  $this->action = 'index';
		  if(!empty($this->queryVars)){		  	
		    $tmp = explode('/', $this->queryVars);
		    if(!empty($tmp[0])){
		      $this->module = $tmp[0];
		      if(!empty($tmp[1])){
		        $this->controller = $tmp[1];
		        if(!empty($tmp[2])){
		          $this->action = $tmp[2];
		          if(!empty($tmp[3])){
		            array_shift($tmp);
		            array_shift($tmp);
		            array_shift($tmp);
		            $count = count($tmp);
		            for($i = 0; $i < $count; $i++){
		              if(!empty($tmp[$i]) && !empty($tmp[$i+1])){
		                $this->queryString[$tmp[$i]] = $_GET[$tmp[$i]] = $tmp[$i+1];
		              }
		              $i++; // increments by 2 since for increments by 1
		            }
		          }
		        }
		      }
		    }
		  }
		  $this->_request->module($this->module)->controller($this->controller)->action($this->action);
  	}
  	
  	/**
  	 * Calls the query string parser and issues controller actions
  	 */
  	public function dispatch(){
  		$this->_disector();
	  	require_once(MODULES_PATH.'/'.$this->module.'/controllers/'.$this->controller.'Controller.php');
		  $Controller = $this->controller.'Controller';
		  $Controller = new $Controller($this->_request);
		  $Action = $this->action.'Action';
		  if(method_exists($Controller, $Action)){

		  	// before_filter
		  	if(!empty($Controller->before_filter)){
		  	  if(is_array($Controller->before_filter)){
		  	    foreach($Controller->before_filter as $action){
		  	      $Controller->$action();
		  	    }
		  	  } else {
            $Controller->after_filter();
		  	  }
		  	}
		  	
		  	// run requested action
		    $Controller->$Action();
		    
		    // after_filter
		    if(!empty($Controller->after_filter)){
		      if(is_array($Controller->after_filter)){
            foreach($Controller->after_filter as $action){
              $Controller->$action();
            }
		      } else {
            $Controller->after_filter();
		      }
		    }
		    
		  } else {
		    // bad action request
		    trigger_error('Invalid action requested ('.$Action.').', E_USER_ERROR);  
		  }
  	}
  	
  }