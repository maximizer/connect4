<?php

require 'ConnectFourGame.class.php';
require 'ai/ConnectFourStrategy.class.php';
require 'ai/Random.class.php';
require 'ai/Defensive.class.php';
require 'ai/Balanced.class.php';
require 'ai/Tweaked.class.php';
require 'ai/Aggressive.class.php';


$c = new ConnectFourGame(6, 7);

$player1 = new Balanced(1);
$player2 = new Tweaked(2);

while(!$c->isGameOver()) {
    try {
        echo "\n\n".$c;
        if($c->getActivePlayer() == 1) {
            $player1->makeMove($c);
        } else {
            $player2->makeMove($c);
        }
        
        
    } catch(Exception $e) {} 
    
}
echo "\n\n".$c;
echo $c->getGameState();

