<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'stock',
        'images',
        'category_id',
        'user_id'
    ];

    // Relaciones

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
