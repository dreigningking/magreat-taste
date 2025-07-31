<?php

namespace App\Livewire\DashboardArea;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contact;
use Carbon\Carbon;

class Contactus extends Component
{
    use WithPagination;

    // Filter properties
    public $dateFrom = '';
    public $dateTo = '';
    public $status = '';
    public $contactType = '';
    public $search = '';

    // Sort properties
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Modal properties
    public $selectedContactId = null;
    public $selectedContact = null;

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'status' => ['except' => ''],
        'contactType' => ['except' => ''],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        // Set default date range to last 30 days
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedContactType()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['dateFrom', 'dateTo', 'status', 'contactType', 'search']);
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->resetPage();
    }

    public function updateStatus($contactId, $newStatus)
    {
        $contact = Contact::find($contactId);
        if ($contact) {
            $contact->update(['status' => $newStatus]);
            session()->flash('message', 'Contact status updated successfully!');
        }
    }

    public function render()
    {
        $query = Contact::query()
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->contactType, function ($query) {
                $query->where('contact_type', $this->contactType);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('message', 'like', '%' . $this->search . '%');
                });
            });

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $contacts = $query->paginate(15);

        // Calculate statistics
        $totalContacts = $query->count();
        $pendingContacts = $query->where('status', 'pending')->count();
        $resolvedContacts = $query->where('status', 'resolved')->count();
        $closedContacts = $query->where('status', 'closed')->count();

        return view('livewire.dashboard-area.contactus', [
            'contacts' => $contacts,
            'totalContacts' => $totalContacts,
            'pendingContacts' => $pendingContacts,
            'resolvedContacts' => $resolvedContacts,
            'closedContacts' => $closedContacts,
        ]);
    }
}
