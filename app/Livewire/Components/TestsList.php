<?php

namespace App\Livewire\Components;

use App\Models\Test;
use App\Models\TestSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TestsList extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch() {
        $this->resetPage();
    }

    public function startTest($testId) {
        // Check if user has already taken this test
        $existingSubmission = TestSubmission::where('user_id', Auth::id())
            ->where('test_id', $testId)
            ->first();

        if ($existingSubmission) {
            session()->flash('error', 'You have already completed this test. You can only attempt each test once.');
            return;
        }

        return $this->redirect("/test/{$testId}");
    }

    public function viewResult($testId) {
        // Method to view test results (we can implement this later if needed)
        session()->flash('info', 'Test results feature coming soon!');
    }

    public function render() {
        $tests = Test::active()
            ->with(['questions'])
            ->has('questions')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        // Get user's completed test IDs
        $completedTestIds = TestSubmission::where('user_id', Auth::id())
            ->pluck('test_id')
            ->toArray();

        // Get user's test results
        $testResults = TestSubmission::where('user_id', Auth::id())
            ->with('test')
            ->get()
            ->keyBy('test_id');

        return view('livewire.components.tests-list', [
            'tests' => $tests,
            'completedTestIds' => $completedTestIds,
            'testResults' => $testResults
        ]);
    }
}
