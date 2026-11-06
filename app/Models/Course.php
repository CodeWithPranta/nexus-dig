<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'thumbnail',
        'cover_image',
        'promo_video_url',
        'language',
        'difficulty',
        'duration_hours',
        'learning_objectives',
        'prerequisites',
        'target_audience',
        'price',
        'discounted_price',
        'currency',
        'status',
        'is_featured',
        'enrollment_count',
        'seo_title',
        'seo_description',
        'seo_image',
        'published_at',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'learning_objectives' => 'array',
            'prerequisites' => 'array',
            'target_audience' => 'array',
            'price' => 'decimal:2',
            'discounted_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'enrollment_count' => 'integer',
            'duration_hours' => 'integer',
            'published_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Pending review',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function difficultyOptions(): array
    {
        return [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_instructor')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class)->orderBy('sort_order');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, CourseSection::class);
    }

    public function classSessions(): HasMany
    {
        return $this->hasMany(ClassSession::class);
    }

    public function recordings(): HasMany
    {
        return $this->hasMany(Recording::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function hasInstructor(User $user): bool
    {
        return $this->instructors()->whereKey($user->getKey())->exists();
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->discounted_price ?? $this->price);
    }
}
