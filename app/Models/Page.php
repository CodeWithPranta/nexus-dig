<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'seo_title',
        'seo_description',
        'seo_image',
    ];

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'published' => 'Published',
        ];
    }
}
