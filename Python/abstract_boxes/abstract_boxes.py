#
#   Abstract Random Box Art
#   Copyright 2009 Travis Crowder
#   travis.crowder@spechal.com
#   Published under the MIT License
#
import pygame, sys, random
from pygame.color import THECOLORS

pygame.init()

screen = pygame.display.set_mode([640,480])
screen.fill([0,0,0])

for i in range(1, 100):
    width = random.randint(0, 250)
    height = random.randint(0, 250)
    top = random.randint(5, 375)
    left = random.randint(5, 435)
    color_name = random.choice(THECOLORS.keys())
    color = THECOLORS[color_name]
    line_width = random.randint(1,3)
    pygame.draw.rect(screen, color, [left, top, width, height], line_width)
pygame.display.flip()
while True:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            sys.exit()