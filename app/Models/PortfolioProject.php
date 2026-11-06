<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_category_id',
        'title',
        'slug',
        'description',
        'featured_image',
        'gallery',
        'tech_stack',
        'project_url',
        'status',
        'is_featured',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'tech_stack' => 'array',
            'is_featured' => 'boolean',
            'completed_at' => 'date',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
        ];
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
