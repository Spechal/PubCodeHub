# comment
import random

secret = random.randint(1,100)
guess = 0
guesses = 0

print "Guess the secret number between 1 and 100!"

while guess != secret:
    guesses = guesses + 1
    guess = input("Enter your guess: ")
    if guess > secret:
        print "Your guess is too high.  Try again!"
    elif guess < secret:
        print "Your guess is too low.  Try again!"
    else:
        print "You guessed the secret in", guesses, "guesses!  It was", secret
