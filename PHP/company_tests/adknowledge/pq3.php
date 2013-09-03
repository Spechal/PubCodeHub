<?php

  /**
   * Find the sum of an array whose numbers are not adjacent
   */

  function findAdjacentSums(array $ints){

    $totalInts = count($ints);
    
    if($totalInts < 3)
      throw new Exception('You must supply at least 3 integers to '.__FUNCTION__);
    
    $inclusive = $exclusive = $tmp = 0;
    
    for($i = 0; $i < $totalInts; $i++){
      $tmp = max($inclusive, $exclusive);
      $inclusive = $exclusive + $ints[$i];
      $exclusive = $tmp;
    }
    
    return max($inclusive, $exclusive);
    
  }
  
  $ints = array(
            array(3, 2, 7, 10), 
            array(3, 2, 5, 10, 7), 
            array(3, 2, 6, 5, 7, 12)
          );

  echo "\n=========== Exercise 3 ===========\n";        
  
  foreach($ints as $arr)
    echo findAdjacentSums($arr)."\n";
    
  echo "\n=========== End Exercise 3 =======\n";