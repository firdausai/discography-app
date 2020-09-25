<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->nullable();
            $table->foreignId('song_title_id')->nullable();
            $table->foreignId('arranger_id')->nullable();
            $table->foreignId('singer_id')->nullable();
            $table->foreignId('band_id')->nullable();
            $table->foreignId('band_leader_id')->nullable();
            $table->string('audio_path', 255)->nullable();
        });

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('album_id')->references('id')->on('albums')->nullable();
            $table->foreign('song_title_id')->references('id')->on('song_titles')->nullable();
            $table->foreign('arranger_id')->references('id')->on('arrangers')->nullable();
            $table->foreign('singer_id')->references('id')->on('singers')->nullable();
            $table->foreign('band_id')->references('id')->on('bands')->nullable();
            $table->foreign('band_leader_id')->references('id')->on('band_leaders')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
}
