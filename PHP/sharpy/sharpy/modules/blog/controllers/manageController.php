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
  require_once(SHARPY_PATH.'/models/Blog.php');
  require_once(SHARPY_PATH.'/Sharpy/Session.php');

	class manageController extends Controller {
		
		// we need an authenticated user for the manage controller
		public $before_filter = array('authenticatedUser');		
		
		protected function authenticatedUser(){
			$Session = new Session;
			if(!empty($Session->data['user']['id']))
			  return true;
			header('Location: /index/login');
			exit;
		}
		
	  public function indexAction(){
	    // view all blog posts
	    $Blog = new Blog($this->_db, new User($this->_db, $_SESSION['user']['id']));
	    $this->_template->posts = $Blog->find();
	  }
	  
	  public function createAction(){
	  	if(!empty($_POST['submit'])){
	  		$Blog = new Blog($this->_db, new User($this->_db, $_SESSION['user']['id']));
	  	  $bind = array();
        foreach($Blog->columns() as $key => $val){
          if(isset($_POST[$val]))
            $bind[$val] = $_POST[$val];
        }
        $bind['unixtime'] = time();
	  		if($Blog->create($bind)){
	  			$this->forward('index', array('class' => 'success', 'message' => 'Post created!'));
	  		} else {
	  			$this->_template->hud = array('class' => 'error', 'message' => 'There was an error creaging the post!');
	  		}
	  	}
	  }
	  
	  public function updateAction(){
	  	if(empty($_GET['id']))
	  	  return false;
	  	  
	  	$Blog = new Blog($this->_db, new User($this->_db, $_SESSION['user']['id']));
	  	$Blog->debug();

	  	// make sure they own the blog post
	  	$post = $Blog->find(array('id' => abs($_GET['id'])));
	  	if(empty($post[0]) || $post[0]['users_id'] != $_SESSION['user']['id']){
	  		$this->_template->hud = array('class' => 'error', 'message' => 'You do not own this post!');
	  		return false;
	  	}
	  	
	    if(!empty($_POST['submit'])){
        
        $bind = array();
        foreach($Blog->columns() as $key => $val){
          if(isset($_POST[$val]))
            $bind[$val] = $_POST[$val];
        }
        $bind['unixtime'] = time();
        if($Blog->update($bind, abs($_GET['id']))){
          $this->_template->hud = array('class' => 'success', 'message' => 'Post updated!');
        } else {
          $this->_template->hud = array('class' => 'error', 'message' => 'There was an error updating the post!');
        }
      }
      
      $post = $Blog->find(array('id' => abs($_GET['id'])));
      if(!empty($post[0]))
        $this->_template->post = $post[0];
    }
    
	  public function deleteAction(){
      // make sure they own it
      $Blog = new Blog($this->_db, new User($this->_db, $_SESSION['user']['id']));
	    $post = $Blog->find(array('id' => abs($_GET['id'])));
      if(empty($post[0]) || $post[0]['users_id'] != $_SESSION['user']['id']){
        $this->_template->hud = array('class' => 'error', 'message' => 'You do not own this post!');
        return false;
      }
      if($Blog->destroy($_GET['id'])){
      	$this->forward('index', array('class' => 'success', 'message' => 'The post was removed!'));
      } else {
      	$this->_template->hud = array('class' => 'error', 'message' => 'There was a problem removing the post!');
      }
    }
			    
	}