<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomAllocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount(['currentOccupants'])
            ->with(['currentAllocation.user'])
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'floor' => $room->floor,
                    'pavilion' => $room->pavilion,
                    'accommodation_type' => $room->accommodation_type,
                    'capacity' => $room->capacity,
                    'status' => $room->status,
                    'description' => $room->description,
                    'maintenance_status' => $room->maintenance_status,
                    'occupancy' => $room->currentOccupants()->count(),
                    'availability_color' => $room->status === 'Available' ? 'green' : 'red',
                    'current_allocation' => $room->currentAllocation ? [
                        'student' => $room->currentAllocation->user->name,
                        'start_date' => $room->currentAllocation->start_date->format('Y-m-d'),
                        'end_date' => $room->currentAllocation->end_date?->format('Y-m-d')
                    ] : null
                ];
            });

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', 'unique:rooms,room_number'],
            'floor' => ['required', 'integer', 'min:0'],
            'pavilion' => ['required', Rule::in(['Girls', 'Boys'])],
            'accommodation_type' => ['required', Rule::in(['Staff', 'Intern'])],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['Available', 'Unavailable'])],
            'description' => ['nullable', Rule::in(['Occupied - Bookable', 'Vacant - Interns'])],
            'maintenance_status' => ['required', Rule::in(['operational', 'under_maintenance', 'needs_repair'])]
        ]);

        $room = Room::create($validated);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load(['currentAllocation.user', 'allocations.user' => function($query) {
            $query->withTrashed();
        }]);

        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', Rule::unique('rooms')->ignore($room)],
            'floor' => ['required', 'integer', 'min:0'],
            'pavilion' => ['required', Rule::in(['Girls', 'Boys'])],
            'accommodation_type' => ['required', Rule::in(['Staff', 'Intern'])],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['Available', 'Unavailable'])],
            'description' => ['nullable', Rule::in(['Occupied - Bookable', 'Vacant - Interns'])],
            'maintenance_status' => ['required', Rule::in(['operational', 'under_maintenance', 'needs_repair'])]
        ]);

        $room->update($validated);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->currentAllocation()->exists()) {
            return back()->with('error', 'Cannot delete room with active allocation.');
        }

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }

    public function allocate(Request $request, Room $room)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'notes' => ['nullable', 'string']
        ]);

        // Check if room is available
        if (!$room->isAvailable()) {
            return back()->with('error', 'Room is not available for allocation.');
        }

        // Check if student already has an active allocation
        if (RoomAllocation::where('user_id', $validated['user_id'])
            ->where('status', 'active')
            ->exists()) {
            return back()->with('error', 'Student already has an active room allocation.');
        }

        // Check for overlapping allocations
        if ($room->allocations()
            ->where('status', 'active')
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date'] ?? '9999-12-31'])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date'] ?? '9999-12-31'])
                    ->orWhere(function($query) use ($validated) {
                        $query->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date'] ?? '9999-12-31');
                    });
            })
            ->exists()) {
            return back()->with('error', 'Room allocation overlaps with existing allocation.');
        }

        DB::transaction(function() use ($room, $validated) {
            // Create room allocation
            $room->allocations()->create([
                'user_id' => $validated['user_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'notes' => $validated['notes'],
                'status' => 'active'
            ]);

            // Update room status
            $room->markAsOccupied();
        });

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room allocated successfully.');
    }

    public function deallocate(Room $room, RoomAllocation $allocation)
    {
        if ($allocation->room_id !== $room->id) {
            abort(404);
        }

        if (!$allocation->isActive()) {
            return back()->with('error', 'Room allocation is not active.');
        }

        DB::transaction(function() use ($room, $allocation) {
            // Complete the allocation
            $allocation->complete();

            // If no more active allocations, mark room as available
            if (!$room->allocations()->where('status', 'active')->exists()) {
                $room->markAsAvailable();
            }
        });

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room deallocated successfully.');
    }

    public function getAvailableRooms()
    {
        $rooms = Room::available()
            ->with(['currentAllocation.user'])
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'type' => $room->type,
                    'capacity' => $room->capacity,
                    'price' => $room->price_per_month,
                    'amenities' => $room->amenities
                ];
            });

        return response()->json($rooms);
    }

    public function maintenance(Request $request, Room $room)
    {
        if ($room->currentAllocation()->exists()) {
            return back()->with('error', 'Cannot put occupied room under maintenance.');
        }

        $room->markUnderMaintenance();

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room marked for maintenance.');
    }
    
    public function changeStatus(Request $request, Room $room)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Available', 'Unavailable'])],
            'maintenance_status' => ['required', Rule::in(['operational', 'under_maintenance', 'needs_repair'])]
        ]);
        
        $room->update($validated);
        
        return redirect()->route('rooms.index')
            ->with('success', 'Room status updated successfully.');
    }
    
    public function dashboard()
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'Available')->count();
        $occupiedRooms = Room::where('status', 'Unavailable')->count();
        $maintenanceRooms = Room::where('maintenance_status', '!=', 'operational')->count();
        
        $roomsByPavilion = Room::select('pavilion', DB::raw('count(*) as total'))
            ->groupBy('pavilion')
            ->get();
            
        $roomsByFloor = Room::select('floor', DB::raw('count(*) as total'))
            ->groupBy('floor')
            ->orderBy('floor')
            ->get();
            
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        
        return view('rooms.dashboard', compact(
            'totalRooms', 
            'availableRooms', 
            'occupiedRooms', 
            'maintenanceRooms',
            'roomsByPavilion',
            'roomsByFloor',
            'occupancyRate'
        ));
    }
}
