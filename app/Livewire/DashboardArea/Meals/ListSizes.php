<?php

namespace App\Livewire\DashboardArea\Meals;


use App\Models\Size;
use Livewire\Component;
use App\Imports\SizesImport;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ListSizes extends Component
{
    use WithPagination, WithFileUploads;

    public $type_options = ['bowl', 'bucket', 'cup', 'bottle', 'box', 'pack','tray','wrap', 'slice'];
    public $unit_options = ['L', 'ml', 'g', 'kg', 'oz', 'lb', 'piece'];
    // Create size properties
    public $name = '';
    public $type = '';
    public $unit = 'L';
    public $value = '';
    public $image = null;

    // Upload sizes properties
    public $uploadFile = null;

    // Edit size properties
    public $edit_id = '';
    public $edit_name = '';
    public $edit_type = '';
    public $edit_unit = 'L';
    public $edit_value = '';
    public $edit_image = null;

    // Sorting and filtering properties
    public $sortBy = 'type';
    public $sortDirection = 'asc';
    public $filterType = '';

    public function mount()
    {
        // Initialize empty form
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
            'type' => 'nullable|string|max:255',
            'unit' => 'required|string|max:10',
            'value' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('sizes', 'public');
        }

        Size::create([
            'name' => $this->name,
            'type' => $this->type,
            'unit' => $this->unit,
            'value' => $this->value,
            'image' => $imagePath,
        ]);

        // Reset form
        $this->reset(['name', 'type', 'unit', 'value', 'image']);
        $this->dispatch('closeModal', ['modalId' => 'createSizeModal']);
        session()->flash('message', 'Size created successfully!');
    }

    public function editSize($id)
    {
        $size = Size::find($id);

        if ($size) {
            $this->edit_id = $size->id;
            $this->edit_name = $size->name;
            $this->edit_type = $size->type;
            $this->edit_unit = $size->unit;
            $this->edit_value = $size->value;
            $this->edit_image = null;
        }
    }

    public function update()
    {
        $this->validate([
            'edit_id' => 'required|exists:sizes,id',
            'edit_name' => 'required|string|max:255|unique:sizes,name,' . $this->edit_id,
            'edit_type' => 'nullable|string|max:255',
            'edit_unit' => 'required|string|max:10',
            'edit_value' => 'required|numeric',
            'edit_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $size = Size::find($this->edit_id);

        $imagePath = $size->image; // Keep existing image by default

        if ($this->edit_image) {
            $imagePath = $this->edit_image->store('sizes', 'public');
        }

        $size->update([
            'name' => $this->edit_name,
            'type' => $this->edit_type,
            'unit' => $this->edit_unit,
            'value' => $this->edit_value,
            'image' => $imagePath,
        ]);

        // Reset edit form
        $this->reset(['edit_id', 'edit_name', 'edit_type', 'edit_unit', 'edit_value', 'edit_image']);
        $this->dispatch('closeModal', ['modalId' => 'editSizeModal']);
        session()->flash('message', 'Size updated successfully!');
    }

    public function uploadSizes()
    {
        Log::info('Upload method called');
        Log::info('Upload method called', [
            'uploadFile' => $this->uploadFile ? $this->uploadFile->getClientOriginalName() : null,
            'uploadFileType' => $this->uploadFile ? $this->uploadFile->getMimeType() : null,
        ]);

        $this->validate([
            'uploadFile' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);
        Log::info('Upload file validated', [
            'uploadFile' => $this->uploadFile ? $this->uploadFile->getClientOriginalName() : null,
        ]);

        try {
            $realPath = $this->uploadFile->getRealPath();
            Log::info('Excel import starting', [
                'realPath' => $realPath,
            ]);
            Excel::import(new SizesImport, $realPath);
            Log::info('Excel import completed');
            // Reset form
            $this->reset(['uploadFile']);
            $this->dispatch('closeModal', ['modalId' => 'uploadSizesModal']);
            session()->flash('message', 'Sizes uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Error uploading sizes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Error uploading sizes: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $size = Size::find($id);
        if ($size) {
            $size->delete();
            session()->flash('message', 'Size deleted successfully!');
        }
    }

    public function render()
    {
        $query = Size::query();

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        $sizes = $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);

        // Calculate statistics
        $totalSizes = Size::count();
        $types = Size::distinct('type')->pluck('type')->filter()->values();

        return view('livewire.dashboard-area.meals.list-sizes', [
            'sizes' => $sizes,
            'totalSizes' => $totalSizes,
            'types' => $types,
        ]);
    }
}
