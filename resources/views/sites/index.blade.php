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
                    <h1 class="text-lg font-semibold mb-4">Filter Sites</h1>

                    <!-- Filter Form -->
                    <form id="filterForm" method="GET">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <input type="text" name="domain" placeholder="Domain" class="form-input px-4 py-2 border rounded-md">
                            <input type="text" name="currency" placeholder="Currency" class="form-input px-4 py-2 border rounded-md">
                            <input type="text" name="niche" placeholder="Niche" class="form-input px-4 py-2 border rounded-md">
                            <input type="number" name="price_guest_post" placeholder="Max Price for Guest Post" class="form-input px-4 py-2 border rounded-md">
                        </div>
                        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md">Search</button>
                    </form>

                    <!-- Export to Excel Button -->
                    <div class="mt-4 mb-6">
                        <button id="export-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Export to Excel
                        </button>
                    </div>

                    <!-- Modal for Column Selection -->
                    <!-- Modal for Column Selection -->
                    <div id="export-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                            <h2 class="text-xl font-semibold mb-4">Select Columns to Export</h2>
                            <form id="export-form" method="GET" action="{{ route('export.excel') }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="columns[]" value="domain" class="mr-2" checked>
                                        Domain
                                    </label>
                                </div>
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="columns[]" value="currency" class="mr-2" checked>
                                        Currency
                                    </label>
                                </div>
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="columns[]" value="price_guest_post" class="mr-2" checked>
                                        Price Guest Post
                                    </label>
                                </div>
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="columns[]" value="price_link_insertion" class="mr-2" checked>
                                        Price Link Insertion
                                    </label>
                                </div>
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="columns[]" value="niche" class="mr-2" checked>
                                        Niche
                                    </label>
                                </div>

                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="columns[]" value="sale_price_guest_post" class="mr-2" checked>
                                        Sale Price Guest Post
                                    </label>
                                </div>
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="columns[]" value="sale_price_link_insertion" class="mr-2" checked>
                                        Sale Price Link Insertion
                                    </label>
                                </div>

                                <input type="hidden" id="export-filters" name="filters">

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

                    <!-- Table Results -->
                    <div id="siteResults" class="mt-6">
                        <!-- AJAX-loaded results will be injected here -->
                        @include('sites.partials.site-list', ['sites' => $sites])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AJAX Script -->
    <script>
        $(document).ready(function () {
            // AJAX filtering and pagination
            $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            
            // Serialize and filter empty inputs
            var formData = $('#filterForm').serializeArray().filter(function(item) {
                return item.value.trim() !== '';  // Only keep fields that are not empty
            });

            var queryString = $.param(formData);

            loadSites(queryString);  // Pass only non-empty fields to loadSites
        });

        // Handle pagination clicks
        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            loadSites(url.split('?')[1]);  // Get the query string from pagination links
        });
        // Export Modal Global Script
        $(document).on('click', '#export-btn', function () {
            var formData = $('#filterForm').serializeArray();
            var filteredData = formData.filter(function(item) {
                return item.value.trim() !== '';
            });

            var queryString = $.param(filteredData);
            $('#export-filters').val(queryString);
            $('#export-modal').removeClass('hidden');
        });

            $(document).on('click', '#cancel-btn', function () {
                $('#export-modal').addClass('hidden');
            });

            // Load sites with filters and pagination
            function loadSites(queryString = '') {
                var url = "{{ url('sites') }}";
                if (queryString) {
                    url += '?' + queryString;
                }

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function (response) {
                        // Inject only the partial content (the table or site list)
                        $('#siteResults').html(response);  // Update only the results section
                    },
                    error: function () {
                        alert('Failed to load sites. Please try again.');
                    }
                });
            }

        });

    </script>

    <!-- Custom CSS for Responsiveness -->
    <style>
        /* Ensure the table is scrollable on small screens */
        .table-responsive {
            overflow-x: auto;
        }

        /* Adjust table header and cell padding on smaller screens */
        @media (max-width: 640px) {
            .table-responsive th, .table-responsive td {
                padding: 8px 4px;
                font-size: 14px;
            }

            .table-responsive th {
                white-space: nowrap;
            }
        }

        /* Adjust filter form grid for smaller screens */
        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        /* Ensure pagination links are responsive */
        .pagination {
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            margin: 2px;
            padding: 8px 12px;
            font-size: 14px;
        }
    </style>
</x-app-layout>
