<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'address',
        'tax_id',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Méthodes utiles
    public function getTotalInvoices()
    {
        return $this->invoices()->sum('total_amount');
    }

    public function getPendingAmount()
    {
        return $this->invoices()
            ->whereIn('status', ['pending', 'partial'])
            ->sum('total_amount') - $this->invoices()
            ->whereIn('status', ['pending', 'partial'])
            ->sum('paid_amount');
    }
}
