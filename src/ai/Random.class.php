<?php
/**
 * This strategy just randomly drops pieces
 * 
 */
class Random extends ConnectFourStrategy
{
    public function makeMove(ConnectFourGame $game) {
        while(!$game->makeMove($this->player, rand(0, $game->getColumns() - 1)));
    }
}
