<!-- component -->
<div class="container">
    <button wire:click="openCreateModal" class="bg-purple-800 text-white px-4 py-2 my-6 rounded-md hover:bg-purple-700">Nuevo</button>
	<table class="text-center w-full">
		<thead class="bg-purple-800 flex text-white w-full">
			<tr class="flex w-full mb-4">
				<th class="p-4 w-1/3">One</th>
				<th class="p-4 w-1/3">Two</th>
				<th class="p-4 w-1/3">Three</th>
			</tr>
		</thead>
    <!-- Remove the nasty inline CSS fixed height on production and replace it with a CSS class — this is just for demonstration purposes! -->
		<tbody class="bg-grey-light flex flex-col items-center justify-between w-full">
            @foreach ($tasks as $task)
            <tr class="flex w-full mb-4">
				<td class="p-4 w-1/3">{{ $task->title }}</td>
				<td class="p-4 w-1/3">{{ $task->description }}</td>
				<td class="p-4 w-1/3">
                    <button wire:click="openCreateModal({{$task}})" class="bg-yellow-800 text-white px-4 py-2 rounded-md hover:bg-yellow-700">Editar</button>
                    <button wire:click="deleteTask({{$task}})" class="bg-red-800 text-white px-4 py-2 rounded-md hover:bg-red-500">Borrar</button>
                </td>
			</tr>
            @endforeach
		</tbody>
	</table>

    @if ($modal)
    <!-- component -->
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
</div>

