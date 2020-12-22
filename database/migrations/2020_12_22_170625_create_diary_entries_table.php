<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiaryEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diary_entries', function (Blueprint $table) {
            $table->uuid('id')->primary(); //change!!! change!!!
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('title');
            $table->string('content');
            //$table->string('tag_id')->references('id')->on('tags');
            //just call the entry_tag table for tags^
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diary_entries');
    }
}
