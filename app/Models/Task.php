<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Auditable;
use App\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use Auditable, Sortable, HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'priority',
        'user_id'
    ];

    protected $casts = [
        'due_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereFullText(['title', 'description'], $search . '*', ['mode' => 'boolean']);
    }

    public function scopeFilterByStatus($query, $status)
    {
        return $query->when($status, function ($q) use ($status) {
            return $q->where('status', $status);
        });
    }

    public function scopeFilterByPriority($query, $priority)
    {
        return $query->when($priority, function ($q) use ($priority) {
            return $q->where('priority', $priority);
        });
    }

    public function scopeFilterByDueDate($query, $start, $end)
    {
        return $query->when($start && $end, function ($q) use ($start, $end) {
            return $q->whereBetween('due_date', [$start, $end]);
        });
    }
}
