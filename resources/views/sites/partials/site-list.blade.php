<div class="table-responsive">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider text-sm">
                    <a href="?sort_by=domain&sort_order={{ request('sort_by') === 'domain' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}" class="hover:underline">
                        Domain
                        @if(request('sort_by') === 'domain')
                            @if(request('sort_order') === 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </a>
                </th>
                <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider text-sm">
                    <a href="?sort_by=currency&sort_order={{ request('sort_by') === 'currency' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}" class="hover:underline">
                        Currency
                        @if(request('sort_by') === 'currency')
                            @if(request('sort_order') === 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </a>
                </th>
                <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider text-sm">
                    <a href="?sort_by=price_guest_post&sort_order={{ request('sort_by') === 'price_guest_post' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}" class="hover:underline">
                        Price Guest Post
                        @if(request('sort_by') === 'price_guest_post')
                            @if(request('sort_order') === 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </a>
                </th>
                <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider text-sm">
                    <a href="?sort_by=price_link_insertion&sort_order={{ request('sort_by') === 'price_link_insertion' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}" class="hover:underline">
                        Price Link Insertion
                        @if(request('sort_by') === 'price_link_insertion')
                            @if(request('sort_order') === 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </a>
                </th>
                <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider text-sm">
                    <a href="?sort_by=niche&sort_order={{ request('sort_by') === 'niche' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}" class="hover:underline">
                        Niche
                        @if(request('sort_by') === 'niche')
                            @if(request('sort_order') === 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </a>
                </th>
                <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider text-sm">
                    <a href="?sort_by=sale_price_guest_post&sort_order={{ request('sort_by') === 'sale_price_guest_post' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}" class="hover:underline">
                        Sale Price Guest Post
                        @if(request('sort_by') === 'sale_price_guest_post')
                            @if(request('sort_order') === 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </a>
                </th>
                <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider text-sm">
                    <a href="?sort_by=sale_price_link_insertion&sort_order={{ request('sort_by') === 'sale_price_link_insertion' && request('sort_order') === 'asc' ? 'desc' : 'asc' }}" class="hover:underline">
                        Sale Price Link Insertion
                        @if(request('sort_by') === 'sale_price_link_insertion')
                            @if(request('sort_order') === 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @endif
                    </a>
                </th>

            </tr>
        </thead>
        <tbody class="bg-white">
            @if($sites->isEmpty())
                <tr>
                    <td colspan="6" class="text-center px-6 py-4 text-gray-500">No sites found.</td>
                </tr>
            @else
                @foreach($sites as $site)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-700">{{ $site->domain }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $site->currency }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $site->price_guest_post }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $site->price_link_insertion }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $site->niche }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $site->sale_price_guest_post }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $site->sale_price_link_insertion }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="mt-4">
        <nav role="navigation" aria-label="Pagination" class="inline-flex rounded-md shadow-sm">
            {{ $sites->appends(request()->query())->links('pagination::tailwind') }}
        </nav>
    </div>
</div>
