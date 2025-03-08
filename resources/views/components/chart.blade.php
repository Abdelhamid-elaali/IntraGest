@props([
    'id',
    'type' => 'line',
    'height' => '300px',
    'data' => [],
    'options' => []
])

<div class="bg-white rounded-lg shadow p-4">
    <canvas id="{{ $id }}" style="height: {{ $height }}"></canvas>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id }}').getContext('2d');
    const chartData = @json($data);
    const chartOptions = @json($options);

    new Chart(ctx, {
        type: '{{ $type }}',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            ...chartOptions
        }
    });
});
</script>
@endpush
