<?php

namespace App\Http\Controllers;
use App\Models\Marker; 

use Illuminate\Http\Request;

class MarkerController extends Controller
{

    public function deleteMarker(Request $request, $markerId)
    {
        // Retrieve the authenticated user
        $user = $request->user();

        // Find the marker by ID and check if it belongs to the authenticated user
        $marker = Marker::find($markerId);
        if (!$marker || $marker->user_id!== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the marker
        $marker->delete();

        return response()->json(['success' => 'Marker deleted successfully']);
    }

    public function showMarkersForUser(Request $request)
    {
        // Retrieve the authenticated user
        $user = $request->user();

        // Fetch markers for the authenticated user
        $markers = Marker::where('user_id', $user->id)->get();

        // Return the markers
        return response()->json($markers);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $markers = Marker::where('user_id', auth()->id())->get();
    
        return view('markers.index', compact('markers'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('markers.create');
    }   


    public function storeMarker(Request $request)
    {
        // Validate the request data
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'user_id' => 'required|integer', // Assuming user_id is sent in the request
        ]);

        // Create a new marker
        $marker = new Marker([
            'title' => $request->title,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'user_id' => $request->user_id,
        ]);

        // Save the marker
        $marker->save();
        $markerId = $marker->id;
        // Return a JSON response indicating success
        return response()->json(['message' => 'Marker added successfully.', 'marker_id' => $markerId], 201);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);
    
        $marker = new Marker([
            'title' => $request->title,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'user_id' => auth()->id(),
        ]);
        print_r($marker);
        $marker->save();
        //return 'hello';
        return redirect()->back()->with('success', 'Marker added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
       // $markers = Marker::all();
            $markers = Marker::where('created_at', '>=', now()->subHours(2))->get();
   

        return $markers->toJson();
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $marker = Marker::findOrFail($id);
    
        return view('markers.edit', compact('marker'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $marker = Marker::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'lat' => 'required|numeric',
        'lon' => 'required|numeric',
    ]);

    $marker->update([
        'title' => $request->title,
        'lat' => $request->lat,
        'lon' => $request->lon,
    ]);

    return redirect()->back()->with('success', 'Marker updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $marker = Marker::findOrFail($id);

    // Check if the authenticated user is the owner of the marker
    if ($marker->user_id!= auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $marker->delete();

    return redirect()->back()->with('success', 'Marker deleted successfully.');

    }
    
}
