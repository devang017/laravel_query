@if ($paginator->hasPages())
<div class="mt-6 flex justify-between items-center">

    <p class="text-gray-500 text-sm">
        Showing {{ $paginator->firstItem() ?? 0 }}
        to {{ $paginator->lastItem() ?? 0 }}
        of {{ number_format($paginator->total()) }} users
    </p>

    <div class="flex gap-2">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
        <span class="border px-4 py-2 rounded-lg text-gray-400 cursor-not-allowed">
            Previous
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" class="border px-4 py-2 rounded-lg hover:bg-gray-100">
            Previous
        </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)

        {{-- Separator --}}
        @if (is_string($element))
        <span class="px-3 py-2">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)

        @if ($page == $paginator->currentPage())
        <span class="bg-indigo-600 text-white px-4 py-2 rounded-lg">
            {{ $page }}
        </span>
        @else
        <a href="{{ $url }}" class="border px-4 py-2 rounded-lg hover:bg-gray-100">
            {{ $page }}
        </a>
        @endif

        @endforeach
        @endif

        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="border px-4 py-2 rounded-lg hover:bg-gray-100">
            Next
        </a>
        @else
        <span class="border px-4 py-2 rounded-lg text-gray-400 cursor-not-allowed">
            Next
        </span>
        @endif

    </div>

</div>
@endif