<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'image',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A member can have many categories
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // A member can have many documents
    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');

    }
}
