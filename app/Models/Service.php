<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'cover_image',
        'gallery',
        'features',
        'deliverables',
        'technologies',
        'starting_price',
        'price_note',
        'delivery_range',
        'faqs',
        'is_featured',
        'status',
        'sort_order',
        'seo_title',
        'seo_description',
        'seo_image',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'features' => 'array',
            'deliverables' => 'array',
            'technologies' => 'array',
            'faqs' => 'array',
            'starting_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(ServiceInquiry::class);
    }
}
