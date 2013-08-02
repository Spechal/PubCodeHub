/**
  Guessing Game
  Copyright 2009 Travis Crowder
  travis.crowder@spechal.com
  Published under the MIT License
*/
#include <iostream>

int main(int argc, char* argv[]){

  /**
    Create our variable to hold the guess made
  */
  int guess = 0;

  /**
    Let's keep track of the number of guesses made.
  */
  int guesses = 0;

  /**
    Set the secret to be a random number between 1 and 100
    Remember to seed the random number generator
  */
  srand(time(0));
  int secret = rand() % 100 + 1;

  /**
    while our guess is not the secret, get a guess
  */
  while(guess != secret){
    std::cout << "Enter your guess, between 1 and 100: ";
    std::cin >> guess;
    guesses++;
    if(guess == secret){
      /** guess was correct */
      std::cout << "You've figured out the secret in " << guesses;
      if(guesses == 1)
        std::cout << " guess";
      else
        std::cout << " guesses";
      std::cout << "!  It was " << secret << "!" << std::endl;
    } else if(guess > secret){
      /** guess was too high */
      std::cout << "Your guess was too high!  Try again!" << std::endl;
    } else {
      /** our guess can only be lower, logically */
      std::cout << "Your guess was too low!  Try again!" << std::endl;
    }
  }

  return 0;
}
