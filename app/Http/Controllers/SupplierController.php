<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\StockOrder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search by name, email or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        $suppliers = $query->latest()->paginate(10)->withQueryString();
        
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:50',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Supplier::create($request->all());
        
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Get supplier statistics
        $totalStocks = $supplier->stocks()->count();
        $activeStocks = $supplier->activeStocks()->count();
        $lowStockItems = $supplier->lowStockItems()->count();
        $expiringStocks = $supplier->expiringStocks()->count();
        $totalPurchases = $supplier->totalPurchases();
        
        // Get recent orders
        $recentOrders = StockOrder::where('supplier_id', $id)
            ->with('user')
            ->latest('order_date')
            ->take(5)
            ->get();
            
        // Get stocks from this supplier
        $stocks = Stock::where('supplier_id', $id)
            ->with('category')
            ->latest()
            ->paginate(10);
        
        return view('suppliers.show', compact(
            'supplier', 
            'totalStocks', 
            'activeStocks', 
            'lowStockItems', 
            'expiringStocks',
            'totalPurchases',
            'recentOrders',
            'stocks'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('suppliers')->ignore($id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:50',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $supplier->update($request->all());
        
        return redirect()->route('suppliers.show', $supplier->id)
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Check if supplier has associated stocks
        if ($supplier->stocks()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete supplier with associated stock items. Please reassign stock items first.');
        }
        
        // Check if supplier has associated orders
        if (StockOrder::where('supplier_id', $id)->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete supplier with associated orders. Please delete orders first.');
        }
        
        $supplier->delete();
        
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
    
    /**
     * Display supplier orders.
     */
    public function orders(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $orders = StockOrder::where('supplier_id', $id)
            ->with(['user', 'approvedBy'])
            ->latest('order_date')
            ->paginate(10);
            
        return view('suppliers.orders', compact('supplier', 'orders'));
    }
    
    /**
     * Display supplier stock items.
     */
    public function stocks(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $stocks = Stock::where('supplier_id', $id)
            ->with(['category', 'subcategory'])
            ->latest()
            ->paginate(10);
            
        return view('suppliers.stocks', compact('supplier', 'stocks'));
    }
}
