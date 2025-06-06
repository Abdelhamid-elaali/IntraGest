<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['supplier']);
        
        // Apply filters if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        // Department filtering removed
        
        if ($request->filled('status')) {
            if ($request->status === 'low') {
                $query->lowStock();
            } elseif ($request->status === 'critical') {
                $query->criticalStock();
            } elseif ($request->status === 'expired') {
                $query->where('expiry_date', '<', now());
            }
        }
        
        $stocks = $query->latest()->paginate(10)->withQueryString();
        
        // Get categories for filter dropdowns
        $categories = Stock::select('category')->distinct()->pluck('category');
        $suppliers = Supplier::all();
        
        // Get subcategories if they exist in the database
        $subcategories = Stock::select('subcategory')->whereNotNull('subcategory')->distinct()->pluck('subcategory');
        return view('stocks.index', compact('stocks', 'categories', 'subcategories', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('stocks.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'maximum_quantity' => 'required|integer|min:1',
            'minimum_quantity' => 'required|integer|min:0',
            'unit_type' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);
        
        // Generate a code if not in the database yet
        $validated['code'] = strtoupper(substr(str_replace(' ', '', $validated['name']), 0, 3) . '-' . rand(1000, 9999));

        $stock = Stock::create($validated);

        // Create initial stock transaction
        StockTransaction::create([
            'stock_id' => $stock->id,
            'type' => 'initial',
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'user_id' => auth()->id(),
            'notes' => 'Initial stock entry',
            'reference_number' => 'INIT-' . $stock->code . '-' . time(),
            'transaction_date' => now(),
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock item created successfully.');
    }

    public function show(Stock $stock)
    {
        $stock->load(['supplier', 'transactions.user']);
        
        // Get transaction history with pagination
        $transactions = $stock->transactions()->with('user')->latest()->paginate(10);
        
        // Calculate stock level percentage for progress bar
        $stockPercentage = $stock->stock_percentage;
        
        return view('stocks.show', compact('stock', 'transactions', 'stockPercentage'));
    }

    public function edit(Stock $stock)
    {
        $suppliers = Supplier::all();
        return view('stocks.edit', compact('stock', 'suppliers'));
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'maximum_quantity' => 'required|integer|min:1',
            'minimum_quantity' => 'required|integer|min:0',
            'unit_type' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        // If unit price changed, record it in the transaction history
        $priceChanged = $stock->unit_price != $validated['unit_price'];
        
        $stock->update($validated);
        
        if ($priceChanged) {
            StockTransaction::create([
                'stock_id' => $stock->id,
                'type' => 'price_change',
                'quantity' => 0,
                'unit_price' => $validated['unit_price'],
                'user_id' => auth()->id(),
                'notes' => 'Price updated from ' . $stock->getOriginal('unit_price') . ' to ' . $validated['unit_price'],
                'reference_number' => 'PRICE-' . time() . '-' . rand(1000, 9999),
            ]);
        }

        return redirect()->route('stocks.show', $stock)->with('success', 'Stock item updated successfully.');
    }

    public function destroy(Stock $stock)
    {
        // Check if there are any transactions other than initial
        $hasTransactions = $stock->transactions()->where('type', '!=', 'initial')->exists();
        
        if ($hasTransactions) {
            return redirect()->route('stocks.index')
                ->with('error', 'Cannot delete stock item with existing transactions. Consider archiving it instead.');
        }
        
        $stock->transactions()->delete();
        $stock->delete();
        
        return redirect()->route('stocks.index')
            ->with('success', 'Stock item deleted successfully.');
    }

    public function addStock(Request $request, Stock $stock)
    {
        // Check if this is a simple add from low stock page or detailed add
        $isSimpleAdd = !$request->has('unit_price');
        
        if ($isSimpleAdd) {
            // Simple validation for low stock page form
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:255',
            ]);
            
            try {
                // Create transaction record
                $transaction = StockTransaction::create([
                    'stock_id' => $stock->id,
                    'type' => 'in',
                    'quantity' => $validated['quantity'],
                    'unit_price' => $stock->unit_price, // Use existing price
                    'user_id' => auth()->id(),
                    'notes' => $validated['notes'] ?? 'Stock added from low stock alert',
                    'reference_number' => 'LOWADD-' . time() . '-' . rand(1000, 9999),
                    'transaction_date' => now(),
                ]);
                
                // Update stock quantity
                $stock->increment('quantity', $validated['quantity']);
                
                return redirect()->back()->with('success', 'Stock updated successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error updating stock: ' . $e->getMessage());
            }
        } else {
            // Detailed validation for regular stock addition
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'expiry_date' => 'nullable|date|after:today',
                'notes' => 'nullable|string',
                'reference_number' => 'nullable|string',
            ]);

            // Create transaction record
            $transaction = StockTransaction::create([
                'stock_id' => $stock->id,
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'unit_price' => $validated['unit_price'],
                'user_id' => auth()->id(),
                'notes' => $validated['notes'] ?? 'Stock addition',
                'reference_number' => $validated['reference_number'] ?? ('IN-' . time() . '-' . rand(1000, 9999)),
                'transaction_date' => now(),
            ]);

            // Update stock quantity and price
            $stock->increment('quantity', $validated['quantity']);
            
            // Update unit price only if it's different
            if ($stock->unit_price != $validated['unit_price']) {
                $stock->update(['unit_price' => $validated['unit_price']]);
            }
            
            // Update expiry date if provided
            if (isset($validated['expiry_date'])) {
                $stock->update(['expiry_date' => $validated['expiry_date']]);
            }

            return redirect()->route('stocks.show', $stock)->with('success', 'Stock added successfully.');
        }
    }

    public function removeStock(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => "required|integer|min:1|max:{$stock->quantity}",
            'notes' => 'nullable|string',
            'reference_number' => 'nullable|string',
        ]);

        // Set transaction type to out (consumption)
        $type = 'out';
        $notes = $validated['notes'] ?? 'Stock consumption';

        // Create transaction record
        $transaction = StockTransaction::create([
            'stock_id' => $stock->id,
            'type' => $type,
            'quantity' => $validated['quantity'],
            'unit_price' => $stock->unit_price,
            'user_id' => auth()->id(),
            'notes' => $notes,
            'reference_number' => $validated['reference_number'] ?? ($type === 'transfer_out' ? 'TRF-' : 'OUT-') . time() . '-' . rand(1000, 9999),
            'transaction_date' => now(),
        ]);

        // Decrement stock quantity
        $stock->decrement('quantity', $validated['quantity']);
        
        $successMessage = 'Stock removed successfully.';
        return redirect()->route('stocks.show', $stock)->with('success', $successMessage);
    }
    
    /**
     * Display low stock items
     */
    public function lowStock()
    {
        $criticalStocks = Stock::criticalStock()->with(['supplier'])->get();
        $lowStocks = Stock::lowStock()->where('stock_percentage', '>', 10)->with(['supplier'])->get();
        
        return view('stocks.low_stock', compact('criticalStocks', 'lowStocks'));
    }
    
    // Analytics method removed and moved to a dedicated controller
}
