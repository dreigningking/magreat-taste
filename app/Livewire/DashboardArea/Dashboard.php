<?php

namespace App\Livewire\DashboardArea;

use App\Models\Plan;
use App\Models\Task;
use Livewire\Component;
use App\Models\Platform;
use App\Models\Settlement;
use App\Models\TaskWorker;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Dashboard extends Component
{
    
    public function mount()
    {
        
    }

    public function loadDashboardData()
    {
        
    }

    public function loadTasksData()
    {
       
    }

    public function loadJobsData()
    {
        
        
       
    }

    public function loadSidebarData()
    {
        
    }

    
    
    public function render()
    {
        return view('livewire.dashboard-area.dashboard');
    }
}
