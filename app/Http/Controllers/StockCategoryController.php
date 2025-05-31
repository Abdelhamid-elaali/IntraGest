<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StockCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StockCategory::query();
        
        // Filter by parent category (main categories or subcategories)
        if ($request->filled('parent')) {
            if ($request->parent === 'main') {
                $query->mainCategories();
            } elseif ($request->parent === 'sub') {
                $query->subcategories();
            } else {
                $query->where('parent_id', $request->parent);
            }
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Get categories with their parent relationship
        $categories = $query->with('parent')->latest()->paginate(10)->withQueryString();
        
        // Get main categories for the filter dropdown
        $mainCategories = StockCategory::mainCategories()->get();
        
        return view('stock-categories.index', compact('categories', 'mainCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mainCategories = StockCategory::mainCategories()->active()->get();
        return view('stock-categories.create', compact('mainCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:stock_categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:stock_categories,id',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => 'nullable|integer'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        StockCategory::create($request->all());
        
        return redirect()->route('stock-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = StockCategory::with(['parent', 'subcategories', 'stocks'])->findOrFail($id);
        return view('stock-categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = StockCategory::findOrFail($id);
        $mainCategories = StockCategory::mainCategories()
            ->where('id', '!=', $id)
            ->active()
            ->get();
            
        return view('stock-categories.edit', compact('category', 'mainCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = StockCategory::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', Rule::unique('stock_categories')->ignore($id)],
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:stock_categories,id',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => 'nullable|integer'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Prevent category from being its own parent
        if ($request->parent_id == $id) {
            return redirect()->back()
                ->withErrors(['parent_id' => 'A category cannot be its own parent.'])
                ->withInput();
        }
        
        $category->update($request->all());
        
        return redirect()->route('stock-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = StockCategory::findOrFail($id);
        
        // Check if category has subcategories
        if ($category->subcategories()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with subcategories. Please delete or reassign subcategories first.');
        }
        
        // Check if category has stocks
        if ($category->stocks()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with associated stock items. Please reassign stock items first.');
        }
        
        $category->delete();
        
        return redirect()->route('stock-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
