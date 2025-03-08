@props([
    'method' => 'POST',
    'action' => '',
    'hasFiles' => false,
    'submit' => 'Submit',
    'submitColor' => 'blue',
    'cancel' => null,
    'grid' => false,
    'columns' => '2'
])

@php
$spoofedMethods = ['PUT', 'PATCH', 'DELETE'];
$shouldSpoofMethod = in_array(strtoupper($method), $spoofedMethods);
$gridCols = [
    '2' => 'sm:grid-cols-2',
    '3' => 'sm:grid-cols-3',
    '4' => 'sm:grid-cols-4'
];
$gridClass = $grid ? 'grid gap-6 ' . ($gridCols[$columns] ?? 'sm:grid-cols-2') : 'space-y-6';
@endphp

<form
    method="{{ $shouldSpoofMethod ? 'POST' : $method }}"
    action="{{ $action }}"
    {!! $hasFiles ? 'enctype="multipart/form-data"' : '' !!}
    {{ $attributes->merge(['class' => $gridClass]) }}
>
    @csrf
    @if($shouldSpoofMethod)
        @method($method)
    @endif

    {{ $slot }}

    <div class="{{ $grid ? 'sm:col-span-' . $columns : '' }} border-t border-gray-200 pt-5">
        <div class="flex justify-end space-x-3">
            @if($cancel)
                <a
                    href="{{ $cancel }}"
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Cancel
                </a>
            @endif

            <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-{{ $submitColor }}-600 hover:bg-{{ $submitColor }}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $submitColor }}-500"
            >
                {{ $submit }}
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission with validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Remove any existing error messages
            form.querySelectorAll('.error-message').forEach(el => el.remove());
            form.querySelectorAll('.error-field').forEach(el => {
                el.classList.remove('error-field', 'border-red-300', 'text-red-900', 'placeholder-red-300', 'focus:ring-red-500', 'focus:border-red-500');
            });

            // Check HTML5 validation
            if (!form.checkValidity()) {
                e.preventDefault();

                // Add error styling to invalid fields
                form.querySelectorAll(':invalid').forEach(field => {
                    field.classList.add('error-field', 'border-red-300', 'text-red-900', 'placeholder-red-300', 'focus:ring-red-500', 'focus:border-red-500');
                    
                    // Add error message
                    const errorMessage = document.createElement('p');
                    errorMessage.classList.add('error-message', 'mt-2', 'text-sm', 'text-red-600');
                    errorMessage.textContent = field.validationMessage;
                    field.parentNode.appendChild(errorMessage);
                });
            }
        });
    });

    // Handle file input styling
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const label = input.nextElementSibling;
            if (label && label.tagName === 'LABEL') {
                const span = label.querySelector('span');
                if (span) {
                    span.textContent = fileName || 'No file chosen';
                }
            }
        });
    });
});
</script>
@endpush
