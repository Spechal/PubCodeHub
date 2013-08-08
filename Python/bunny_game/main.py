# 1 - Libraries
import pygame, math, random
from pygame.locals import *

# 2 - Init
pygame.init()
screen_width, screen_height = 640, 480
screen = pygame.display.set_mode((screen_width, screen_height))

keys = [False, False, False, False]
player_position = [100, 100]
accuracy = [0, 0]
arrows = []
badguy_timer = 100
badguy_timer_alt = 0
badguys = [[640, 100]]
player_health = 194

pygame.display.set_caption('Bunny Game')

# 3 - Images
player = pygame.image.load('resources/images/dude.png')
grass = pygame.image.load('resources/images/grass.png')
castle = pygame.image.load('resources/images/castle.png')
arrow = pygame.image.load('resources/images/bullet.png')
badger = pygame.image.load('resources/images/badguy.png')
badger_animated = badger
health_bar = pygame.image.load('resources/images/healthbar.png')
health = pygame.image.load('resources/images/health.png')
lose_screen = pygame.image.load('resources/images/gameover.png')
win_screen = pygame.image.load('resources/images/youwin.png')

running = 1
exit_code = 0

# 4 - Loop forever
while running:
    badguy_timer -= 1
    # 5 clear before redraw
    screen.fill(0)

    # add grass
    for x in range(screen_width / grass.get_width() + 1):
        for y in range(screen_height / grass.get_height() + 1):
            screen.blit(grass, (x * 100, y * 100))

    # add the castles
    screen.blit(castle, (0, 30))
    screen.blit(castle, (0, 135))
    screen.blit(castle, (0, 240))
    screen.blit(castle, (0, 345))

    # 6 - 6.1 - Add elements to screen
    mouse_position = pygame.mouse.get_pos()
    angle = math.atan2(mouse_position[1] - (player_position[1] + 32), mouse_position[0] - (player_position[0] + 26))
    player_rotated = pygame.transform.rotate(player, 360 - angle * 57.29)
    player_position_new = (player_position[0] - player_rotated.get_rect().width / 2, player_position[1] - player_rotated.get_rect().height / 2)
    screen.blit(player_rotated, player_position_new)

    # 6.2 bullets
    for bullet in arrows:
        index = 0
        velocity_x = math.cos(bullet[0]) * 10
        velocity_y = math.sin(bullet[0]) * 10
        bullet[1] += velocity_x
        bullet[2] += velocity_y
        if bullet[1] < -64 or bullet[1] > 640 or bullet[2] < -64 or bullet[2] > 480:
            arrows.pop(index)
        index += 1
        for projectile in arrows:
            arrow_rotated = pygame.transform.rotate(arrow, 360 - projectile[0] * 57.29)
            screen.blit(arrow_rotated, (projectile[1], projectile[2]))

    # 6.3 badgers
    if badguy_timer == 0:
        badguys.append([640, random.randint(50, 430)])
        badguy_timer = 100 - (badguy_timer_alt * 2)
        if badguy_timer_alt >= 35:
            badguy_timer_alt = 35
        else:
            badguy_timer_alt += 5

    badguy_index = 0
    for badguy in badguys:
        if badguy[0] < -64:
            badguys.pop(badguy_index)
        badguy[0] -= 7
        # 6.3.1
        badrect = pygame.Rect(badger.get_rect())
        badrect.top = badguy[1]
        badrect.left = badguy[0]
        if badrect.left < 64:
            player_health -= random.randint(5, 20)
            badguys.pop(badguy_index)
        # 6.3.2
        arrow_index = 0
        for bullet in arrows:
            bullrect = pygame.Rect(arrow.get_rect())
            bullrect.left = bullet[1]
            bullrect.top = bullet[2]
            if badrect.colliderect(bullrect):
                accuracy[0] += 1
                badguys.pop(badguy_index)
                arrows.pop(arrow_index)
            arrow_index += 1
        # 6.3.3
        badguy_index += 1

    for badguy in badguys:
        screen.blit(badger_animated, badguy)

    #screen.blit(player, player_position)

    # 6.4 clock
    font = pygame.font.Font(None, 24)
    survivedText = font.render(str((90000 - pygame.time.get_ticks()) / 60000) + ":" + str((90000-pygame.time.get_ticks()) / 1000 % 60).zfill(2), True, (0, 0, 0))
    textRect = survivedText.get_rect()
    textRect.topright = [635, 5]
    screen.blit(survivedText, textRect)

    # 6.5 health bar
    screen.blit(health_bar, (5, 5))
    for h in range(player_health):
        screen.blit(health, (h+8, 8))

    # 7 - Update screen
    pygame.display.flip()

    # 8 - Event loop
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            pygame.quit()
            exit(0)
        if event.type == pygame.MOUSEBUTTONDOWN:
            mouse_pos = pygame.mouse.get_pos()
            accuracy[1] += 1
            arrows.append([math.atan2(mouse_pos[1] - (player_position_new[1] + 32), mouse_pos[0] - (player_position_new[0] + 26)), player_position_new[0] + 32, player_position_new[1] + 32])
        if event.type == pygame.KEYDOWN:
            if event.key == K_w:
                keys[0] = True
            elif event.key == K_a:
                keys[1] = True
            elif event.key == K_s:
                keys[2] = True
            elif event.key == K_d:
                keys[3] = True
        if event.type == pygame.KEYUP:
            if event.key == K_w:
                keys[0] = False
            elif event.key == K_a:
                keys[1] = False
            elif event.key == K_s:
                keys[2] = False
            elif event.key == K_d:
                keys[3] = False


    if keys[0]:
        player_position[1] -= 5
    elif keys[2]:
        player_position[1] += 5
    elif keys[1]:
        player_position[0] -= 5
    elif keys[3]:
        player_position[0] += 5

    if pygame.time.get_ticks() >= 90000:
        running = 0
        exit_code = 1
    if player_health <= 0:
        running = 0
        exit_code = 0
    if accuracy[1] != 0:
        total_accuracy = accuracy[0] * 1.0 / accuracy[1] * 100
    else:
        total_accuracy = 0

if exit_code == 0:
    pygame.font.init()
    font = pygame.font.Font(None, 24)
    text = font.render("Accuracy: " + str(total_accuracy) + "%", True, (255, 0, 0))
    textRect = text.get_rect()
    textRect.centerx = screen.get_rect().centerx
    textRect.centery = screen.get_rect().centery + 24
    screen.blit(lose_screen, (0,0))
    screen.blit(text, textRect)
else :
    pygame.font.init()
    font = pygame.font.Font(None, 24)
    text = font.render("Accuracy: " + str(total_accuracy) + "%", True, (0,255,0))
    textRect = text.get_rect()
    textRect.centerx = screen.get_rect().centerx
    textRect.centery = screen.get_rect().centery + 24
    screen.blit(win_screen, (0,0))
    screen.blit(text, textRect)

while 1:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            pygame.quit()
            exit(0)
    pygame.display.flip()