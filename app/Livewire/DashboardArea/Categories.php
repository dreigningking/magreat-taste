<?php

namespace App\Livewire\DashboardArea;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class Categories extends Component
{
    public $categories;
    
    // Add category properties
    public $name = '';
    public $description = '';
    public $image = '';
    public $type = 'post';
    public $is_active = 1;
    
    // Edit category properties
    public $edit_id = '';
    public $edit_name = '';
    public $edit_description = '';
    public $edit_image = '';
    public $edit_type = 'post';
    public $edit_is_active = 1;

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'required|string|max:500',
            'image' => 'nullable|url|max:255',
            'type' => 'required|in:post,meal',
            'is_active' => 'boolean'
        ]);

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['name', 'description', 'image', 'type', 'is_active']);
        $this->categories = Category::all();
        
        session()->flash('message', 'Category created successfully!');
    }

    #[On('editCategory')]
    public function editCategory($id, $name, $description, $image, $type, $isActive)
    {
        $this->edit_id = $id;
        $this->edit_name = $name;
        $this->edit_description = $description;
        $this->edit_image = $image;
        $this->edit_type = $type;
        $this->edit_is_active = intval($isActive);
    }

    public function update()
    {
        
        $this->validate([
            'edit_id' => 'required|exists:categories,id',
            'edit_name' => 'required|string|max:255|unique:categories,name,' . $this->edit_id,
            'edit_description' => 'required|string|max:500',
            'edit_image' => 'nullable|url|max:255',
            'edit_type' => 'required|in:post,meal',
            'edit_is_active' => 'boolean'
        ]);
        
        $category = Category::find($this->edit_id);
        $category->update([
            'name' => $this->edit_name,
            'description' => $this->edit_description,
            'image' => $this->edit_image,
            'type' => $this->edit_type,
            'is_active' => $this->edit_is_active,
        ]);

        $this->reset(['edit_id', 'edit_name', 'edit_description', 'edit_image', 'edit_type', 'edit_is_active']);
        $this->categories = Category::all();
        $this->dispatch('closeModal', ['modalId' => 'editCategoryModal']);
        session()->flash('message', 'Category updated successfully!');
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            $this->categories = Category::all();
            session()->flash('message', 'Category deleted successfully!');
        }
    }

    public function clearValidationErrors()
    {
        $this->resetErrorBag();
        $this->reset(['edit_id', 'edit_name', 'edit_description', 'edit_image', 'edit_type', 'edit_is_active']);
    }

    public function render()
    {
        return view('livewire.dashboard-area.categories');
    }
}
