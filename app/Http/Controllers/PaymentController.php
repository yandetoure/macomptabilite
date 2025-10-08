<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with(['invoice', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['invoice', 'creator', 'journalEntries.lines.account']);

        return view('payments.show', compact('payment'));
    }
}
