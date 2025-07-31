<?php

namespace App\Livewire\DashboardArea\Partials;

use Livewire\Component;

class SummernoteEditor extends Component
{
    public $content = '';
    public $placeholder = '';
    public $height = 'auto';
    public $wireModel = '';
    public $uniqueId;

    public function mount($content = '', $placeholder = '', $height = 'auto', $wireModel = '')
    {
        $this->content = $content;
        $this->placeholder = $placeholder;
        $this->height = $height;
        $this->wireModel = $wireModel;
        $this->uniqueId = 'summernote_' . uniqid();
    }

    public function updateContent($content)
    {
        $this->content = $content;
        $this->dispatch('contentUpdated', ['content' => $content, 'wireModel' => $this->wireModel]);
    }

    public function render()
    {
        return view('livewire.dashboard-area.partials.summernote-editor');
    }
} 