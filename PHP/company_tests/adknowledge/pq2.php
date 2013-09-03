<?php

  /**
   * Find the second largest item in an array
   * 
   * the quick way would be arsort($strings); echo $strings[1];
   */

  $strings = array('aaa', 'aaaaaa', 'a', 'a', 'aa', 'aaaa', 'a', 'aaaaaaaaaa', 'aaa');
  
  function secondLargest(array $elements){
    if(count($elements) == 1) return $elements[0];
    unset($elements[largestElement($elements)]);
    return $elements[largestElement($elements)];
  }
  
  function largestElement(array $elements){
    $largest = array('key' => 0, 'size' => 0);
    foreach($elements as $key => $element)
      if(strlen($element) > $largest['size'])
        $largest = array('key' => $key, 'size' => strlen($element));
    return $largest['key'];
  }
  
  #arsort($strings);  
  #echo $strings[1];
  
  echo "\n=========== Exercise 2 ===========\n";
  echo secondLargest($strings);
  echo "\n=========== End Exercise 2 =======\n";