import pygame, random, sys

pygame.init()

screen = pygame.display.set_mode([640,480])
screen.fill([255,255,255])

x = 0
y = 0
x_speed = 20
y_speed = 20

domo = pygame.image.load('domo_58.jpg')

for i in range(1, 5000):
    screen.blit(domo, [x, y])
    pygame.display.flip()
    pygame.draw.rect(screen, [255,255,255], [x, y, 58, 58], 0)
    pygame.time.delay(20)
    x += x_speed
    y += y_speed
    if x > screen.get_width() - 58 or x < 0:
        x = - x_speed
    if y > screen.get_height() - 58 or y < 0:
        y = - y_speed
    screen.blit(domo, [x, y])
    pygame.display.flip()
    

while True:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            sys.exit()
