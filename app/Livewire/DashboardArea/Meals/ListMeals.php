<?php

namespace App\Livewire\DashboardArea\Meals;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Meal;
use App\Models\Category;
use App\Models\Food;
use App\Imports\MealsImport;
use Maatwebsite\Excel\Facades\Excel;

class ListMeals extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter properties
    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $sortBy = 'created_at';

    // Upload meals properties
    public $uploadFile = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function upload()
    {
        $this->validate([
            'uploadFile' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            Excel::import(new MealsImport, $this->uploadFile->getRealPath());

            // Reset form
            $this->reset(['uploadFile']);
            $this->dispatch('closeModal', ['modalId' => 'uploadMealsModal']);
            session()->flash('message', 'Meals uploaded successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error uploading meals: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $meal = Meal::find($id);
        if ($meal) {
            // Detach foods first
            $meal->foods()->detach();
            $meal->delete();

            session()->flash('message', 'Meal deleted successfully!');
        }
    }

    public function render()
    {
        $query = Meal::with(['category', 'foods.sizes']);

        // Apply search filter
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply category filter
        if ($this->categoryFilter) {
            $query->byCategory($this->categoryFilter);
        }

        // Apply status filter
        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'from_price':
                // For price sorting, we need to calculate from_price for each meal
                $query->orderBy('created_at', 'desc'); // Default fallback
                break;
            case 'from_price_desc':
                // For price sorting, we need to calculate from_price for each meal
                $query->orderBy('created_at', 'desc'); // Default fallback
                break;
            case 'category_id':
                $query->orderBy('category_id', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $meals = $query->paginate(10);

        // For price sorting, we need to manually sort the collection
        if (in_array($this->sortBy, ['from_price', 'from_price_desc'])) {
            $meals = $meals->getCollection()->sortBy(function ($meal) {
                return $meal->from_price;
            });
            
            if ($this->sortBy === 'from_price_desc') {
                $meals = $meals->reverse();
            }
            
            // Recreate paginator with sorted collection
            $meals = new \Illuminate\Pagination\LengthAwarePaginator(
                $meals,
                $meals->count(),
                10,
                request()->get('page', 1),
                ['path' => request()->url()]
            );
        }

        // Get categories for filter dropdown
        $categories = Category::where('type', 'meal')->where('is_active', true)->get();

        // Calculate statistics
        $totalMeals = Meal::count();
        $activeMeals = Meal::where('is_active', true)->count();
        $totalCategories = Category::where('type', 'meal')->count();
        
        // Calculate average price (this is a simplified calculation)
        $averagePrice = 0;
        $mealsWithPrices = Meal::with('foods.sizes')->get();
        $totalPrice = 0;
        $count = 0;
        
        foreach ($mealsWithPrices as $meal) {
            $fromPrice = $meal->from_price;
            if ($fromPrice > 0) {
                $totalPrice += $fromPrice;
                $count++;
            }
        }
        
        $averagePrice = $count > 0 ? $totalPrice / $count : 0;

        return view('livewire.dashboard-area.meals.list-meals', [
            'meals' => $meals,
            'categories' => $categories,
            'totalMeals' => $totalMeals,
            'activeMeals' => $activeMeals,
            'totalCategories' => $totalCategories,
            'averagePrice' => $averagePrice,
        ]);
    }
}
