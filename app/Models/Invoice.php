<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'type',
        'customer_id',
        'supplier_id',
        'invoice_date',
        'due_date',
        'party_name',
        'description',
        'total_amount',
        'paid_amount',
        'status',
        'file_path',
        'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    // Relations
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    // Accesseur pour obtenir le nom de la partie (client ou fournisseur)
    public function getPartyNameAttribute($value)
    {
        if ($this->type === 'customer' && $this->customer) {
            return $this->customer->name;
        }
        if ($this->type === 'supplier' && $this->supplier) {
            return $this->supplier->name;
        }
        return $value;
    }

    // Méthodes utiles
    public function getRemainingAmount()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function isFullyPaid()
    {
        return $this->paid_amount >= $this->total_amount;
    }
}
