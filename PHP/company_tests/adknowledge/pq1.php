<?php

  /**
   * Function to find the first unrepeated char of a string
   */
  function unrepeatedChar($string){
    $len = strlen($string);
    if($len == 1) return $string;
    
    for($i = 0; $i < $len; $i++){ // iterate characters
      // if no more chars, check if last matches previous
      if(!isset($string[$i+1])){
        if($string[$i] != $string[$i-1]){
          return $string[$i];
        }
      }
      // if more chars, check if char matches next one
      // make sure current char does not match next or previous
      if($string[$i] != $string[$i+1] && $string[$i] != $string[$i-1]){
        return $string[$i];
      }
    }
    
  }
  
  $strings = array('aaabbbbbccccd', 'abbbbccc', 'cccdda', 'qwertyqqq');
  
  echo "\n=========== Exercise 1 ===========\n"; 
  
  foreach($strings as $string)
    echo unrepeatedChar($string)."\n";

  echo "\n=========== End Exercise 1 =======\n";