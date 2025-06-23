<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Filter Kamar</h5>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="filterTipe">Tipe Kamar</label>
            <select class="form-control" id="filterTipe">
                <option value="">Semua Tipe</option>
                <option value="standard">Standard</option>
                <option value="premium">Premium</option>
                <option value="vip">VIP</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="filterStatus">Status</label>
            <select class="form-control" id="filterStatus">
                <option value="">Semua Status</option>
                <option value="kosong">Kosong</option>
                <option value="dihuni">Dihuni</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="filterTarif">Rentang Tarif</label>
            <div class="row">
                <div class="col-6">
                    <input type="number" class="form-control" id="filterTarifMin" placeholder="Min">
                </div>
                <div class="col-6">
                    <input type="number" class="form-control" id="filterTarifMax" placeholder="Max">
                </div>
            </div>
        </div>
        
        <button class="btn btn-primary btn-block" id="applyFilters">
            <i class="fas fa-filter"></i> Terapkan Filter
        </button>
        <button class="btn btn-outline-secondary btn-block" id="resetFilters">
            <i class="fas fa-sync-alt"></i> Reset
        </button>
    </div>
</div>