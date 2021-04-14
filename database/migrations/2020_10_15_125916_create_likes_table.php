<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index();     //  Foreign Key
            $table->morphs('likeable');
            $table->timestamps();

            //-----------------------------------------------------------------------------
            //  Here we set the foreign key relationship
            //  Cascade means if we delete the user then all the
            //  assosiated likes for that User should also be deleted
            //
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            //-----------------------------------------------------------------------------
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
