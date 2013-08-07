import pygame
from pygame.locals import *

pygame.init()

width, height = 640, 480
screen = pygame.display.set_mode((width, height))

pygame.display.set_caption('Bunny Game')

player = pygame.image.load('resources/images/dude.png')
grass = pygame.image.load('resources/images/grass.png')
castle = pygame.image.load('resources/images/castle.png')

while 1:
	screen.fill(0)

	# add grass
	for x in range(width / grass.get_width() + 1):
		for y in range(height / grass.get_height() + 1):
			screen.blit(grass, (x * 100, y * 100))

	# add the castles
	screen.blit(castle, (0, 30))
	screen.blit(castle, (0, 135))
	screen.blit(castle, (0, 240))
	screen.blit(castle, (0, 345))

	# add bunny
	screen.blit(player, (100,100))

	pygame.display.flip()

	for event in pygame.event.get():
		if event.type == pygame.QUIT:
			pygame.quit()
			exit(0)
