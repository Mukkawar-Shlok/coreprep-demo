<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Sidebar extends Component {
    public string $activeTab = 'dashboard';
    public bool $isCollapsed = false;

    public function setActiveTab(string $tab) {
        $this->activeTab = $tab;
        $this->dispatch('tab-changed', $tab);
    }

    public function toggleSidebar() {
        $this->isCollapsed = !$this->isCollapsed;
    }

    public function render() {
        return view('livewire.components.sidebar');
    }
}
