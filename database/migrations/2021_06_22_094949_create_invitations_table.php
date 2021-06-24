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
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('template_id')->constrained('mail_templates');
            $table->string('name');
            $table->string('email', 64);
            $table->string('reference_number');
            $table->string('status',32)->default(\App\Models\Invitation::STATUS_QUEUED);
            $table->string('type',32);
            $table->string('sender_name');
            $table->string('sender_email', 64);
            $table->string('reply_to_email', 64);
            $table->timestamp('sent_at')->nullable();
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
