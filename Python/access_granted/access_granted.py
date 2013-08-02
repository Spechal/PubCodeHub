password = "football"
attempts = 0
guess = ""

guess = raw_input("Enter the password: ")

while guess != password:
    print "Access Denied"
    guess = raw_input("Enter the password: ")

print "Access Granted"
