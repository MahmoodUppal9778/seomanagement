<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Domain Matching Tool') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-lg font-semibold mb-4">Upload Excel File with Domains</h1>
                    
                    <form action="{{ route('domain.matching.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="excel_file" class="block text-sm font-medium text-gray-700">
                                Excel File (XLSX, XLS, CSV)
                            </label>
                            <input type="file" name="excel_file" id="excel_file" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @error('excel_file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Match Domains
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6 bg-blue-50 p-4 rounded-md">
                        <h3 class="font-medium text-blue-800">Instructions:</h3>
                        <ul class="list-disc pl-5 mt-2 text-blue-700">
                            <li>Upload an Excel file containing domains in the first column</li>
                            <li>The system will compare these domains with your database</li>
                            <li>You'll receive a report of matching domains with their details</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>