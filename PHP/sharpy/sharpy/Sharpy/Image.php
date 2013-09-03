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
   * This class is currently only used for testing purposes. -- This class needs to be redone ... -Travis
   */

  require_once(SHARPY_PATH.'/Sharpy/File.php');

  class Image extends File {
  	
  	/**
  	 * Path to save image
  	 * @var string
  	 */
  	protected $_savePath = NULL;
  	/**
  	 * Allowed image file extensions
  	 * @var mixed
  	 */
  	protected $_allowed = array('jpg', 'jpeg', 'gif', 'png');
  	/**
  	 * Absolute file path
  	 * @var string
  	 */
  	protected $_file = NULL;
  	
  	/**
  	 * Set the image name
  	 * @var string Final name of file
  	 */
  	protected $_name = NULL;
  	
  	/**
  	 * File properties obtained from $_FILES
  	 * @var mixed
  	 */
  	protected $_properties = array();
  	
  	/**
  	 * 
  	 * @param array $files
  	 */
  	public function __construct($savePath = NULL, $files = NULL){
  		if($savePath)
        $this->setSavePath($savePath);
  		if(!empty($files))
  		  $this->_load($files);
  	}
  	
  	protected function _load(array $files){
  		$this->setProperties($files);
      $this->setName(strtolower($this->_properties['name']));
  	}
  	
  	/**
  	 * Set the path to save the file to
  	 * @param string $path Path to save file to
  	 */
  	public function setSavePath($path){
  		if(is_dir($path)){
  			if($path[strlen($path)-1] == DIRECTORY_SEPARATOR)
  			  $path = substr($path, 0, -1);
  		  $this->_savePath = $path;
  		  return true;
  		}
  		return false;
  	}
  	
  	public function getSavePath(){
  		return $this->_savePath;
  	}
  	
  	/**
  	 * Set the file properties
  	 * @param mixed $files $_FILES array
  	 */
  	public function setProperties(array $files){
  		$this->_properties = $files;
  		$this->setName($files['name']);
  	}
  	
  	public function property($name){
  		if(isset($this->_properties[$name]))
  		  return $this->_properties[$name];
  	}
  	
  	/**
     * Alias to vaid
     * @see valid
     */
  	public function validImage(){
  		return $this->valid();
  	}
  	
  	public function getExtension(){
  		return substr($this->getName(), strrpos($this->getName(), '.')+1);
  	}
  	
  	/**
  	 * Set the name of the file
  	 * @param string $name File name
  	 */
  	public function setName($name){
  		$this->_name = $name;
  	}
  	
  	public function getName(){
  		return $this->_name;
  	}
  	
  	/**
  	 * Move the file to a location
  	 */
  	public function moveUpload(){
  		if($this->validImage() && move_uploaded_file($this->_properties['tmp_name'], $this->_savePath.DIRECTORY_SEPARATOR.$this->_name))
  		  return true;
  		return false;
  	}
  	
  	public function remove($absPath){
  		return unlink($absPath);
  	}
  	
  	/**
  	 * Resize and save the image
  	 * @param int $w Width to resize to
  	 * @param int $h Height to resize to
  	 * @param string $out Output to append to file name
  	 */
  	public function resize($w = 800, $h = 600, $out = NULL){
  		// the original path to the file
  		$file = $this->_savePath.DIRECTORY_SEPARATOR.$this->_name;
  		// the new file name, sans extension
  		if(!$out)
  		  $out = $this->_savePath.DIRECTORY_SEPARATOR.substr($this->_name, 0, strrpos($this->_name, '.'));
  		else
  		  $out = $this->_savePath.DIRECTORY_SEPARATOR.substr($this->_name, 0, strrpos($this->_name, '.')).'-'.$out;
  		$file_info = getimagesize($file);
      $type = $file_info[2];
      $width = $file_info[0];
      $height = $file_info[1];
      $dest = imagecreatetruecolor($w, $h);
      switch($type){
        case IMAGETYPE_PNG:
          $tmp = imagecreatefrompng($file);
          imagecopyresampled($dest, $tmp, 0, 0, 0, 0, $w, $h, $width, $height);
          imagepng($dest, $out.'.png', 80);
          break;
        case IMAGETYPE_GIF:
          $tmp = imagecreatefromgif($file);
          imagecopyresampled($dest, $tmp, 0, 0, 0, 0, $w, $h, $width, $height);
          imagegif($dest, $out.'.gif');
          break;
        case IMAGETYPE_JPEG:
        default:
          $tmp = imagecreatefromjpeg($file);
          imagecopyresampled($dest, $tmp, 0, 0, 0, 0, $w, $h, $width, $height);
          imagejpeg($dest, $out.'.jpg', 80);
      }
      return imagedestroy($dest);
  	}
  	
  }