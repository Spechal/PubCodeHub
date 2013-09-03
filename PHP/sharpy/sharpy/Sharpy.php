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
   * Configuration
   */   
  define('BASE_PATH', realpath('../'));
  define('SHARPY_PATH', BASE_PATH.DIRECTORY_SEPARATOR.'sharpy');
  define('MODULES_PATH', SHARPY_PATH.DIRECTORY_SEPARATOR.'modules');
  define('LAYOUTS_PATH', SHARPY_PATH.DIRECTORY_SEPARATOR.'layouts');

  /**
   * Front Controller
   */

  require_once('Sharpy/Request.php');
  require_once('Sharpy/Dispatcher.php');

  class Sharpy {
  	
  	protected static $_request = NULL;
  	
  	public static function run(){
  		self::$_request = Request::getInstance();
  		$Dispatcher = new Dispatcher(self::$_request);
  		$Dispatcher->dispatch();
  	}
  	
  }