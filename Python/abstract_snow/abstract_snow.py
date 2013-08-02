#
#   Abstract Random Snow Art
#   Copyright 2009 Travis Crowder
#   travis.crowder@spechal.com
#   Published under the MIT License
#
import pygame, sys, random
from pygame.color import THECOLORS

pygame.init()

screen = pygame.display.set_mode([640,480])
screen.fill([0,0,0])

for i in range(1, 2500):
    top = random.randint(2, 478)
    left = random.randint(2, 638)
    color_name = random.choice(THECOLORS.keys())
    color = THECOLORS[color_name]
    pygame.draw.rect(screen, color, [left, top, 1, 1], 1)
pygame.display.flip()
while True:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            sys.exit()