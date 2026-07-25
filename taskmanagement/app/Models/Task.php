<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'attachment',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    const STATUS_PENDING     = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED   = 'completed';

    const PRIORITY_LOW    = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH   = 'high';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING     => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED   => 'Completed',
        ];
    }

    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW    => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH   => 'High',
        ];
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING     => 'badge bg-warning text-dark',
            self::STATUS_IN_PROGRESS => 'badge bg-primary',
            self::STATUS_COMPLETED   => 'badge bg-success',
            default                  => 'badge bg-secondary',
        };
    }

    public function getPriorityBadgeClass(): string
    {
        return match ($this->priority) {
            self::PRIORITY_HIGH   => 'badge bg-danger',
            self::PRIORITY_MEDIUM => 'badge bg-warning text-dark',
            self::PRIORITY_LOW    => 'badge bg-info text-dark',
            default               => 'badge bg-secondary',
        };
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== self::STATUS_COMPLETED;
    }
}
