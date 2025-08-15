<?php

namespace App\Livewire\DashboardArea\Places;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Livewire\Component;
use App\Models\Location;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Locations extends Component
{
    use WithPagination;

    // Create location properties
    public $name = '';
    public $country_id = '';
    public $state_id = '';
    public $city_id = '';
    public $address = '';
    public $status = true;
    
    // Edit location properties
    public $edit_id = '';
    public $edit_name = '';
    public $edit_country_id = '';
    public $edit_state_id = '';
    public $edit_city_id = '';
    public $edit_address = '';
    public $edit_status = true;
    
    // Data collections
    public $countries;
    public $states = [];
    public $cities = [];
    public $edit_states = [];
    public $edit_cities = [];

    public function mount()
    {
        $this->countries = Country::all();
        $this->country_id = $this->countries->firstWhere('iso2','NG')->id;
        $this->states = State::where('country_id',$this->country_id)->orderBy('name')->get();
    }

    public function updatedCountryId($value)
    {
        if ($value) {
            $this->states = State::where('country_id', $value)->orderBy('name')->get();
            $this->state_id = '';
            $this->city_id = '';
            $this->cities = [];
        } else {
            $this->states = [];
            $this->cities = [];
            $this->state_id = '';
            $this->city_id = '';
        }
    }

    public function updatedStateId($value)
    {
        if ($value) {
            $this->cities = City::where('state_id', $value)->orderBy('name')->get();
            $this->city_id = '';
        } else {
            $this->cities = [];
            $this->city_id = '';
        }
    }

    public function updatedEditCountryId($value)
    {
        if ($value) {
            $this->edit_states = State::where('country_id', $value)->orderBy('name')->get();
            $this->edit_state_id = '';
            $this->edit_city_id = '';
            $this->edit_cities = [];
        } else {
            $this->edit_states = [];
            $this->edit_cities = [];
            $this->edit_state_id = '';
            $this->edit_city_id = '';
        }
    }

    public function updatedEditStateId($value)
    {
        if ($value) {
            $this->edit_cities = City::where('state_id', $value)->orderBy('name')->get();
            $this->edit_city_id = '';
        } else {
            $this->edit_cities = [];
            $this->edit_city_id = '';
        }
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:sqlite_countries.countries,id',
            'state_id' => 'required|exists:sqlite_states.states,id',
            'city_id' => 'required|exists:sqlite_cities.cities,id',
            'address' => 'required|string|max:500',
            'status' => 'boolean',
        ]);

        Location::create([
            'name' => $this->name,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'address' => $this->address,
            'status' => $this->status,
        ]);

        $this->resetForm();
        $this->dispatch('closeModal', ['modalId' => 'createLocationModal']);
        session()->flash('message', 'Location created successfully!');
    }

    public function resetForm()
    {
        $this->reset(['name', 'country_id', 'state_id', 'city_id', 'address', 'status']);
        $this->states = [];
        $this->cities = [];
    }

    public function resetEditForm()
    {
        $this->reset(['edit_id', 'edit_name', 'edit_country_id', 'edit_state_id', 'edit_city_id', 'edit_address', 'edit_status']);
        $this->edit_states = [];
        $this->edit_cities = [];
    }

    public function cancelCreate()
    {
        $this->resetForm();
        $this->dispatch('closeModal', ['modalId' => 'createLocationModal']);
    }

    public function cancelEdit()
    {
        $this->resetEditForm();
        $this->dispatch('closeModal', ['modalId' => 'editLocationModal']);
    }

    #[On('edit-location')]
    public function editLocation($id)
    {
        $location = Location::find($id);
        
        if ($location) {
            $this->edit_id = $location->id;
            $this->edit_name = $location->name;
            $this->edit_country_id = $location->country_id;
            $this->edit_state_id = $location->state_id;
            $this->edit_city_id = $location->city_id;
            $this->edit_address = $location->address;
            $this->edit_status = $location->status;
            
            // Load states and cities for the selected country and state
            if ($this->edit_country_id) {
                $this->edit_states = State::where('country_id', $this->edit_country_id)->orderBy('name')->get();
            }
            if ($this->edit_state_id) {
                $this->edit_cities = City::where('state_id', $this->edit_state_id)->orderBy('name')->get();
            }
        }
    }

    public function update()
    {
        $this->validate([
            'edit_id' => 'required|exists:locations,id',
            'edit_name' => 'required|string|max:255',
            'edit_country_id' => 'required|exists:sqlite_countries.countries,id',
            'edit_state_id' => 'required|exists:sqlite_states.states,id',
            'edit_city_id' => 'required|exists:sqlite_cities.cities,id',
            'edit_address' => 'required|string|max:500',
            'edit_status' => 'boolean',
        ]);

        $location = Location::find($this->edit_id);
        
        if ($location) {
            $location->update([
                'name' => $this->edit_name,
                'country_id' => $this->edit_country_id,
                'state_id' => $this->edit_state_id,
                'city_id' => $this->edit_city_id,
                'address' => $this->edit_address,
                'status' => $this->edit_status,
            ]);

            $this->resetEditForm();
            $this->dispatch('closeModal', ['modalId' => 'editLocationModal']);
            session()->flash('message', 'Location updated successfully!');
        }
    }

    public function delete($id)
    {
        $location = Location::find($id);
        if ($location) {
            $location->delete();
            session()->flash('message', 'Location deleted successfully!');
        }
    }

    public function render()
    {
        $locations = Location::with(['country', 'state', 'city'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.dashboard-area.places.locations', [
            'locations' => $locations
        ]);
    }
}
