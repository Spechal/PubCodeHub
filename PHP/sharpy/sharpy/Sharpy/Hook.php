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
   * Execute bits of code that have been registered
   * 
   * Usage:
   * 
   * class TestHook {
   *   public function appStart(){
   *     echo 'Started';
   *   }
   *   public function appEnd(){
   *     echo 'Finished';
   *   }
   * }
   * 
   * class OtherHook {
   *   public function appEnd($arg){
   *     echo $arg;
   *   }
   * }
   * 
   * Hook::register('TestHook', 'appStart');
   * Hook::register('TestHook', 'appEnd');
   * Hook::register('OtherHook', 'appEnd');
   * 
   * Hook::hook('appStart');
   * 
   * echo 'Hello World!';
   * 
   * $args = 'Bye Bye';
   * Hook::hook('appEnd', $args);
   */

  /**
   * This class is currently only used for testing purposes.
   */

  final class Hook {
    
    protected static $_hooks = array();
    
    protected function __construct(){ }
    
    /**
     * Register a hook and hook point
     * 
     * @param string $hook Class to hook
     * @param string $point Method of Class to execute
     * @param string $file Path to required file
     * @return void
     */
    public static function register($hook, $point, $file = NULL){
      if($file && is_file($file))
        require_once($file);
      if(method_exists($hook, $point)){
        self::$_hooks[$hook][] = $point;
      }
    }
    
    /**
     * Checks to make sure a hook is registered
     * 
     * @param string $hook Class name
     * @param string $point Method of Class to execute
     * @return bool
     */
    public static function isRegistered($hook, $point = NULL){
      if($point == NULL && array_key_exists($hook, self::$_hooks))
        return true;
      if(in_array($point, self::$_hooks[$hook]))
        return true;
      return false;
    }
    
    /**
     * Execute a hook point on all registered hooks
     * 
     * @param string $point Method of registered hooks to execute
     * @param mixed $args Parameter(s) to pass to the hook
     */
    public static function hook($point, &$args = NULL){
      foreach(self::$_hooks as $hook => $points)
        Hook::run($hook, $point, &$args);
    }
    
    /**
     * Run a point in a hook (execute a class method)
     * 
     * @param string $hook Class name of registered hook
     * @param string $point Method of Class to execute
     * @param mixed $args Parameter(s) to pass to the hook
     */
    public static function run($hook, $point, &$args = NULL){
      if(self::isRegistered($hook, $point))
        if(is_array($args))
          call_user_func_array(array($hook, $point), &$args);
        else
          call_user_func_array(array($hook, $point), array(&$args));
    }
    
  }