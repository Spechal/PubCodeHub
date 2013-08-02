/**
*   Basic thread use
*/

#include <stdio.h>
#include <pthread.h>

void* hello(){
    printf("Hello ");

    return NULL;
}

void* world(){
    printf("World");

    return NULL;
}

int main(){

    pthread_t t0;
    pthread_t t1;

    if(pthread_create(&t0, NULL, hello, NULL) == -1)
        fprintf(stderr, "Could not create thread t0.");
    if(pthread_create(&t1, NULL, hello, NULL) == -1)
        fprintf(stderr, "Could not create thread t1.");

    void *result;
    if(pthread_join(t0, &result) == -1)
        fprintf(stderr, "Thread t0 could not join result.");
    if(pthread_join(t1, &result) == -1)
        fprintf(stderr, "Thread t0 could not join result.");

    return 0;
}
