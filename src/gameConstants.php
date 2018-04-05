<?php

DEFINE( 'PLAYGROUNDDIM', 10 );


#items constants
#
DEFINE( 'I_TRAP', 1 );  #Hidden trap
DEFINE( 'I_BOMB', 2 );  #usable bomb
DEFINE( 'I_ROCK', 3 );  # obstacle: a rock
DEFINE( 'I_HEART', 4 ); # a heart : some life to absorb
DEFINE( 'I_BOOSTER', 5 ); #boost the speed for several turns


#tile images
#
$tiles = [
    I_BOMB => 'bomb-32.png',
    I_ROCK => 'rock-32.png',
    I_HEART => 'heart-32.png',
    I_BOOSTER => 'booster-32.png'
];


$itemsToPlace = [
    I_TRAP => 10,
    I_BOMB => 5,
    I_ROCK => 20,
    I_BOOSTER => 3
];



#required fields for each player
# id ->  number of the character inside the superhero API
$requiredFields = [ 'id', 'strength', 'durability', 'x', 'y' ];


#PLAYERS CONTANTS
#
DEFINE( 'MAXLIFE', 100 );
