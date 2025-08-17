<?php

namespace App\Livewire\DashboardArea\Meals;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Size;

class ListSizes extends Component
{
    use WithPagination, WithFileUploads;

    // Create size properties
    public $name = '';
    public $image = null;

    // Edit size properties
    public $edit_id = '';
    public $edit_name = '';
    public $edit_image = null;

    public function mount()
    {
        // Initialize empty form
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('sizes', 'public');
        }

        Size::create([
            'name' => $this->name,
            'image' => $imagePath,
        ]);

        // Reset form
        $this->reset(['name', 'image']);
        $this->dispatch('closeModal', ['modalId' => 'createSizeModal']);
        session()->flash('message', 'Size created successfully!');
    }

    public function editSize($id)
    {
        $size = Size::find($id);
        
        if ($size) {
            $this->edit_id = $size->id;
            $this->edit_name = $size->name;
            $this->edit_image = null;
        }
    }

    public function update()
    {
        $this->validate([
            'edit_id' => 'required|exists:sizes,id',
            'edit_name' => 'required|string|max:255|unique:sizes,name,' . $this->edit_id,
            'edit_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $size = Size::find($this->edit_id);
        
        $imagePath = $size->image; // Keep existing image by default
        
        if ($this->edit_image) {
            $imagePath = $this->edit_image->store('sizes', 'public');
        }

        $size->update([
            'name' => $this->edit_name,
            'image' => $imagePath,
        ]);

        // Reset edit form
        $this->reset(['edit_id', 'edit_name', 'edit_image']);
        $this->dispatch('closeModal', ['modalId' => 'editSizeModal']);
        session()->flash('message', 'Size updated successfully!');
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
        $sizes = Size::orderBy('created_at', 'desc')->paginate(10);

        // Calculate statistics
        $totalSizes = Size::count();

        return view('livewire.dashboard-area.meals.list-sizes', [
            'sizes' => $sizes,
            'totalSizes' => $totalSizes,
        ]);
    }
}
