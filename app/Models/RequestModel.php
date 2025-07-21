<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'branch_id',
        'date',
        'unit',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'unit' => 'integer',
    ];

    // Relationship dengan Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // Scope untuk status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk branch
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // Scope untuk tanggal
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

   

    // Accessor untuk format tanggal Indonesia
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d/m/Y');
    }

    // Accessor untuk badge status
    public function getStatusBadgeAttribute()
    {
        $badgeClass = match($this->status) {
            'Menunggu' => 'bg-warning',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            'Diproses' => 'bg-info',
            default => 'bg-secondary'
        };
        return '<span class="badge ' . $badgeClass . '">' . $this->status . '</span>';
    }
}