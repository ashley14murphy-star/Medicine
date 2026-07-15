<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // Display dashboard with list of medicines, optional edit target, and category counts
    public function index(Request $request)
    {
        $medicines = Medicine::orderBy('expiry_date', 'asc')->get();
        $editingMedicine = null;

        if ($request->has('edit')) {
            $editingMedicine = Medicine::find($request->edit);
        }

        // Kinukuha natin ang bilang ng active records para sa bawat kategorya
        $categoryCounts = [
            'Tablet'    => Medicine::where('category', 'Tablet')->count(),
            'Capsule'   => Medicine::where('category', 'Capsule')->count(),
            'Syrup'     => Medicine::where('category', 'Syrup')->count(),
            'Ointment'  => Medicine::where('category', 'Ointment')->count(),
            'Injection' => Medicine::where('category', 'Injection')->count(),
        ];

        // Isinama ang 'categoryCounts' sa compact para magamit sa Blade template
        return view('dashboard', compact('medicines', 'editingMedicine', 'categoryCounts'));
    }

    // Store a new medicine
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date|after_or_equal:today',
        ]);

        Medicine::create($validated);

        return redirect()->route('dashboard')->with('success', 'Medical record added successfully!');
    }

    // Update an existing medicine
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
        ]);

        $medicine->update($validated);

        return redirect()->route('dashboard')->with('success', 'Medical record updated successfully!');
    }

    // Delete a medicine
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('dashboard')->with('success', 'Medical record deleted successfully!');
    }
}