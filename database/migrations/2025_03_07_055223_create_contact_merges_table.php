<?php

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
        Schema::create('contact_merges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_contact_id'); // Main contact after merging
            $table->unsignedBigInteger('merged_contact_id'); // Contact that was merged
            $table->timestamps();

            $table->foreign('master_contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('merged_contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_merges');
    }
};
