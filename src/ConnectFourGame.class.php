<?php

class ConnectFourGame
{
    const EMPTY_SPACE      = 0;
    const PLAYER_ONE_SPACE = 1;
    const PLAYER_TWO_SPACE = 2;
    
    const GAME_STATE_IN_PROGRESS    = 0;
    const GAME_STATE_PLAYER_ONE_WIN = 1;
    const GAME_STATE_PLAYER_TWO_WIN = 2;
    const GAME_STATE_PLAYER_DRAW    = 3;
    
    private $gameId;
    private $board;
    private $columns;
    private $rows;
    private $moves;
    private $activePlayer;
    private $gameState;
    
    public function __construct($rows = 6, $columns = 7) {
        $this->gameId = uniqid();
        $this->columns = $columns;
        $this->rows = $rows;
        $this->moves = 0;
        $this->activePlayer = self::PLAYER_ONE_SPACE;
        $this->gameState = self::GAME_STATE_IN_PROGRESS;
        
        $this->_initBoard($rows, $columns);
    }
    
    public function getGameId() {
        return $this->gameId;
    }
    
    public function getNextAvailableRow($column) {
        for( $row = $this->rows-1; $row >= 0; $row-- ) {
            if(self::EMPTY_SPACE === $this->board[$row][$column]) {
                return $row;
            }   
        }
        return false;
    }
    
    public function getMoves() {
        return $this->moves;
    }
    
    public function getRows() {
        return $this->rows;
    }
    
    public function getColumns() {
        return $this->columns;
    }
    
    public function getActivePlayer() {
        return $this->activePlayer;
    }
    
    public function isGameOver() {
        return $this->gameState !== self::GAME_STATE_IN_PROGRESS;
    }
    
    public function getGameState() {
        switch($this->gameState) {
            case self::GAME_STATE_IN_PROGRESS:
                return 'In progress';
                break;
            case self::GAME_STATE_PLAYER_DRAW:
                return 'Draw';
                break;
            case self::GAME_STATE_PLAYER_ONE_WIN:
                return 'Player 1 wins!';
                break;
            case self::GAME_STATE_PLAYER_TWO_WIN:
                return 'Player 2 wins!';
                break;       
        }
    }
    
    public function makeMove($playerNum, $column) {
        
        if($this->activePlayer != $playerNum) {
            throw new Exception('It is Player ' . $playerNum . '\'s  turn.');
        }
        
        if(!$this->_inBounds(0, $column)) {
            throw new Exception('Invalid column');
        }
        
        if($this->board[0][$column] !== self::EMPTY_SPACE) {
            return false;
        }
        
        for( $row = $this->rows-1; $row >= 0; $row-- ) {
            if(self::EMPTY_SPACE === $this->board[$row][$column]) {

                // echo "Move <" . $column . '>' . "\n";
                $this->board[$row][$column] = $this->activePlayer;
                
                ++ $this->moves;
                
                if($this->_hasWon($row, $column)) {
                    $this->gameState = $this->activePlayer == 
                            self::PLAYER_ONE_SPACE ? 
                            self::GAME_STATE_PLAYER_ONE_WIN : 
                            self::GAME_STATE_PLAYER_TWO_WIN;
                }
                
                if($this->moves >= $this->rows * $this->columns) {
                    $this->gameState = self::GAME_STATE_PLAYER_DRAW;
                }
                break;
            }
        }
        $this->_togglePlayer();
        return true;
    }
   
    
    /**
     * 
     * @param int $row
     * @param int $col
     */
    private function _hasWon($row, $column) {
        return $this->_checkVertical($row, $column) ||
               $this->_checkHorizontal($row, $column) ||
               $this->_checkDiagnalLeft($row, $column) ||
               $this->_checkDiagnalRight($row, $column);
    }
    
    private function _checkVertical($row, $column) {
        $count = 1;

        /* check down */
        for($r = $row +1; $this->_inBounds($r, $column); ++$r) {
            if($this->board[$r][$column] === $this->board[$row][$column]) {
                ++ $count;
            } else {
                break;
            }
        }
        
        // echo "vert cnt: " . $count . "\n";
        
        return ($count >= 4);        
    }
    
    private function _checkHorizontal($row, $column) {
        $count = 1;

        /* check to the left */
        for($c = $column-1; $this->_inBounds($row, $c); --$c) {
            if($this->board[$row][$c] !== $this->board[$row][$column]) {
                break;
            }
            ++ $count;
        }
        
        /* check to the right */
        for($c = $column+1; $this->_inBounds($row, $c); ++$c) {
            if($this->board[$row][$c] !== $this->board[$row][$column]) {
                break;
            }
            ++ $count;
        }
        
        // echo "horiz cnt: " . $count . "\n";
        
        return ($count >= 4);
    }    
    
    private function _checkDiagnalLeft($row, $column) {
        $count = 1;

        /* check upleft */
        $r = $row - 1;
        $c = $column - 1;
        while($this->_inBounds($r, $c)) {
            if($this->board[$r][$c] !== $this->board[$row][$column]) {
                break;
            }     
            ++ $count;
            --$r;
            --$c;            
        }
        
        /* check to the downright */
        $r = $row + 1;
        $c = $column + 1;
        while($this->_inBounds($r, $c)) {
            if($this->board[$r][$c] !== $this->board[$row][$column]) {
                break;
            }      
            ++ $count;
            ++$r;
            ++$c;            
        }
        
        // echo "left diag cnt: " . $count . "\n";
        return $count >= 4;        
    }
    
    private function _checkDiagnalRight($row, $column) {
        $count = 1;

        /* check upright */
        $r = $row - 1;
        $c = $column + 1;
        while($this->_inBounds($r, $c)) {
            if($this->board[$r][$c] !== $this->board[$row][$column]) {
                break;
            }    
            ++ $count;
            --$r;
            ++$c;            
        }
        
        /* check to the downleft */
        $r = $row + 1;
        $c = $column - 1;
        while($this->_inBounds($r, $c)) {
            if($this->board[$r][$c] !== $this->board[$row][$column]) {
                break;
            }   
            ++ $count;
            ++$r;
            --$c;            
        }
        
        // echo "right diag cnt: " . $count . "\n";
        
        return $count >= 4;    
    }    
    
    public function _inBounds($row, $column) {
        return ($row < $this->rows && $row >= 0 && 
                $column < $this->columns && $column >= 0);
    }
    
    private function _togglePlayer() {
        if($this->activePlayer == self::PLAYER_ONE_SPACE) {
            $this->activePlayer = self::PLAYER_TWO_SPACE;
            return;
        }
        $this->activePlayer = self::PLAYER_ONE_SPACE;
    }
    
     
    private function _initBoard() {
        for($i =0; $i < $this->rows; ++$i) {
            for($j = 0; $j < $this->columns; ++$j) {
                $this->board[$i][$j] = self::EMPTY_SPACE;
            }
        }
    }
    
    public function getBoard() {
        return $this->board;
    }
    
    public function __toString() {
        $str = '';
        $line = '';
        for($j = 0; $j < $this->columns ; $j ++ ) {
            $str .= " | " . $j;
            $line .= '----';
        }
        
        $str .= " | ";
        $str .= "\n ". $line . "-\n";  
        for($i = 0; $i < $this->rows ; $i ++ ){
            
            $str .= " | ";
            
            for($j = 0; $j < $this->columns ; $j ++ ){
                $str .= $this->board[$i][$j] . ' | ';
            }

            $str .= "\n";
        }
        return $str;        
    }
}