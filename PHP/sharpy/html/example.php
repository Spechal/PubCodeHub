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

  require_once('../sharpy/Sharpy.php');
  
  Sharpy::run();
  
  /**
   * This file is currently only used for testing purposes.
   */
  
  #require_once('../sharpy/Sharpy/Database/PostgreSQL.php');
  #$params = array('user' => 'postgres', 'password' => '123456', 'dbname' => 'mydatabase', 'host' => 'localhost');
  #$P = PostgreSQL::getInstance($params);
  
  
  require_once('../sharpy/Sharpy/Database/MySQL.php');
  $params = array('user' => 'sharpy', 'password' => 'sharpy', 'dbname' => 'sharpy', 'host' => 'localhost');
  $M = MySQL::getInstance($params);
  $M->debug();
  
  require_once('../sharpy/Sharpy/User.php');
  
  $U = new User($M);
  print_r($U->id(1)->find());
  #print_r($U->find(array('peaches' => sha1('password'))));
  #$U->dump();
  // need to get this converting to $U->Login->find();
  #print_r($U->Login->find());
  #require_once(SHARPY_PATH.'/models/Blog.php');
  #$B = new Blog($M, $U);
  #print_r($B->find());
  