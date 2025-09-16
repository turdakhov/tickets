<?php

use App\Enums\ChannelEnum;
use App\Enums\StatusEnum;
use App\Models\User;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->enum('channel', ChannelEnum::cases());
            $table->string('category_slug')->references('slug')->on('categories');
            $table->string('subject', 2000);
            $table->enum('status', array_column(StatusEnum::cases(), 'name'))->default(StatusEnum::New->name);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
