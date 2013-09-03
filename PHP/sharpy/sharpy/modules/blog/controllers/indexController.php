<?php
  		
  require_once(SHARPY_PATH.'/Sharpy/Controller.php');
  
  require_once(SHARPY_PATH.'/Sharpy/User.php');
  require_once(SHARPY_PATH.'/models/Blog.php');

	class indexController extends Controller {
		
	  public function indexAction(){
	    // view all blog posts
	    $blog = new Blog($this->_db);
	    $this->_template->posts = $blog->find();
	  }
	  
	  public function userAction(){
	  	// view blog posts by a user
	  	$id = abs($_GET['id']);
	  	$user = new User($this->_db);
      $this->_template->posts = $user->id($id)->Blog->find();
      $this->_template->_action('index');
	  }
			    
	}