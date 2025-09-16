<?php

use App\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ticket::class);
            $table->foreignId('sender_id')->nullable()->constrained('users');
            $table->string('text', 2000);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
