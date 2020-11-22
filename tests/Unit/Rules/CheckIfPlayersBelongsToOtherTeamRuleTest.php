<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\CheckIfPlayersBelongsToOtherTeamRule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Player;
class CheckIfPlayersBelongsToOtherTeamRuleTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testPassesTrue()
    {
        Player::whereIn('id',[1,2])->update(['team_id' => NULL]);

        $rule    = new CheckIfPlayersBelongsToOtherTeamRule(1);
        $retorno = $rule->passes('players_ids', [1,2]);

        $this->assertTrue($retorno);
    }

    public function testPassesFalse()
    {
        $rule    = new CheckIfPlayersBelongsToOtherTeamRule(1);
        $retorno = $rule->passes('players_ids', [1,2]);

        $this->assertFalse($retorno);
    }

    public function testMessage()
    {
        $request = new Request();
        $rule    = new CheckIfPlayersBelongsToOtherTeamRule($request);
        $retorno = $rule->message();

        $this->assertEquals('Players that belong to another teams are not allowed to be choosen.', $retorno);
    }
  
}
