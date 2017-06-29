<?php
/**
 * Created by PhpStorm.
 * User: fsalles
 * Date: 29/06/17
 * Time: 07:36
 */

namespace Trivia;

use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testRollMethod()
    {
        $game = $this->initializeGameForRollMethod();
        $game->roll(1);

        $this->assertEquals(0, $game->currentPlayer);
        $this->assertEquals(1, $game->places[$game->currentPlayer]);
    }
    
    public function testRollGettingOutOfPenaltyBox()
    {
        $game = $this->initializeGameForRollMethod();
        $game->currentPlayer = 2;
        $game->inPenaltyBox[$game->currentPlayer] = true;
        $game->roll(1);

        $this->assertTrue($game->isGettingOutOfPenaltyBox);
        $this->assertEquals(1, $game->places[$game->currentPlayer]);
    }

    public function testRollStayInPenaltyBox()
    {
        $game = $this->initializeGameForRollMethod();
        $game->currentPlayer = 0;
        $game->inPenaltyBox[$game->currentPlayer] = true;
        $game->roll(4);

        $this->assertFalse($game->isGettingOutOfPenaltyBox);
        $this->assertEquals(0, $game->places[$game->currentPlayer]);
    }
    
    public function testRollResetPlaces()
    {
        $game = $this->initializeGameForRollMethod();
        $game->currentPlayer = 0;
        $game->places[$game->currentPlayer] = 11;
        $game->roll(1);

        $this->assertEquals(0, $game->places[$game->currentPlayer]);
    }

    /**
     * On initialise Game avec :
     * un stub pour la méthode currentCategory
     * un fake pour la méthode askQuestion
     * @return Game
     */
    private function initializeGameForRollMethod() : Game
    {
        $game = $this->getMockBuilder(Game::class)
            ->setMethods(['currentCategory', 'askQuestion'])
            ->getMock();

        $game->method('currentCategory')->willReturn('Science');
        $game->method('askQuestion')->will($this->returnCallback(function() use ($game) {
            /** @var Game $game */
            array_shift($game->scienceQuestions);
        }));

        $game->add("Chet");
        $game->add("Pat");
        $game->add("Sue");

        return $game;
    }
}
