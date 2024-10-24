<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Document title
            $table->string('file_path'); // Path to the stored file
            $table->string('file_type'); // File type (e.g., pdf, docx, etc.)
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Link to category
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who uploaded the document
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
}
