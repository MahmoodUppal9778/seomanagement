<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="GET" class="mb-4">
                        <input type="text" name="search" placeholder="Search logs..." value="{{ request('search') }}"
                               class="form-input px-4 py-2 border rounded-md w-full md:w-1/3">
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="py-2 px-4 border">Date</th>
                                    <th class="py-2 px-4 border">User</th>
                                    <th class="py-2 px-4 border">Log</th>
                                    <th class="py-2 px-4 border">Properties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $activity)
                                    <tr>
                                        <td class="py-2 px-4 border">{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="py-2 px-4 border">
                                            {{ $activity->causer ? $activity->causer->name : 'System' }}
                                        </td>
                                        <td class="py-2 px-4 border">{{ $activity->description }}</td>
                                        <td class="py-2 px-4 border text-sm text-gray-600">
                                            <pre class="whitespace-pre-wrap">{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT) }}</pre>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">No activity found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $activities->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
