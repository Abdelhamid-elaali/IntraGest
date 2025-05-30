@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Payment Details</h2>
            <div class="flex space-x-2">
                <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Back to List
                </a>
                <button onclick="printInvoice()" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Invoice
                </button>
            </div>
        </div>

        <div class="mb-8">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Payment Status: 
                            <span class="font-medium 
                                @if($payment->status === 'completed') text-green-700
                                @elseif($payment->status === 'cancelled') text-red-700
                                @else text-yellow-700 @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                            @if($payment->transaction_id)
                                | Transaction ID: <span class="font-medium">{{ $payment->transaction_id }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="invoice" class="border border-gray-200 rounded-lg p-8 mb-6">
            <!-- Invoice Header -->
            <div class="flex justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">INVOICE</h1>
                    <p class="text-gray-600">Invoice #: INV-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-gray-600">Date: {{ $payment->payment_date->format('M d, Y') }}</p>
                    <p class="text-gray-600">Due Date: {{ $payment->due_date ? $payment->due_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <img src="{{ asset('images/logo.png') }}" alt="IntraGest Logo" class="h-16 mb-2">
                    <h2 class="text-xl font-semibold text-gray-800">IntraGest</h2>
                    <p class="text-gray-600">123 Training Center St.</p>
                    <p class="text-gray-600">Casablanca, Morocco</p>
                    <p class="text-gray-600">contact@intraingest.ma</p>
                </div>
            </div>

            <!-- Client Information -->
            <div class="mb-8">
                <h3 class="text-gray-600 font-semibold mb-2">Bill To:</h3>
                <p class="font-semibold text-gray-800">{{ $payment->user->name }}</p>
                <p class="text-gray-600">{{ $payment->user->email }}</p>
                @if($payment->user->phone)
                    <p class="text-gray-600">{{ $payment->user->phone }}</p>
                @endif
                @if($payment->payment_type === 'trainee' && isset($payment->user->student))
                    <p class="text-gray-600">Student ID: {{ $payment->user->student->student_id }}</p>
                @endif
            </div>

            <!-- Payment Details -->
            <div class="mb-8">
                <h3 class="text-gray-600 font-semibold mb-4">Payment Details:</h3>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payment->items as $item)
                            <tr>
                                <td class="py-3 px-4 border-b border-gray-200">{{ $item->description }}</td>
                                <td class="py-3 px-4 border-b border-gray-200">{{ ucfirst($item->category ?? 'N/A') }}</td>
                                <td class="py-3 px-4 border-b border-gray-200 text-right">{{ number_format($item->amount, 2) }} DH</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="py-3 px-4 text-right font-semibold">Total:</td>
                            <td class="py-3 px-4 text-right font-bold">{{ number_format($payment->amount, 2) }} DH</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Method -->
            <div class="mb-8">
                <h3 class="text-gray-600 font-semibold mb-2">Payment Method:</h3>
                <p class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
            </div>

            <!-- Notes -->
            <div class="mb-8">
                <h3 class="text-gray-600 font-semibold mb-2">Notes:</h3>
                <p class="text-gray-800">{{ $payment->description }}</p>
            </div>

            <!-- Bank Details for Bank Transfer -->
            @if($payment->payment_method === 'bank_transfer')
                <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <h3 class="text-gray-600 font-semibold mb-2">Bank Details:</h3>
                    <p class="text-gray-800">Bank: BMCE Bank</p>
                    <p class="text-gray-800">Account Name: IntraGest Training Center</p>
                    <p class="text-gray-800">Account Number: 011 810 000123456789</p>
                    <p class="text-gray-800">SWIFT/BIC: BMCEXXXXX</p>
                    <p class="text-gray-800 mt-2 font-semibold">Please include your Invoice # as reference</p>
                </div>
            @endif

            <!-- Thank You Message -->
            <div class="text-center mt-8 pt-8 border-t border-gray-200">
                <p class="text-gray-600">Thank you for your business!</p>
                <p class="text-gray-500 text-sm mt-2">For any questions regarding this invoice, please contact our finance department at finance@intraingest.ma</p>
            </div>
        </div>

        @if($payment->status === 'pending')
            <div class="flex justify-end space-x-4 mt-6">
                <form action="{{ route('payments.process', $payment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Process Payment
                    </button>
                </form>
                <form action="{{ route('payments.cancel', $payment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to cancel this payment?')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel Payment
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function printInvoice() {
        const printContents = document.getElementById('invoice').innerHTML;
        const originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <div style="padding: 20px;">
                ${printContents}
            </div>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
</script>
@endsection
