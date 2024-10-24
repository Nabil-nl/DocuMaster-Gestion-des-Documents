<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'file_path', 'file_type', 'category_id', 'user_id'];

    // A document belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // A document belongs to a member
    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
