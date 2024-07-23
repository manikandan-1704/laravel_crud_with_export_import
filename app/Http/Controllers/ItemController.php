<?php 

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ItemController extends Controller
{
    public function index() {
        try {
            $items = Item::orderBy('created_at', 'desc')->get();
            return view('items.index', compact('items'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the items.']);
        }
    }

    public function create() {
        try {
            return view('items.create');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while opening the create form.']);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('items.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        try {
            Item::create($request->all());
            return redirect()->route('items.index')
                             ->with('success', 'Item created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the item.']);
        }
    }

    public function show(Item $item) {
        try {
            return view('items.show', compact('item'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the item details.']);
        }
    }

    public function edit(Item $item) {
        try {
            return view('items.edit', compact('item'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while opening the edit form.']);
        }
    }

    public function update(Request $request, Item $item) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('items.edit', $item->id)
                             ->withErrors($validator)
                             ->withInput();
        }

        try {
            $item->update($request->all());
            return redirect()->route('items.index')
                             ->with('success', 'Item updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating the item.']);
        }
    }

    public function destroy(Item $item) {
        try {
            $item->delete();
            return redirect()->route('items.index')
                             ->with('success', 'Item deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while deleting the item.']);
        }
    }

    public function export()
    {
    try{

    $items = Item::all();

    $response = new StreamedResponse(function() use ($items) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['S.No.', 'ID', 'Name', 'Description']);
        
        foreach ($items->values() as $index => $item) {
            fputcsv($handle, [$index + 1, $item->id, $item->name, $item->description]);
        }

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="items.csv"');

    return $response;
}
     catch (\Exception $e) {
        return back()->withErrors(['error' => 'An error occurred while exporting the items.']);
    }
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv' 
    ]);

    try {
       
        $file = $request->file('file');
        $path = $file->store('imports');
        $fullPath = storage_path('app/' . $path);

        $spreadsheet = IOFactory::load($fullPath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(); 

        $header = array_shift($data); 

        foreach ($data as $row) {
            Item::updateOrCreate(
                ['name' => $row[0]], 
                ['description' => $row[1]]
            );
        }
        Storage::delete($path);

        return redirect()->route('items.index')->with('success', 'Items imported successfully!');
    } catch (\Exception $e) {
        return redirect()->route('items.index')->with('error', 'Failed to import items: ' . $e->getMessage());
    }
}
}