@extends('layouts.app')

@section('title', 'Create Payment')

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="p-6 border-b border-gray-200">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Record New Payment</h2>
            <p class="text-gray-600">Enter payment details below</p>
        </div>

        <form action="{{ route('payments.store') }}" method="POST" id="payment-form">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                    <select id="payment_type" name="payment_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Type</option>
                        <option value="trainee">Trainee Payment</option>
                        <option value="supplier">Supplier Payment</option>
                        <option value="other">Other</option>
                    </select>
                    @error('payment_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="trainee-selection" class="hidden">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Select Trainee</label>
                    <select id="student_id" name="student_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Select Trainee</option>
                        <!-- This would be populated from the database -->
                        <option value="1">John Doe (ID: T001)</option>
                        <option value="2">Jane Smith (ID: T002)</option>
                        <option value="3">Michael Johnson (ID: T003)</option>
                    </select>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (DH)</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Method</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                    <input type="date" id="payment_date" name="payment_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ date('Y-m-d') }}" required>
                    @error('payment_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                    @error('due_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4 mb-4">
                <h3 class="text-lg font-medium text-gray-800 mb-2">Payment Items</h3>
                <div id="payment-items">
                    <div class="payment-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-3 border border-gray-200 rounded-md">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input type="text" name="items[0][description]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (DH)</label>
                            <input type="number" name="items[0][amount]" step="0.01" min="0" class="item-amount w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="items[0][category]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="tuition">Tuition Fee</option>
                                <option value="materials">Learning Materials</option>
                                <option value="activities">Activities</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="remove-item text-red-500 hover:text-red-700 mt-4" style="display: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="add-item" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Item
                </button>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>    
                Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Save Payment
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentTypeSelect = document.getElementById('payment_type');
        const traineeSelection = document.getElementById('trainee-selection');
        const addItemBtn = document.getElementById('add-item');
        const paymentItemsContainer = document.getElementById('payment-items');
        const amountInput = document.getElementById('amount');
        let itemCounter = 1;

        // Show/hide trainee selection based on payment type
        paymentTypeSelect.addEventListener('change', function() {
            if (this.value === 'trainee') {
                traineeSelection.classList.remove('hidden');
            } else {
                traineeSelection.classList.add('hidden');
            }
        });

        // Add new payment item
        addItemBtn.addEventListener('click', function() {
            const newItem = document.createElement('div');
            newItem.className = 'payment-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-3 border border-gray-200 rounded-md';
            newItem.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="items[${itemCounter}][description]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount (DH)</label>
                    <input type="number" name="items[${itemCounter}][amount]" step="0.01" min="0" class="item-amount w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="items[${itemCounter}][category]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="tuition">Tuition Fee</option>
                        <option value="materials">Learning Materials</option>
                        <option value="activities">Activities</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-item text-red-500 hover:text-red-700 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            `;
            paymentItemsContainer.appendChild(newItem);
            
            // Show remove button for first item
            if (itemCounter === 1) {
                document.querySelector('.remove-item').style.display = 'block';
            }
            
            // Add event listener to the new remove button
            newItem.querySelector('.remove-item').addEventListener('click', function() {
                newItem.remove();
                updateTotalAmount();
            });
            
            // Add event listener to the new amount input
            newItem.querySelector('.item-amount').addEventListener('input', updateTotalAmount);
            
            itemCounter++;
        });

        // Remove payment item
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                e.target.closest('.payment-item').remove();
                updateTotalAmount();
            }
        });

        // Update total amount when item amounts change
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('item-amount')) {
                updateTotalAmount();
            }
        });

        // Calculate and update total amount
        function updateTotalAmount() {
            const itemAmounts = document.querySelectorAll('.item-amount');
            let total = 0;
            
            itemAmounts.forEach(function(input) {
                const value = parseFloat(input.value) || 0;
                total += value;
            });
            
            amountInput.value = total.toFixed(2);
        }
    });
</script>
@endsection
