<div class="status-legend">
    <div class="d-flex align-items-center">
        <span class="legend-color bg-success"></span>
        <span class="legend-label">Kosong</span>
        <span class="badge badge-light ml-2">{{ $kamars->where('status', 'kosong')->count() }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="legend-color bg-danger"></span>
        <span class="legend-label">Dihuni</span>
        <span class="badge badge-light ml-2">{{ $kamars->where('status', 'dihuni')->count() }}</span>
    </div>
    <div class="d-flex align-items-center">
        <span class="legend-color bg-warning"></span>
        <span class="legend-label">Maintenance</span>
        <span class="badge badge-light ml-2">{{ $kamars->where('status', 'maintenance')->count() }}</span>
    </div>
</div>