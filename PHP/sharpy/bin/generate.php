#!/usr/bin/php
<?php

  require_once('../sharpy/Sharpy.php');

  switch($argv[1]){
  	case 'module':
  		$module_path = MODULES_PATH.DIRECTORY_SEPARATOR.$argv[2];
  		makeModule($argv[2]);
  		break;
  	case 'm':
  	case 'model':
  		makeModel($argv[2]);
  		break;
  	case 'controller':
  	case 'c':
  		$module_controller = explode(':', $argv[2]);
  		makeController($module_controller[0], $module_controller[1]);
  		break;
    default:
      die("Invalid command line arguements\n");
  
  }
  
  function makeController($module, $controller){
  	$output = "<?php
  		
  require_once(SHARPY_PATH.'/Sharpy/Controller.php');

	class ".$controller."Controller extends Controller {
			    
	  public function indexAction(){
	    // stub
	  }
			    
	}";
  	$partial = "<!-- stub -->";
  	makeModuleDirs($module);
  	makeControllerDirs($module, $controller);
  	$controller_path = MODULES_PATH.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'controllers';
  	$partials_path = MODULES_PATH.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'partials';
  	file_put_contents($controller_path.DIRECTORY_SEPARATOR.$controller.'Controller.php', $output);
    file_put_contents($partials_path.DIRECTORY_SEPARATOR.$controller.DIRECTORY_SEPARATOR.'index.phtml', $partial);
    die($controller."Controller generated.\n");
  }
  
  function makeControllerDirs($module, $controller){
  	$partials_path = MODULES_PATH.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'partials';
  	if(!is_dir($partials_path.DIRECTORY_SEPARATOR.$controller))
  		if(!mkdir($partials_path.DIRECTORY_SEPARATOR.$controller))
  			die("Could not generate directory structure for controller partials.\n");
  	return true;
  }
  
  function makeModel($model){
  	$output = '<?php
  		
  require_once(SHARPY_PATH.\'/Sharpy/Database/DataMapper.php\');

	class '.$model.' extends DataMapper {
			    
	  public function __construct(Database $db){
  		parent::__construct($db);
      $this->table(\''.strtolower($model).'\');
      $this->columns(array(\'id\'));      
  	}
			    
	}';
  	$model_path = MODELS_PATH.DIRECTORY_SEPARATOR;
  	if(file_put_contents($model_path.$model.'.php', $output))
  		die('The model was generated.');
  	die('There was a problem generating the model.');
  }
  
  function makeModule($module){
  	makeModuleDirs($module);
  	makeController($module, 'index');
  	die('Module generated.');
  }
  
  function makeModuleDirs($module){
  	if(!is_dir(MODULES_PATH.DIRECTORY_SEPARATOR.$module))
  		if(!mkdir(MODULES_PATH.DIRECTORY_SEPARATOR.$module)
  				|| !mkdir(MODULES_PATH.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'controllers')
  				|| !mkdir(MODULES_PATH.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'partials'))
  			die("Could not generate directory structure for module.\n");
  	return true;
  }