<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Recipes') }}
            </h2>
            <a href="{{ route('recipes.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">
                + Add Recipe
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($recipes->isEmpty())
                        <p class="text-gray-500 text-center py-8">You don't have any recipes added yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 border-b text-left text-sm font-medium text-gray-500">Photo</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-medium text-gray-500">Title</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-medium text-gray-500">Status</th>
                                    <th class="py-3 px-4 border-b text-left text-sm font-medium text-gray-500">Date</th>
                                    <th class="py-3 px-4 border-b text-right text-sm font-medium text-gray-500">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recipes as $recipe)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 border-b">
                                            <img src="{{ $recipe->image_url }}" alt="img" class="w-12 h-12 rounded object-cover">
                                        </td>
                                        <td class="py-3 px-4 border-b font-medium">{{ $recipe->title }}</td>
                                        <td class="py-3 px-4 border-b">
                                            @if($recipe->status === 'published')
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Published</span>
                                            @else
                                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Draft</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b text-sm text-gray-500">{{ $recipe->created_at->format('d.m.Y') }}</td>
                                        <td class="py-3 px-4 border-b text-right space-x-2">

                                            <a href="{{ route('recipes.show', $recipe) }}" class="text-blue-500 hover:underline text-sm">View</a>

                                            <a href="{{ route('recipes.edit', $recipe) }}" class="text-indigo-500 hover:underline text-sm">Edit</a>

                                            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $recipes->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
