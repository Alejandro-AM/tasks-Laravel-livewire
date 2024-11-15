<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskComponent extends Component
{
    public $tasks = [];
    public $id;
    public $title;
    public $description;
    public $modal = false;
    public $isUpdating = false;

    public function mount()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function getTasks()
    {
        return Task::where('user_id', Auth::user()->id)->get();
    }

    private function clearFields()
    {
        $this->id = '';
        $this->title = '';
        $this->description = '';
        $this->isUpdating = false;
    }

    public function render()
    {
        return view('livewire.task-component');
    }

    public function openCreateModal(Task $task = null)
    {
        if($task)
        {
            $this->isUpdating = true;
            $this->id = $task->id;
            $this->title = $task->title;
            $this->description = $task->description;
        }
        else
        {
            $this->clearFields();
        }

        $this->modal = true;
    }

    public function closeCreateModal()
    {
        $this->modal = false;
    }

    public function createOrUpdateTask()
    {
        Task::updateOrCreate(
            [
                'id' => $this->id
            ],
            [
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => Auth::user()->id,
            ]
        );

        $this->clearFields();
        $this->modal = false;
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

}
