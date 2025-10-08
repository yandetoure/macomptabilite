<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $fillable = [
        'payroll_number',
        'employee_id',
        'pay_period_start',
        'pay_period_end',
        'payment_date',
        'gross_salary',
        'social_contributions',
        'taxes',
        'other_deductions',
        'net_salary',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'payment_date' => 'date',
        'gross_salary' => 'decimal:2',
        'social_contributions' => 'decimal:2',
        'taxes' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    // Relations
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'payroll_id');
    }

    // Méthodes utiles
    public function getTotalDeductions(): float
    {
        return (float)($this->social_contributions + $this->taxes + $this->other_deductions);
    }

    public function calculateNetSalary(): float
    {
        return (float)($this->gross_salary - $this->getTotalDeductions());
    }
}
