<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_inquiry_id',
        'user_id',
        'type',
        'note',
    ];

    /**
     * @return array<string, string>
     */
    public static function typeOptions(): array
    {
        return [
            'note' => 'Note',
            'call' => 'Call',
            'email' => 'Email',
            'meeting' => 'Meeting',
            'status_change' => 'Status change',
        ];
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(ServiceInquiry::class, 'service_inquiry_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
