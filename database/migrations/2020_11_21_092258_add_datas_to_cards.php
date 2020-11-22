<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Card;
class AddDatasToCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Card::insert(
            [
                [
                    'name'   => 'Yellow', 
                    'points' => 1
                ],
                [
                    'name'   => 'Red',    
                    'points' => 2
                ],
            ]
        );
    }

}
