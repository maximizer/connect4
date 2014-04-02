<?php

abstract class ConnectFourStrategy
{
    protected $player;
    
    public final function __construct($player) {
        $this->player = $player;
    }
    
    public abstract function makeMove(ConnectFourGame $game);
}