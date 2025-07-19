{{-- resources/views/rules.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Pricing Rules') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Success message --}}
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Form to dynamically add rules --}}
                    <form id="rules-form" method="POST" action="{{ route('apply.rules') }}">
                        @csrf

                        <div id="rules-container">
                            <!-- Rule Template (will be cloned) -->
                            <div class="rule mb-6 p-4 bg-gray-50 rounded-lg shadow-sm">
                                <div class="flex flex-wrap gap-4">
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
                                        <input type="number" name="min_price[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                                        <input type="number" name="max_price[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <small class="text-gray-500 text-xs">Leave empty for infinite max</small>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Increment Value</label>
                                        <input type="number" name="increment_value[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                </div>
                                <button type="button" class="remove-rule-btn mt-3 text-sm text-red-500 hover:text-red-700 focus:outline-none">
                                    Remove Rule
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-4">
                            <button type="button" id="add-rule-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Add Another Rule
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                Apply Rules
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-rule-btn').addEventListener('click', function() {
            // Clone the first rule block
            var ruleTemplate = document.querySelector('.rule').cloneNode(true);

            // Clear the input values in the cloned rule
            ruleTemplate.querySelectorAll('input').forEach(function(input) {
                input.value = '';
            });

            // Append the cloned rule to the rules container
            document.getElementById('rules-container').appendChild(ruleTemplate);

            // Add event listener to the new remove button
            ruleTemplate.querySelector('.remove-rule-btn').addEventListener('click', function() {
                ruleTemplate.remove();
            });
        });

        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-rule-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                button.closest('.rule').remove();
            });
        });
    </script>
</x-app-layout>