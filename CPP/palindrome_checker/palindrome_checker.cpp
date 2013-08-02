/**
  Palindrome Checker
  Copyright 2009 Travis Crowder
  travis.crowder@spechal.com
  Published under the MIT License
*/

#include <iostream>
#include <algorithm>
#include <string>

bool isPalindrome(const std::string theWord);

int main(int argc, char* argv[]){

  std::string theWord;

  if(!argv[1]){
    std::cout << "Enter the word to check: ";
    std::cin >> theWord;
  } else {
    theWord = argv[1];
  }
  std::cout << std::endl;

  /**
    Make all of the characters lower case
  */
  int i = 0;
  while(theWord[i]){
    if(isupper(theWord[i]))
      theWord[i] = tolower(theWord[i]);
    i++;
  }

  std::cout << theWord << " is ";
  if(!isPalindrome(theWord))
    std::cout << "NOT ";
  std::cout << "a palindrome." << std::endl;
  return 0;
}

bool isPalindrome(const std::string theWord){
  std::string tmp = theWord;
  reverse(tmp.begin(), tmp.end());
  if(tmp == theWord)
    return true;
  return false;
}
