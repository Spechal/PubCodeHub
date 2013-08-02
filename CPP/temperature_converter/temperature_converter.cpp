/**
  Temperature Converter
  Copyright 2009 Travis Crowder
  travis.crowder@spechal.com
  Published under the MIT License
*/

#include <iostream>
#include <iomanip>

int main(int argc, char* argv[]){

  /**
    Create a variable to hold the input temperature value
  */
  double input = NULL;

  /**
    Create a variable to hold the output temperature value
  */
  double output = 0;

  /**
    Create a variable to hold which type we are converting from
  */
  char convFrom;
  /**
    Create a variable to hold which type we are converting to
  */
  char convTo;

  while(input == NULL){
    std::cout << "Enter the source temperature, without the type: ";
    std::cin >> input;
    std::cout << std::endl;
  }

  while(convFrom != 'C' && convFrom != 'F'){
    std::cout << "What is the source temperature type?" << std::endl;
    std::cout << "[C]entigrade" << std::endl;
    std::cout << "[F]ahrenheit" << std::endl;
    std::cin >> convFrom;
  }

  /**
    Output the results
  */
  std::cout << "Your converted temperature is: ";
  if(convFrom == 'F'){
    output = (input - 32) * 5 / 9;
    convTo = 'C';
  } else {
    output = (input * 9 / 5) + 32;
    convTo = 'F';
  }
  std::cout << output << convTo << std::endl;

  return 0;
}
