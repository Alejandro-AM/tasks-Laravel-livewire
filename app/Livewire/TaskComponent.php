<?php

namespace App\Livewire;

use App\Jobs\RemoveAllTasks;
use App\Mail\SharedTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class TaskComponent extends Component
{
    public $tasks = [];
    public $users = [];
    public $id;
    public $title;
    public $description;
    public $user_id;
    public $permission;
    public $modal = false;
    public $modalShare = false;
    public $isUpdating = false;

    public function mount()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
        $this->users = User::where('id', '!=', auth()->user()->id)->get();
    }

    public function renderAllTasks()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function render()
    {
        return view('livewire.task-component');
    }

    public function getTasks()
    {
        $user = auth()->user();
        $myTasks = Task::where('user_id', Auth::user()->id)->get();
        $mySharedTasks = $user->sharedTasks()->get();

        return $mySharedTasks->merge($myTasks);
    }

    private function clearFields()
    {
        $this->id = '';
        $this->title = '';
        $this->description = '';
        $this->isUpdating = false;
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
        if($this->id)
        {
            $task = Task::find($this->id);
            $task->update([
                'title' => $this->title,
                'description' => $this->description,
            ]);
        }
        else
        {
            $task = Task::create([
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => Auth::user()->id,
            ]);
        }

        $this->clearFields();
        $this->modal = false;
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function openShareModal(Task $task)
    {
        $this->modalShare = true;
        $this->id = $task->id;
    }

    public function closeShareModal()
    {
        $this->modalShare = false;
    }

    public function shareTask()
    {
        $task = Task::find($this->id);
        $user = User::find($this->user_id);
        $user->sharedTasks()->attach($task->id, ['permission' => $this->permission]);
        $this->closeShareModal();
        $this->tasks = $this->getTasks()->sortByDesc('id');
        $this->clearFields();

        Mail::to($user->email)->queue(new SharedTask($task, Auth::user()));
    }

    public function taskUnshared(Task $task)
    {
        $user = User::find(Auth::user()->id);
        $user->sharedTasks()->detach($task->id);
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function removeAllTasks()
    {
        $user = User::find(Auth::user()->id);
        RemoveAllTasks::dispatch($user);
    }

    public function recoverAllTasks()
    {
        $user = User::find(Auth::user()->id);
        $user->tasks()->restore();
        $this->renderAllTasks();
    }
}
