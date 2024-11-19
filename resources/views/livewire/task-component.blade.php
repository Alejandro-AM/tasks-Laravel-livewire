<!-- component -->
<div class="container" wire:poll="renderAllTasks">
    <button wire:click="openCreateModal" class="bg-purple-800 text-white px-4 py-2 my-6 rounded-md hover:bg-purple-700">Nuevo</button>
    <button wire:click="removeAllTasks" wire:confirm="Estas seguro que quieres eliminar todas la tareas?" class="bg-red-800 text-white px-4 py-2 my-6 rounded-md hover:bg-red-700">Borrar todas las tareas</button>
    <button wire:click="recoverAllTasks" wire:confirm="Estas seguro que quieres recuperar todas la tareas?" class="bg-green-800 text-white px-4 py-2 my-6 rounded-md hover:bg-green-700">Recuperar todas las tareas</button>
	<table class="text-center w-full">
		<thead class="bg-purple-800 flex text-white w-full">
			<tr class="flex w-full mb-4">
				<th class="p-4 w-1/3">Title</th>
				<th class="p-4 w-1/3">Description</th>
				<th class="p-4 w-1/3">Options</th>
			</tr>
		</thead>
    <!-- Remove the nasty inline CSS fixed height on production and replace it with a CSS class — this is just for demonstration purposes! -->
		<tbody class="flex flex-col items-center justify-between w-full">
            @foreach ($tasks as $task)
            <tr class="flex w-full">
				<td class="p-4 w-1/3 content-center">{{ $task->title }}</td>
				<td class="p-4 w-1/3 content-center bg-purple-100">{{ $task->description }}</td>
                @if ((isset($task->pivot) && $task->pivot->permission == 'edit') || auth()->user()->id == $task->user_id)
                <td class="p-4 w-1/3 content-center">
                    <button wire:click="openCreateModal({{$task}})" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-500">Editar</button>
                    @if (isset($task->pivot))
                    <button wire:click="taskUnshared({{$task}})" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-blue-500">Desc</button>
                    @endif
                    <button wire:click="openShareModal({{$task}})" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-500">Compartir</button>
                    <button wire:click="deleteTask({{$task}})" wire:confirm="Are you want to delete this task?" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-500">Borrar</button>
                </td>
                @endif
			</tr>
            @endforeach
		</tbody>
	</table>

    @if ($modal)
    <!-- modal form-->
    <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">
        <div class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
        <div class="w-full">
            <div class="m-8 my-20 max-w-[400px] mx-auto">
            <div class="mb-8">
                <h1 class="mb-4 text-3xl font-extrabold">Crear tarea</h1>
                <form>
                    <div class="mb-4">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 text-gray-300">Título</label>
                        <input wire:model="title" type="text" id="title" name="title" class="bg-gray-50 border border-gray-300 w-full" placeholder="Agregar un titulo">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 text-gray-300">Descripción</label>
                        <input wire:model="description" type="text" id="description" name="description" class="bg-gray-50 border border-gray-300 w-full" placeholder="Agregar una descripción">
                    </div>
                </form>
            </div>
            <div class="space-y-4">
                <button wire:click="createOrUpdateTask" class="p-3 bg-black rounded-full text-white w-full font-semibold">
                    {{isset($id) ? 'Actualizar' : 'Crear'}} tarea
                </button>
                <button wire:click="closeCreateModal" class="p-3 bg-white border rounded-full w-full font-semibold">Cancelar</button>
            </div>
            </div>
        </div>
        </div>
    </div>
    @endif

    @if ($modalShare)
    <!-- modal share -->
    <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">
        <div class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
        <div class="w-full">
            <div class="m-8 my-20 max-w-[400px] mx-auto">
            <div class="mb-8">
                <h1 class="mb-4 text-3xl font-extrabold">Compartir tarea</h1>
                <form>
                    <div class="mb-4">
                        <label for="userToShare" class="mb-2 text-sm font-medium text-gray-500">Usuario a compartir</label>
                        <select wire:model="user_id" name="userToShare" id="userToShare" class="block w-full bg-gray-50 border border-gray-300 text-sm rounded-sm focus:ring-blue-500">
                            <option value="">Seleccione un usuario</option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="permission" class="mb-2 text-sm font-medium text-gray-500">Permisos</label>
                        <select wire:model="permission" name="permission" id="permission" class="block w-full bg-gray-50 border border-gray-300 text-sm rounded-sm focus:ring-blue-500">
                            <option value="">Seleccione el tipo de permiso</option>
                            <option value="view">Ver</option>
                            <option value="edit">Editar</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="space-y-4">
                <button wire:click="shareTask" class="p-3 bg-black rounded-full text-white w-full font-semibold">
                    Compartir tarea
                </button>
                <button wire:click="closeShareModal" class="p-3 bg-white border rounded-full w-full font-semibold">Cancelar</button>
            </div>
            </div>
        </div>
        </div>
    </div>
    @endif
</div>

