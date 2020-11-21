<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
                ['name' => 'Red',    'points' => 2], 
                ['name' => 'Yellow', 'points' => 1]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Card::truncate();
    }
}
