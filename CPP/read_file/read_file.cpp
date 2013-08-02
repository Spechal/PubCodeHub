/**
  Read from a file (version 1)
  Copyright 2009 Travis Crowder
  travis.crowder@spechal.com
  Published under the MIT License
*/
#include <iostream>
#include <fstream>
#include <string>

int main(int argc, char* argv[]){

  // create the file handle, opening the file
  std::fstream myFile("text.txt", std::ios::in);

  // create a string to hold the line
  std::string line;

  if(myFile.is_open()){
    while(!myFile.eof()){
      getline(myFile, line);
      std::cout << line << std::endl;
    }
  } else {
    std::cout << "Could not open text.txt for reading." << std::endl;
    return -1;
  }

  return 0;
}
