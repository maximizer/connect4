<?php

class ConnectFourManager
{
    public function save(ConnectFourGame $game) {
        $fh = fopen('/var/log/connect4/'.$game->getGameId().'.dat', 'w');
        fwrite($fh, serialize($game));
    }
    
    /**
     * 
     * @param string $gameId
     * @return ConnectFourGame
     */
    public function load($gameId) {
        return unserialize(file_get_contents('/var/log/connect4/'.$gameId.'.dat'));
    }
    
}