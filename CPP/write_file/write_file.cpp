/**
  Write to a file (version 1)
  Copyright 2009 Travis Crowder
  travis.crowder@spechal.com
  Published under the MIT License
*/
#include <iostream>
#include <fstream>

int main(int argc, char* argv[]){

  // create the file handle, opening the file as well
  std::fstream myFile("text.txt", std::ios::out);

  if(myFile.is_open()){
    myFile << "I am just some text in some file.";
    myFile.close();
    std::cout << "File written to." << std::endl;
  } else {
    // could not create/write to file
    std::cout << "Could not write to file." << std::endl;
    return -1;
  }

  return 0;
}
