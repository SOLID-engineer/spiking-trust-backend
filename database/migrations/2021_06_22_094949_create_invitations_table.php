<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 64);
            $table->string('reference_number');
            $table->string('status',32)->default(\App\Models\Invitation::STATUS_QUEUED);
            $table->string('type',32);
            $table->string('subject');
            $table->mediumText('body');
            $table->string('sender_name');
            $table->string('sender_email', 64);
            $table->string('reply_to_email', 64);
            $table->timestamp('sent_at');
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
        Schema::dropIfExists('invitations');
    }
}
