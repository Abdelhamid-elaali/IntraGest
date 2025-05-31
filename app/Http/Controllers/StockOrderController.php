<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockOrder;
use App\Models\StockOrderItem;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockOrder::with(['supplier', 'user', 'approvedBy']);
        
        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('order_date', '>=', Carbon::parse($request->date_from)->startOfDay());
        }
        
        if ($request->filled('date_to')) {
            $query->where('order_date', '<=', Carbon::parse($request->date_to)->endOfDay());
        }
        
        // Show overdue orders
        if ($request->has('overdue')) {
            $query->overdue();
        }
        
        // Get orders with pagination
        $orders = $query->latest('order_date')->paginate(10)->withQueryString();
        
        // Get all suppliers for filter dropdown
        $suppliers = Supplier::where('status', 'active')->get();
        
        // Get order status counts for the dashboard
        $pendingCount = StockOrder::where('status', 'pending')->count();
        $approvedCount = StockOrder::where('status', 'approved')->count();
        $deliveredCount = StockOrder::where('status', 'delivered')->count();
        $cancelledCount = StockOrder::where('status', 'cancelled')->count();
        
        // Get payment status counts
        $unpaidCount = StockOrder::where('payment_status', 'unpaid')->count();
        $partialCount = StockOrder::where('payment_status', 'partial')->count();
        $paidCount = StockOrder::where('payment_status', 'paid')->count();
        
        return view('stock-orders.index', compact(
            'orders', 
            'suppliers', 
            'pendingCount', 
            'approvedCount', 
            'deliveredCount', 
            'cancelledCount',
            'unpaidCount',
            'partialCount',
            'paidCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $stocks = Stock::with('supplier')->get();
        
        // Generate unique order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(StockOrder::count() + 1, 4, '0', STR_PAD_LEFT);
        
        return view('stock-orders.create', compact('suppliers', 'stocks', 'orderNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate order details
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'order_number' => 'required|string|max:50|unique:stock_orders',
            'reference_number' => 'nullable|string|max:50',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.stock_id' => 'required|exists:stocks,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Calculate total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity'] * $item['unit_price'];
        }
        
        DB::beginTransaction();
        
        try {
            // Create order
            $order = StockOrder::create([
                'supplier_id' => $request->supplier_id,
                'order_number' => $request->order_number,
                'reference_number' => $request->reference_number,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'notes' => $request->notes,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'payment_status' => 'unpaid',
                'user_id' => Auth::id(),
            ]);
            
            // Create order items
            foreach ($request->items as $item) {
                StockOrderItem::create([
                    'stock_order_id' => $order->id,
                    'stock_id' => $item['stock_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'received_quantity' => 0,
                    'notes' => $item['notes'] ?? null,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('stock-orders.index')
                ->with('success', 'Order created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to create order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = StockOrder::with(['supplier', 'user', 'approvedBy', 'items.stock'])->findOrFail($id);
        return view('stock-orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = StockOrder::with(['supplier', 'items.stock'])->findOrFail($id);
        
        // Only allow editing of pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('stock-orders.show', $order->id)
                ->with('error', 'Only pending orders can be edited.');
        }
        
        $suppliers = Supplier::where('status', 'active')->get();
        $stocks = Stock::with('supplier')->get();
        
        return view('stock-orders.edit', compact('order', 'suppliers', 'stocks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = StockOrder::findOrFail($id);
        
        // Only allow editing of pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('stock-orders.show', $order->id)
                ->with('error', 'Only pending orders can be edited.');
        }
        
        // Validate order details
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'order_number' => 'required|string|max:50|unique:stock_orders,order_number,' . $id,
            'reference_number' => 'nullable|string|max:50',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:stock_order_items,id',
            'items.*.stock_id' => 'required|exists:stocks,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Calculate total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity'] * $item['unit_price'];
        }
        
        DB::beginTransaction();
        
        try {
            // Update order
            $order->update([
                'supplier_id' => $request->supplier_id,
                'order_number' => $request->order_number,
                'reference_number' => $request->reference_number,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
            ]);
            
            // Get existing item IDs
            $existingItemIds = $order->items->pluck('id')->toArray();
            $updatedItemIds = [];
            
            // Update or create order items
            foreach ($request->items as $item) {
                if (isset($item['id']) && in_array($item['id'], $existingItemIds)) {
                    // Update existing item
                    $orderItem = StockOrderItem::find($item['id']);
                    $orderItem->update([
                        'stock_id' => $item['stock_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                    
                    $updatedItemIds[] = $item['id'];
                } else {
                    // Create new item
                    $orderItem = StockOrderItem::create([
                        'stock_order_id' => $order->id,
                        'stock_id' => $item['stock_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'received_quantity' => 0,
                        'notes' => $item['notes'] ?? null,
                    ]);
                    
                    $updatedItemIds[] = $orderItem->id;
                }
            }
            
            // Delete removed items
            $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
            if (!empty($itemsToDelete)) {
                StockOrderItem::whereIn('id', $itemsToDelete)->delete();
            }
            
            DB::commit();
            
            return redirect()->route('stock-orders.show', $order->id)
                ->with('success', 'Order updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = StockOrder::findOrFail($id);
        
        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('stock-orders.show', $order->id)
                ->with('error', 'Only pending orders can be deleted.');
        }
        
        DB::beginTransaction();
        
        try {
            // Delete order items first
            $order->items()->delete();
            
            // Delete the order
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('stock-orders.index')
                ->with('success', 'Order deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }
    
    /**
     * Approve an order
     */
    public function approve(string $id)
    {
        $order = StockOrder::findOrFail($id);
        
        // Check if order can be approved
        if ($order->status !== 'pending') {
            return redirect()->route('stock-orders.show', $order->id)
                ->with('error', 'Only pending orders can be approved.');
        }
        
        $order->approve(Auth::id());
        
        return redirect()->route('stock-orders.show', $order->id)
            ->with('success', 'Order approved successfully.');
    }
    
    /**
     * Mark an order as delivered
     */
    public function deliver(Request $request, string $id)
    {
        $order = StockOrder::with('items')->findOrFail($id);
        
        // Check if order can be delivered
        if ($order->status !== 'approved') {
            return redirect()->route('stock-orders.show', $order->id)
                ->with('error', 'Only approved orders can be marked as delivered.');
        }
        
        // Validate received quantities
        $validator = Validator::make($request->all(), [
            'delivery_date' => 'required|date',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:stock_order_items,id',
            'items.*.received_quantity' => 'required|integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Update received quantities for each item
            foreach ($request->items as $item) {
                $orderItem = StockOrderItem::find($item['id']);
                $orderItem->update([
                    'received_quantity' => $item['received_quantity'],
                ]);
                
                // Add received quantity to stock
                if ($item['received_quantity'] > 0) {
                    $stock = Stock::find($orderItem->stock_id);
                    $stock->addStock($item['received_quantity'], Auth::id(), 'Received from order #' . $order->order_number);
                }
            }
            
            // Mark order as delivered
            $order->deliver($request->delivery_date);
            
            DB::commit();
            
            return redirect()->route('stock-orders.show', $order->id)
                ->with('success', 'Order marked as delivered successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to mark order as delivered: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Cancel an order
     */
    public function cancel(Request $request, string $id)
    {
        $order = StockOrder::findOrFail($id);
        
        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'approved'])) {
            return redirect()->route('stock-orders.show', $order->id)
                ->with('error', 'Only pending or approved orders can be cancelled.');
        }
        
        // Validate cancellation reason
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $order->cancel($request->cancellation_reason);
        
        return redirect()->route('stock-orders.show', $order->id)
            ->with('success', 'Order cancelled successfully.');
    }
    
    /**
     * Update payment status
     */
    public function updatePayment(Request $request, string $id)
    {
        $order = StockOrder::findOrFail($id);
        
        // Validate payment details
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:unpaid,partial,paid',
            'payment_method' => 'required_if:payment_status,partial,paid|nullable|string|max:50',
            'payment_notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $order->update([
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'notes' => $request->filled('payment_notes') 
                ? ($order->notes ? $order->notes . "\n\nPayment: " . $request->payment_notes : "Payment: " . $request->payment_notes)
                : $order->notes,
        ]);
        
        return redirect()->route('stock-orders.show', $order->id)
            ->with('success', 'Payment status updated successfully.');
    }
}
