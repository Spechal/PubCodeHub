/**
*   Card counting simulation.
*   Start count at 0.
*   if 2 < card > 7
*   then count++
*   else count--
*/
#include <stdio.h>
#include <stdlib.h>

int main()
{
    char card_name[3];
    int val = -1;
    int count = 0;
    while(val != 0){
        puts("Enter the card_name: ");
        scanf("%2s", card_name);
        switch(card_name[0]){
            case 'K':
            case 'k':
            case 'Q':
            case 'q':
            case 'J':
            case 'j':
                val = 10;
                break;
            case 'A':
            case 'a':
                val = 11;
                break;
            case 'X':
            case 'x':
                val = 0;
                break;
            default:
                val = atoi(card_name);
                if(val <= 1 || val > 10){
                    val = -1;
                    puts("Invalid card value.");
                    continue;
                }
        }
        if ((val > 2) && (val < 7))
            count++;
        else if (val == 10)
            count--;
        printf("Count is %i.\n", count);
    }
    return 0;
}
