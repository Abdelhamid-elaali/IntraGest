<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['supplier', 'transactions'])->latest()->paginate(10);
        return view('stocks.index', compact('stocks'));
    }

    public function create()
    {
        return view('stocks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $stock = Stock::create($validated);

        // Create initial stock transaction
        StockTransaction::create([
            'stock_id' => $stock->id,
            'type' => 'initial',
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'user_id' => auth()->id(),
            'notes' => 'Initial stock entry',
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock item created successfully.');
    }

    public function show(Stock $stock)
    {
        $stock->load(['supplier', 'transactions.user']);
        return view('stocks.show', compact('stock'));
    }

    public function edit(Stock $stock)
    {
        return view('stocks.edit', compact('stock'));
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $stock->update($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock item updated successfully.');
    }

    public function destroy(Stock $stock)
    {
        $stock->transactions()->delete();
        $stock->delete();
        return redirect()->route('stocks.index')->with('success', 'Stock item deleted successfully.');
    }

    public function addStock(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        StockTransaction::create([
            'stock_id' => $stock->id,
            'type' => 'in',
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'user_id' => auth()->id(),
            'notes' => $validated['notes'] ?? 'Stock addition',
        ]);

        $stock->increment('quantity', $validated['quantity']);
        $stock->update(['unit_price' => $validated['unit_price']]);

        return redirect()->route('stocks.show', $stock)->with('success', 'Stock added successfully.');
    }

    public function removeStock(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => "required|integer|min:1|max:{$stock->quantity}",
            'notes' => 'nullable|string',
        ]);

        StockTransaction::create([
            'stock_id' => $stock->id,
            'type' => 'out',
            'quantity' => $validated['quantity'],
            'unit_price' => $stock->unit_price,
            'user_id' => auth()->id(),
            'notes' => $validated['notes'] ?? 'Stock removal',
        ]);

        $stock->decrement('quantity', $validated['quantity']);

        return redirect()->route('stocks.show', $stock)->with('success', 'Stock removed successfully.');
    }
}
