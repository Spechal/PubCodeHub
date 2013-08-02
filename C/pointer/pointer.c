/**
*   Basic pointer use
*/

#include <stdio.h>

void go_south_east(int *lat, int *lon){
    *lat = *lat - 1;
    *lon = *lon + 1;
}

int main(){
    int lat = 42;
    int lon = 24;

    printf("Lat = %i and Long = %i\n", lat, lon);

    go_south_east(&lat, &lon);

    printf("Lat = %i and Long = %i\n", lat, lon);

    return 0;
}
