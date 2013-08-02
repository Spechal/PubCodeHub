/**
*   typedef example
*/

#include <stdio.h>

typedef struct {
    char *name;
} equipment;

typedef struct {
    char *name;
    equipment gear;
} person;

void display(person s){
    printf("%s has %s gear.", s.name, s.gear.name);
}

int main(){
    person travis = {"Travis", {"Stuff"}};

    display(travis);

    return 0;
}
