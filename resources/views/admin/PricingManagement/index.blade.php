{{-- resources/views/admin/pricing-management/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Pricing Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto p-8 space-y-8">
        <!-- Page Title -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pricing Management</h1>
            <p class="mt-2 text-gray-600">
                Manage hierarchical pricing across grades, subjects, topics, and subtopics
            </p>
        </div>

        <!-- Platform Fee Card -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold">Platform Fee</h2>
            </div>
            <div class="p-6 space-y-4">
                <form id="platform-fee-form" action="{{ route('admin.platform-fee.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2">
                        <label for="platform-fee" class="block text-sm font-medium text-gray-700">
                            Platform Service Fee (%)
                        </label>
                        <input
                            id="platform-fee"
                            name="fee_percentage"
                            type="number"
                            min="0"
                            max="100"
                            step="0.1"
                            value="{{ old('fee_percentage', $platformFee) }}"
                            class="border border-gray-300 rounded-md px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        <p class="text-sm text-gray-600">
                            This fee applies to all teacher transactions.
                        </p>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Save Platform Fee
                    </button>
                </form>
                @if(session('success'))
                    <p class="mt-2 text-green-600">{{ session('success') }}</p>
                @endif
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav id="breadcrumb" class="hidden items-center gap-2 py-4 px-6 bg-gray-100 rounded-lg text-sm"></nav>

        <!-- Main Content -->
        <div id="content"></div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script>
    const pricingData = @json($grades);

    let currentData = { grades: pricingData };
    let navigation = { level: "grades" };

    // Paste all the same JavaScript functions from before (calc prices, renderGrades, renderSubjects, etc.)

    // --- [Paste the full JavaScript logic here] ---
    // Include all functions: calcTopicPrices, calcSubjectPrices, calcGradePrices, renderGrades(), renderSubjects(), etc.
    // Also include editing logic with AJAX save

    // Example: Save subtopic price via AJAX
    function saveEdit(code) {
        const min = Number(document.getElementById(`edit-min-${code}`).value);
        const max = Number(document.getElementById(`edit-max-${code}`).value);

        if (min < 0 || max < 0 || max <= min) {
            alert("Invalid prices");
            return;
        }

        fetch('/admin/subtopic-pricing/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ subtopic_code: code, min_price: min, max_price: max })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update local data
                updateSubtopicPrice(code, min, max);
                renderSubtopics(getCurrentTopic());
                alert("Price updated successfully!");
            }
        });
    }

    // Initial render
    renderGrades();
</script>
@endpush
@endsection