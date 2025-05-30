<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'items'])->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        return view('payments.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string|in:trainee,supplier,other',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'description' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'student_id' => 'required_if:payment_type,trainee',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.category' => 'required|string',
        ]);

        $payment = new Payment();
        $payment->user_id = $request->payment_type === 'trainee' && $request->student_id 
            ? Student::findOrFail($request->student_id)->user_id 
            : auth()->id();
        $payment->amount = $validated['amount'];
        $payment->payment_type = $validated['payment_type'];
        $payment->payment_date = $validated['payment_date'];
        $payment->payment_method = $validated['payment_method'];
        $payment->description = $validated['description'];
        $payment->status = 'pending';
        $payment->due_date = $validated['due_date'] ?? Carbon::now()->addDays(30);
        $payment->save();

        foreach ($validated['items'] as $item) {
            $payment->items()->create([
                'description' => $item['description'],
                'amount' => $item['amount'],
                'category' => $item['category'] ?? 'other'
            ]);
        }

        // Send notification to the user if it's a trainee payment
        if ($payment->payment_type === 'trainee') {
            // In a real application, you would send an email notification here
            // Notification::send($payment->user, new PaymentCreatedNotification($payment));
        }

        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $students = Student::with('user')->get();
        return view('payments.edit', compact('payment', 'students'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_type' => 'required|string|in:trainee,supplier,other',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'description' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'student_id' => 'required_if:payment_type,trainee',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.category' => 'required|string',
        ]);

        // Only update user_id if payment type is trainee and we have a student_id
        if ($request->payment_type === 'trainee' && $request->student_id) {
            $payment->user_id = Student::findOrFail($request->student_id)->user_id;
        }

        $payment->update([
            'amount' => $validated['amount'],
            'payment_type' => $validated['payment_type'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'] ?? $payment->due_date,
        ]);

        // Update payment items
        $payment->items()->delete();
        foreach ($validated['items'] as $item) {
            $payment->items()->create([
                'description' => $item['description'],
                'amount' => $item['amount'],
                'category' => $item['category'] ?? 'other'
            ]);
        }

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->items()->delete();
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    /**
     * Process a payment
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function process(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'This payment cannot be processed because its status is ' . $payment->status);
        }

        // In a real application, you would integrate with a payment gateway here
        // For now, we'll just mark the payment as completed
        $payment->markAsCompleted('TRANS-' . strtoupper(uniqid()), auth()->id());

        // Generate and send invoice
        // In a real application, you would generate a PDF invoice and send it via email
        // $invoice = PDF::loadView('payments.invoice', compact('payment'));
        // Mail::to($payment->user->email)->send(new InvoiceMail($payment, $invoice));

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment processed successfully.');
    }

    /**
     * Cancel a payment
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function cancel(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'This payment cannot be cancelled because its status is ' . $payment->status);
        }

        $payment->markAsFailed('Cancelled by ' . auth()->user()->name);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment cancelled successfully.');
    }

    /**
     * Display payment analytics
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function analytics(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $paymentType = $request->input('payment_type');
        $status = $request->input('status');

        $query = Payment::whereBetween('payment_date', [$startDate, $endDate]);

        if ($paymentType) {
            $query->where('payment_type', $paymentType);
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Get statistics
        $totalAmount = $query->sum('amount');
        $paymentCount = $query->count();

        $completedQuery = clone $query;
        $completedAmount = $completedQuery->where('status', 'completed')->sum('amount');
        $completedCount = $completedQuery->where('status', 'completed')->count();

        $pendingQuery = clone $query;
        $pendingAmount = $pendingQuery->where('status', 'pending')->sum('amount');
        $pendingCount = $pendingQuery->where('status', 'pending')->count();

        $overdueQuery = clone $query;
        $overduePayments = $overdueQuery->where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->get();
        $overdueAmount = $overduePayments->sum('amount');
        $overdueCount = $overduePayments->count();

        // Get recent payments
        $recentPayments = Payment::with('user')->latest()->take(10)->get();

        // Prepare monthly chart data
        $monthlyData = Payment::selectRaw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(amount) as total, status')
            ->whereBetween('payment_date', [Carbon::parse($startDate)->startOfMonth(), Carbon::parse($endDate)->endOfMonth()])
            ->groupBy('year', 'month', 'status')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlyLabels = [];
        $monthlyTotals = [];
        $monthlyCompleted = [];

        // Process monthly data
        $startMonth = Carbon::parse($startDate)->startOfMonth();
        $endMonth = Carbon::parse($endDate)->endOfMonth();
        $currentMonth = $startMonth->copy();

        while ($currentMonth->lte($endMonth)) {
            $monthLabel = $currentMonth->format('M Y');
            $monthlyLabels[] = $monthLabel;

            $monthTotal = $monthlyData->filter(function ($item) use ($currentMonth) {
                return $item->year == $currentMonth->year && $item->month == $currentMonth->month;
            })->sum('total');
            $monthlyTotals[] = $monthTotal;

            $monthCompleted = $monthlyData->filter(function ($item) use ($currentMonth) {
                return $item->year == $currentMonth->year && $item->month == $currentMonth->month && $item->status == 'completed';
            })->sum('total');
            $monthlyCompleted[] = $monthCompleted;

            $currentMonth->addMonth();
        }

        // Prepare payment type chart data
        $paymentTypeData = Payment::selectRaw('payment_type, SUM(amount) as total')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('payment_type')
            ->get();

        $paymentTypeLabels = [];
        $paymentTypeValues = [];

        foreach ($paymentTypeData as $data) {
            $paymentTypeLabels[] = ucfirst($data->payment_type);
            $paymentTypeValues[] = $data->total;
        }

        return view('payments.analytics', compact(
            'totalAmount', 'paymentCount',
            'completedAmount', 'completedCount',
            'pendingAmount', 'pendingCount',
            'overdueAmount', 'overdueCount',
            'overduePayments', 'recentPayments',
            'monthlyLabels', 'monthlyTotals', 'monthlyCompleted',
            'paymentTypeLabels', 'paymentTypeValues'
        ));
    }

    /**
     * Send a payment reminder
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function sendReminder(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This payment cannot be reminded because its status is ' . $payment->status
            ]);
        }

        // In a real application, you would send an email reminder here
        // Mail::to($payment->user->email)->send(new PaymentReminderMail($payment));

        return response()->json([
            'success' => true,
            'message' => 'Payment reminder sent successfully.'
        ]);
    }
}
