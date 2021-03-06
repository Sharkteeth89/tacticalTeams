<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name','100')->unique();

            $table->foreignId('mission_id')->nullable()->constained();
            $table->foreignId('leader_id')->nullable()->constained(); //leader
            $table->timestamps();
        });
        Schema::table('soldiers', function (Blueprint $table){

            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');

        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('soldiers', function (Blueprint $table){

            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');

        });

        Schema::dropIfExists('teams');
    }
}
