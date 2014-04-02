<?php

class Aggressive extends ConnectFourStrategy
{
    private $opponent;
    public function makeMove(ConnectFourGame $game) {
        $this->_setOpponent();
        
        $scores = array();
        $maxScore = 0;
        $move = (int)($game->getColumns() / 2);
        
        for($i = 0; $i < $game->getColumns(); ++ $i) {
            if($game->getNextAvailableRow($i) !== false) { 
                $scores[$i] = $this->_calcScore($i, $game);
                if($scores[$i] > $maxScore) {
                    $maxScore = $scores[$i];
                    $move = $i;
                }
            }
        }
        
        print_r($scores);
        
        return $game->makeMove($this->player, $move);
        
    }
    
    private function _calcScore($column, ConnectFourGame $game) {
        return $this->_calcHorizontalValue($column, $game) + 
                $this->_calcVerticalValue($column, $game) +
                $this->_calcLeftDiagnalValue($column, $game) +
                $this->_calcRightDiagnalValue($column, $game);
    }
    
    private function _setOpponent() {
        if($this->player == ConnectFourGame::PLAYER_ONE_SPACE) {
            $this->opponent = ConnectFourGame::PLAYER_TWO_SPACE;
        } else {
            $this->opponent = ConnectFourGame::PLAYER_ONE_SPACE;
        }        
    }
    
    private function _calcHorizontalValue($column, ConnectFourGame $game) {
        $row = $game->getNextAvailableRow($column);
        
        $board = $game->getBoard();
        
        $count = 0;

        /* check to the left */
        for($c = $column-1; $game->_inBounds($row, $c); --$c) {
            if($board[$row][$c] !== $this->player) {
                break;
            }
            ++ $count;
        }
        
        /* check to the right */
        for($c = $column+1; $game->_inBounds($row, $c); ++$c) {
            if($board[$row][$c] !== $this->player) {
                break;
            }
            ++ $count;
        }
        
//        echo "horiz score cnt: " . $count . "\n";
        if($count == 0) return 0;
        return pow(2, $count);     
    }
    
    private function _calcVerticalValue($column,ConnectFourGame $game) {
        $row = $game->getNextAvailableRow($column);
        
        $board = $game->getBoard();
        
        $count = 0;

        /* check down */
        for($r = $row +1; $game->_inBounds($r, $column); ++$r) {
            if($board[$r][$column] === $this->player) {
                ++ $count;
            } else {
                break;
            }
        }
        
//        echo "vert score cnt: " . $count . "\n";
        if($count == 0) return 0;
        return pow(2, $count);         
        
    }
    
    private function _calcLeftDiagnalValue($column,ConnectFourGame $game) {
        
        $row = $game->getNextAvailableRow($column);
        
        $board = $game->getBoard();        
        
        $count = 0;

        /* check upleft */
        $r = $row - 1;
        $c = $column - 1;
        while($game->_inBounds($r, $c)) {
            if($board[$r][$c] !== $this->player) {
                break;
            }     
            ++ $count;
            --$r;
            --$c;            
        }
        
        /* check to the downright */
        $r = $row + 1;
        $c = $column + 1;
        while($game->_inBounds($r, $c)) {
            if($board[$r][$c] !== $this->player) {
                break;
            }      
            ++ $count;
            ++$r;
            ++$c;            
        }
        
        if($count == 0) return 0;
        return pow(2, $count);       
    }
    
    private function _calcRightDiagnalValue($column,ConnectFourGame $game) {
        
        $row = $game->getNextAvailableRow($column);
        
        $board = $game->getBoard();         
        
        $count = 0;

        /* check upright */
        $r = $row - 1;
        $c = $column + 1;
        while($game->_inBounds($r, $c)) {
            if($board[$r][$c] !== $this->player) {
                break;
            }    
            ++ $count;
            --$r;
            ++$c;            
        }
        
        /* check to the downleft */
        $r = $row + 1;
        $c = $column - 1;
        while($game->_inBounds($r, $c)) {
            if($board[$r][$c] !== $this->player) {
                break;
            }   
            ++ $count;
            ++$r;
            --$c;            
        }
        
        
        if($count == 0) return 0;
        return pow(2, $count);         
    }
}
