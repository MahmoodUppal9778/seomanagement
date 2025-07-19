<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Upload Excel File</h1>

                    @if (session('success'))
                        <p>{{ session('success') }}</p>
                    @endif
                
                    <form action="{{ route('sites.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".xlsx">
                        <button type="submit">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
