<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use function Laravel\Prompts\form;

class DeleteTaskPending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deletetask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Borra todas las tareas que estan con softDelete';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //Borrar tareas que el delete_at es mayor a 5 días
        Task::where('deleted_at', '!=', null)
        ->where('deleted_at', '<', now()->subDays(5))
        ->forceDelete();
    }
}
