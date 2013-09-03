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

  class Template extends Object {
  	
  	/**
  	 * Bool variable to toggle template file rendering
  	 * @var bool
  	 */
  	protected $_render = TRUE;
  	
  	/**
     * Bool variable to toggle partial file rendering
     * @var bool
     */
    protected $_ajax = TRUE;
    
    /**
     * String variable used to hold the current template file extension
     * @var string
     */
    protected $_extension = 'phtml';
    
    /**
     * String variable used to hold the current template directory name
     * @var string
     */
    protected $_template = 'default';
    
    /**
     * String variable used to hold the current template layout name
     * i.e. default template uses index as the layout
     * @var string
     */
    protected $_layout = 'index';
    
    /**
     * String variable used to hold the current partials directory name
     * @var string
     */
    protected $_partials = 'partials';
    
    
    
    public function __construct(){
    	
    }
    
    public function noRender(){
    	$this->_render = FALSE;
    }
    
    /**
     * Renders the template layout, if applicable
     */
    public function render($template = NULL, $layout = NULL){
    	if(!$this->_render)
    	  return true;
      // allow for HUD messages after redirect
      // by storing the message in _SESSION[HUD]
      // nullify _SESSION[HUD] afterwards
      // this prevents lingering or duplicate messages
      if(!empty($_SESSION['HUD'])){
        $this->hud = $_SESSION['HUD'];
        $_SESSION['HUD'] = NULL;
      }
    	if($template) $this->_template = $template;
    	if($layout) $this->_layout = $layout;
    	require_once(LAYOUTS_PATH.'/'.$this->_template.'/'.$this->_layout.'.'.$this->_extension);
    }
    
    /**
     * Renders the template partial
     */
    public function renderPartial($module = NULL, $controller = NULL, $action = NULL){
    	if($module) $this->_module($module);
    	if($controller) $this->_controller($controller);
    	if($action) $this->_action($action);
    	require_once(MODULES_PATH.'/'.$this->_module.'/'.$this->_partials.'/'.$this->_controller.'/'.$this->_action.'.'.$this->_extension);
    }
  	
  }