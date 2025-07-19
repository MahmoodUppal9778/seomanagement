<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Domain Matching Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 bg-blue-50 p-4 rounded-md">
                        <h3 class="font-medium text-blue-800">Matching Results:</h3>
                        <p class="mt-2 text-blue-700">
                            Found {{ $matchedCount }} matches out of {{ $totalDomains }} domains in your file.
                        </p>

                        @if($matchedCount > 0)
                            <!-- Button to open the export modal -->
                            <button id="open-export-modal" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                Export Matches to Excel
                            </button>
                        @endif
                    </div>

                    @if($matchedCount > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niche</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Post Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Guest Post</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link Insertion Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale Link Insertion</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($matches as $site)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $site->domain }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $site->niche }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $site->currency }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $site->price_guest_post }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $site->sale_price_guest_post }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $site->price_link_insertion }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $site->sale_price_link_insertion }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No matching domains found in your database.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Column Selection Modal -->
    <div id="export-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Select Columns to Export</h2>
            <!-- Change the form method to POST -->
            <form id="export-form" action="{{ route('sites.export-domain-matches') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="columns[]" value="domain" class="mr-2" checked> Domain
                    </label>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="columns[]" value="currency" class="mr-2" checked> Currency
                    </label>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="columns[]" value="price_guest_post" class="mr-2" checked> Price Guest Post
                    </label>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="columns[]" value="sale_price_guest_post" class="mr-2" checked> Sale Price Guest Post
                    </label>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="columns[]" value="price_link_insertion" class="mr-2" checked> Price Link Insertion
                    </label>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="columns[]" value="sale_price_link_insertion" class="mr-2" checked> Sale Price Link Insertion
                    </label>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="columns[]" value="niche" class="mr-2" checked> Niche
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="cancel-btn" class="px-4 py-2 bg-gray-500 text-white rounded-md mr-2 hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Open the modal when the export button is clicked
        document.getElementById("open-export-modal").addEventListener("click", function() {
            document.getElementById("export-modal").classList.remove("hidden");
        });

        // Close the modal when the cancel button is clicked
        document.getElementById("cancel-btn").addEventListener("click", function() {
            document.getElementById("export-modal").classList.add("hidden");
        });
    </script>
</x-app-layout>
