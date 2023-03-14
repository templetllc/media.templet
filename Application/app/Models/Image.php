<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'image_id',
        'image_path',
        'image_size',
        'width',
        'height',
        'method',
        'preset',
        'preset_id',
        'description',
        'category',
        'tags',
        'image_parent',
        'thumbnail',
        'gallery',
        'active',
        'approval'
    ];

    /**
     * Get the user for image.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function makeThumbnail($filename){

        
    }
}
