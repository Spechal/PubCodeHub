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

  class File {
  	
  	protected $_file = NULL;
  	
  	/**
     * Allowed file extensions
     * @var mixed
     */
    protected $_allowed = array('jpg', 
                                'jpeg', 
                                'gif', 
                                'png', 
                                'psd', 
                                'ttf', 
                                'pdf', 
                                'doc', 
                                'docx', 
                                'odt', 
                                'eps', 
                                'ai', 
                                'zip'
                            );
  	
    protected $_forbidden = array('exe', 'vbs');
                            
  	public function __construct(array $file){
  		$this->_file = $file;
  		if(!isset($this->_file['error']))
  		  trigger_error('Invalid file supplied.', E_USER_WARNING);
  	}
  	
  	/**
  	 * Gets the current allowed extentions or add a new extention
  	 * @param string|null
  	 */
  	public function allowedExtensions($new = NULL){
  		if($new && is_array($new)){
  			$this->_allowed = $new;
  		}
  		return $this->_allowed;
  	}
  	
  	/**
     * Gets the current forbidden extentions or forbids a new extention
     * @param string|null
     */
    public function forbiddenExtensions($new = NULL){
      if($new && is_array($new)){
        $this->_forbidden = $new;
      }
      return $this->_forbidden;
    }
  	
    /**
     * Verify the image extension is valid
     */
    public function valid(){
      if(in_array($this->getExtension(), $this->allowedExtensions()) && !in_array($this->getExtension(), $this->forbiddenExtensions()))
        return true;
      return false;
    }
    
    /**
     * Alias to vaid
     * @see valid
     */
    public function allowed(){
    	return $this->valid();
    }
    
    /**
     * Get the extension of a file based on the characters 
     * after the final period in the file name.
     * 
     * @return string
     */
    public function getExtension(){
      return substr($this->_file['name'], strrpos($this->_file['name'], '.')+1);
    }
  	
    /**
     * Moves a to the specific path
     * @param string 
     */
  	public function move($path){
  		if(move_uploaded_file($this->_file['tmp_name'], $path . DIRECTORY_SEPARATOR . $this->_file['name']))
  		  return true;
  		return false;
  	}
  	
  	public function getError(){
  	  switch($this->_file['error']){
        case 1:
          $hud = 'file_upload_ini';
          break;
        case 2:
          $hud = 'file_upload_max';
          break;
        case 3:
          $hud = 'file_upload_partial';
          break;
        case 6:
          $hud = 'file_upload_tmp';
          break;
        case 7:
          $hud = 'file_upload_write';
          break;
        case 8:
          $hud = 'file_upload_ext';
          break;
        case 4:
        default:
          $hud = NULL;
      }
      return $hud;
  	}
  	
  }