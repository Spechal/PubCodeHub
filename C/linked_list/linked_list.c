/**
*   Basic linked list use
*/

#include <stdio.h>

typedef struct list {
    char *name;
    struct list *next;
} list;

int main(){

    list Tom = {"Tom", NULL};
    list Frank = {"Frank", NULL};
    list Jim = {"Jim", NULL};
    list Axel = {"Axel", NULL};

    Tom.next = &Frank;
    Frank.next = &Jim;
    Jim.next = &Axel;

    list *i = &Tom;

    for(; i != NULL; i = i->next)
        printf("Name: %s\n", i->name);

    return 0;
}
