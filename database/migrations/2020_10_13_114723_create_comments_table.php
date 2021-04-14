<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index();     //  Foreign Key
            $table->text('body');
            $table->morphs('commentable');
            $table->timestamps();

            //-----------------------------------------------------------------------------
            //  Here we set the foreign key relationship
            //  Cascade means if we delete the user then all the
            //  assosiated comments for that User should also be deleted
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
        Schema::dropIfExists('comments');
    }
}
