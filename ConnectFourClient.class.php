<?php

class ConnectFourClient
{
    const ENDPOINT = 'http://connect4.sparefoot.com/';
    // const ENDPOINT = 'http://localhost/';
    private $gameId;
    
    public function __construct() {
        
    }
    
    public function createGame() {
        $resp = file_get_contents(self::ENDPOINT.'/?action=create');
        $resp = json_decode($resp);
        
        $this->gameId = $resp->game_id;
    }
    
    public function move($col) {
        $resp = file_get_contents(self::ENDPOINT.'/?action=move&game_id='.$this->gameId . '&column='.$col);
        $resp = json_decode($resp);    
    }
    
    public function printBoard() {
        $resp = file_get_contents(self::ENDPOINT.'/?action=state&game_id='.$this->gameId);
        $resp = json_decode($resp);     
        
        return $resp->pretty_board;
    }
    
    public function getResult() {
        $resp = file_get_contents(self::ENDPOINT.'/?action=state&game_id='.$this->gameId);
        $resp = json_decode($resp);     
        
        return $resp->state;
    }
    
}

$c = new ConnectFourClient();
$c->createGame();


while($c->getResult() == 'In progress') {
    echo $c->printBoard() . "\n";
    echo "Enter column: ";
    echo $c->move(trim(fread(STDIN, 1024)));
    echo "\n";
}

echo $c->printBoard() . "\n";
echo "Game over: " . $c->getResult();
