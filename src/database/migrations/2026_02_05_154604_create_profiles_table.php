<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('profiles', function (Blueprint $table) {
        $table->id();

        // userとの1対1
        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete()
              ->unique();

        $table->string('name');                 // NOT NULL
        $table->string('profile_image')->nullable(); // NULL OK
        $table->string('postal_code', 8);       // NOT NULL
        $table->text('address');                // NOT NULL
        $table->text('building')->nullable();   // NULL OK

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
        Schema::dropIfExists('profiles');
    }
}
