<?php

namespace App\Livewire\DashboardArea\Places;

use App\Models\City;
use App\Models\Country;
use Livewire\Component;
use App\Models\Location;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ShipmentRoute;

class Logistics extends Component
{
    use WithPagination;

    // Create shipment route properties
    public $route_name = '';
    public $shipper_name = '';
    public $location_id = '';
    public $destination_city_id = '';
    public $base_price = '';
    public $estimated_minutes = '';
    public $notes = '';
    public $status = false;
    
    // Edit shipment route properties
    public $edit_route_id = '';
    public $edit_route_name = '';
    public $edit_shipper_name = '';
    public $edit_location_id = '';
    public $edit_destination_city_id = '';
    public $edit_base_price = '';
    public $edit_estimated_minutes = '';
    public $edit_notes = '';
    public $edit_status = false;
    
    public $locations;
    public $states = [];
    public $cities = [];

    public function mount()
    {
        $this->locations = Location::all();
        $country = Country::where('iso2','NG')->first();
        $this->cities = City::where('country_id',$country->id)->with(['state'])->orderBy('state_id','asc')->get();
    }

    public function store()
    { 
        // dd($this->all());
        $this->validate([
            'route_name' => 'required|string|max:255',
            'shipper_name' => 'required|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'destination_city_id' => 'required|exists:sqlite_cities.cities,id',
            'base_price' => 'nullable|numeric|min:0',
            'estimated_minutes' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Create the shipment route
        ShipmentRoute::create([
            'route_name' => $this->route_name,
            'shipper_name' => $this->shipper_name,
            'location_id' => $this->location_id,
            'destination_city_id' => $this->destination_city_id,
            'base_price' => $this->base_price ?: 0,
            'estimated_minutes' => $this->estimated_minutes ?: 0,
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        // Reset form
        $this->resetForm();
        
        $this->dispatch('closeModal', ['modalId' => 'createLogisticsModal']);
        session()->flash('message', 'Shipment route created successfully!');
    }

    public function resetForm()
    {
        $this->reset(['route_name', 'shipper_name', 'location_id', 'destination_city_id', 'base_price', 'estimated_minutes', 'notes', 'status']);
    }

    public function resetEditForm()
    {
        $this->reset(['edit_route_id', 'edit_route_name', 'edit_shipper_name', 'edit_location_id', 'edit_destination_city_id', 'edit_base_price', 'edit_estimated_minutes', 'edit_notes', 'edit_status']);
    }

    public function cancelCreate()
    {
        $this->resetForm();
        $this->dispatch('closeModal', ['modalId' => 'createLogisticsModal']);
    }

    public function cancelEdit()
    {
        $this->resetEditForm();
        $this->dispatch('closeModal', ['modalId' => 'editshipmentRouteModal']);
    }

    #[On('edit-shipment-route')]
    public function editShipmentRoute($id)
    {
        $route = ShipmentRoute::find($id);
        
        if ($route) {
            $this->edit_route_id = $route->id;
            $this->edit_route_name = $route->route_name;
            $this->edit_shipper_name = $route->shipper_name;
            $this->edit_location_id = $route->location_id;
            $this->edit_destination_city_id = $route->destination_city_id;
            $this->edit_base_price = $route->base_price;
            $this->edit_estimated_minutes = $route->estimated_minutes;
            $this->edit_notes = $route->notes;
            $this->edit_status = $route->status;
            
            // Dispatch event to reinitialize Select2 after data is loaded
            $this->dispatch('reinitializeSelect2', [
                'location' => $route->location_id,
                'destination' => $route->destination_city_id
            ]);
        }
    }

    #[On('select2_change')]
    public function select2changes($field, $value)
    {
        $this->{$field} = $value;
    }

    public function update()
    {
        $this->validate([
            'edit_route_id' => 'required|exists:shipment_routes,id',
            'edit_route_name' => 'required|string|max:255',
            'edit_shipper_name' => 'required|string|max:255',
            'edit_location_id' => 'required|exists:locations,id',
            'edit_destination_city_id' => 'required|exists:sqlite_cities.cities,id',
            'edit_base_price' => 'nullable|numeric|min:0',
            'edit_estimated_minutes' => 'nullable|numeric|min:0',
            'edit_notes' => 'nullable|string|max:500',
        ]);

        $route = ShipmentRoute::find($this->edit_route_id);
        
        if ($route) {
            $route->update([
                'route_name' => $this->edit_route_name,
                'shipper_name' => $this->edit_shipper_name,
                'location_id' => $this->edit_location_id,
                'destination_city_id' => $this->edit_destination_city_id,
                'base_price' => $this->edit_base_price ?: 0,
                'estimated_minutes' => $this->edit_estimated_minutes ?: 0,
                'notes' => $this->edit_notes,
                'status' => $this->edit_status,
            ]);

            // Reset edit form
            $this->resetEditForm();
            $this->dispatch('closeModal', ['modalId' => 'editshipmentRouteModal']);
            session()->flash('message', 'Shipment route updated successfully!');
        }
    }

    public function delete($id)
    {
        $route = ShipmentRoute::find($id);
        if ($route) {
            $route->delete();
            session()->flash('message', 'Shipment route deleted successfully!');
        }
    }

    public function render()
    {
        $shipmentRoutes = ShipmentRoute::with(['location', 'destinationCity.state'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('livewire.dashboard-area.places.logistics', [
            'shipmentRoutes' => $shipmentRoutes
        ]);
    }
}
