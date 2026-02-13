<?php

use App\Models\Payment;
use App\Models\PhotoSession;

echo "--- DEBUG START ---\n";

// 1. Check raw successful payments
$successfulPaymentsCount = Payment::where('transaction_status', 'success')->count();
echo "Successful Payments (raw count): " . $successfulPaymentsCount . "\n";

$successfulPaymentsSum = Payment::where('transaction_status', 'success')->sum('amount');
echo "Successful Payments (raw sum): " . $successfulPaymentsSum . "\n";

// 2. Check PhotoSessions with successful payments
$sessionsWithPayment = PhotoSession::whereHas('payment', function ($q) {
    $q->where('transaction_status', 'success');
})->count();
echo "PhotoSessions with success payment: " . $sessionsWithPayment . "\n";

// 3. Inspect a few payments to see actual status
$samplePayments = Payment::latest()->take(5)->get();
echo "\nLatest 5 Payments:\n";
foreach ($samplePayments as $p) {
    echo "ID: {$p->id}, Status: '{$p->transaction_status}', Amount: {$p->amount}\n";
}

// 4. Test the reduction logic on a small subset
$testSessions = PhotoSession::with(['payment', 'finalImage', 'machine'])
    ->whereHas('payment', function ($q) {
        $q->where('transaction_status', 'PAID');
    })
    ->limit(5)
    ->get();

echo "\nTesting Reduction Logic on " . $testSessions->count() . " sessions:\n";
$calculated = $testSessions->reduce(function ($carry, $session) {
    $amount = $session->payment->amount ?? 0;
    $printQty = $session->finalImage->print_quantity ?? 1;
    $extra = max(0, $printQty - 1);
    $cost = $session->machine->additional_print_cost ?? 0;

    $total = $amount + ($extra * $cost);
    echo "Session {$session->id}: Base={$amount}, ExtraPrints={$extra}, Cost={$cost}, Total={$total}\n";
    return $carry + $total;
}, 0);

echo "Calculated Sample Revenue: " . $calculated . "\n";
echo "--- DEBUG END ---\n";
