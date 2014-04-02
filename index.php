<?php
require __DIR__.'/src/ConnectFourGame.class.php';
require __DIR__.'/src/ai/ConnectFourStrategy.class.php';
require __DIR__.'/src/ai/Random.class.php';
require __DIR__.'/src/ai/Defensive.class.php';
require __DIR__.'/src/ai/Balanced.class.php';
require __DIR__.'/src/ai/Tweaked.class.php';
require __DIR__.'/src/ai/Aggressive.class.php';
require __DIR__.'/ConnectFourManager.class.php';

$manager = new ConnectFourManager();
$ai = new Tweaked(1);

function createGame($name, $emailAddress) {
    global $manager;
    global $ai;
    
    $c = new ConnectFourGame(6, 7);    
            
    $ai->makeMove($c);    
    
    $manager->save($c);
    
    echo json_encode(array('game_id' => $c->getGameId()));
}

function makeMove($gameId, $col) {
    global $manager;
    global $ai;
    
    $c = $manager->load($gameId);
    
    if(!$c->isGameOver()) { 
        $c->makeMove(2, $col);
        
        if(!$c->isGameOver()) {
            $ai->makeMove($c);
            $manager->save($c);
        }
        
        
        
        $manager->save($c);
        
    }
    
    getState($gameId);
}

function getState($gameId) {
    global $manager;
    $c = $manager->load($gameId);
    
    print json_encode(array('game_id' => $gameId, 'state' => $c->getGameState(), 'moves' =>$c->getMoves(), 'board' => $c->getBoard(), 'pretty_board' => $c->__toString()));
}

switch($_REQUEST['action']) {
    case 'create':
        createGame($_REQUEST['name'], $_REQUEST['email']);
        break;
    case 'state':
        getState($_REQUEST['game_id']);
        break;    
    case 'move':
        makeMove($_REQUEST['game_id'], $_REQUEST['column']);
        break;
    default:
        echo <<<OUT
<pre>

========
The API
========

CREATE
======
Use this to create a game.  
Be sure to store the game_id.

Request:
http://connect4.sparefoot.com/?action=create

Response:
{
    "game_id" : "515ca3157c6e6"
}

STATE
======
Use this to get the game's state.

Request:
http://connect4.sparefoot.com/?action=state&game_id=\$GAME_ID

Response:
{
    "game_id" : "515ca3157c6e6",
    "state" : "In progress",
    "moves" : 1,
    "board" : [[0,0,0,0,0,0,0],[0,0,0,0,0,0,0],[0,0,0,0,0,0,0],[0,0,0,0,0,0,0],[0,0,0,0,0,0,0],[0,0,0,1,0,0,0]],
    "pretty_board" : "
 | 0 | 1 | 2 | 3 | 4 | 5 | 6 | \n -----------------------------\n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 1 | 0 | 0 | 0 | \n"
}

MOVE
=====
Use this to make your move.. 

Request:
http://connect4.sparefoot.com/?action=move&column=\$COLUMN_NUMBER&game_id=\$GAME_ID

Response:
{
    "game_id" : "515ca3157c6e6",
    "state" : "In progress",
    "moves" : 1,
    "board" : [[0,0,0,0,0,0,0],[0,0,0,0,0,0,0],[0,0,0,0,0,0,0],[0,0,0,0,0,0,0],[0,0,0,0,0,0,0],[0,0,0,1,0,0,0]],
    "pretty_board" : " 
 | 0 | 1 | 2 | 3 | 4 | 5 | 6 | \n -----------------------------\n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 0 | 0 | 0 | 0 | \n | 0 | 0 | 0 | 1 | 0 | 0 | 0 | \n"
}


</pre>
OUT;
        break;
}
