<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    protected $fillable = [
        'entry_number',
        'entry_date',
        'reference',
        'description',
        'status',
        'is_balanced',
        'created_by',
        'invoice_id',
        'payment_id',
        'payroll_id',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'is_balanced' => 'boolean',
    ];

    // Relations
    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    // Méthodes utiles
    public function getTotalDebit()
    {
        return $this->lines()->sum('debit');
    }

    public function getTotalCredit()
    {
        return $this->lines()->sum('credit');
    }

    public function checkBalance()
    {
        return $this->getTotalDebit() == $this->getTotalCredit();
    }
}
