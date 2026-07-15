<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // Display dashboard with list of medicines and optional edit target
    public function index(Request $request)
    {
        $medicines = Medicine::orderBy('expiry_date', 'asc')->get();
        $editingMedicine = null;

        if ($request->has('edit')) {
            $editingMedicine = Medicine::find($request->edit);
        }

        return view('dashboard', compact('medicines', 'editingMedicine'));
    }

    // Store a new medicine
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date|after_or_equal:today',
        ]);

        Medicine::create($validated);

        // PINALITAN: redirect sa 'dashboard' sa halip na 'medicines.index'
        return redirect()->route('dashboard')->with('success', 'Medical record added successfully!');
    }

    // Update an existing medicine
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
        ]);

        $medicine->update($validated);

        // PINALITAN: redirect sa 'dashboard' sa halip na 'medicines.index'
        return redirect()->route('dashboard')->with('success', 'Medical record updated successfully!');
    }

    // Delete a medicine
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        // PINALITAN: redirect sa 'dashboard' sa halip na 'medicines.index'
        return redirect()->route('dashboard')->with('success', 'Medical record deleted successfully!');
    }
}