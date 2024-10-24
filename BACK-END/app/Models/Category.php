<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id']; 

    // A category has many documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // A category belongs to a member
    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }
}
