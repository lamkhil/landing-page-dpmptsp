@props(['title', 'unit' => null, 'series', 'color' => '#0E4DA4'])

@php
    // $series: Collection<{year:int, value:float, label:?string}>
    $categories = $series->pluck('year')->all();
    $values     = $series->pluck('value')->all();
    $config = [
        'chart' => [
            'type'      => 'area',
            'height'    => 280,
            'toolbar'   => ['show' => false],
            'zoom'      => ['enabled' => false],
            'animations'=> ['enabled' => true, 'speed' => 600],
            'fontFamily'=> 'Inter, system-ui, sans-serif',
        ],
        'colors'      => [$color],
        'stroke'      => ['curve' => 'smooth', 'width' => 3],
        'fill'        => [
            'type'     => 'gradient',
            'gradient' => ['shadeIntensity' => 0.6, 'opacityFrom' => 0.35, 'opacityTo' => 0.05],
        ],
        'dataLabels'  => ['enabled' => false],
        'xaxis'       => ['categories' => $categories, 'labels' => ['style' => ['colors' => '#64748b']]],
        'yaxis'       => ['labels' => ['style' => ['colors' => '#64748b']]],
        'grid'        => ['borderColor' => '#e2e8f0', 'strokeDashArray' => 4],
        'tooltip'     => ['theme' => 'light', 'y' => ['formatter' => null]],
        'series'      => [['name' => $title, 'data' => $values]],
    ];
@endphp

<div class="card-padded">
    <div class="flex items-start justify-between">
        <div>
            <p class="heading-eyebrow">Tren 5 Tahun</p>
            <h3 class="mt-1 font-semibold text-lg">{{ $title }}</h3>
        </div>
        @if ($unit)
            <span class="chip">{{ $unit }}</span>
        @endif
    </div>
    <div class="mt-4" x-data='apexChart(@json($config))' x-intersect.once="$nextTick(() => init())"></div>
</div>
