<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PaymentWorkFlow extends Component
{
    public $currentIndex = 0;
    public $subContainers = [
        ['title' => 'Sub Container 1', 'content' => 'Content for sub container 1'],
        ['title' => 'Sub Container 2', 'content' => 'Content for sub container 2'],
        ['title' => 'Sub Container 3', 'content' => 'Content for sub container 3'],
        ['title' => 'Sub Container 4', 'content' => 'Content for sub container 4'],
        ['title' => 'Sub Container 5', 'content' => 'Content for sub container 5'],
        ['title' => 'Sub Container 6', 'content' => 'Content for sub container 6'],
        ['title' => 'Sub Container 7', 'content' => 'Content for sub container 7'],
    ];

    public function render()
    {
        return view('livewire.payment-work-flow');
    }

    public function next()
    {
        $this->currentIndex = min($this->currentIndex + 1, count($this->subContainers) - 1);
    }

    public function back()
    {
        $this->currentIndex = max($this->currentIndex - 1, 0);
    }

    public function mainMenu()
    {
        $this->currentIndex = 0;
    }
}
