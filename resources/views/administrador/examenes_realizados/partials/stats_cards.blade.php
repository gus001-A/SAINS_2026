<div class="col-sm-6 col-md-3">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-primary">
        <div class="card-body p-3 p-xl-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Total Exámenes</p>
                    <h2 class="display-4 fw-bold mb-0">{{ $stats['total'] ?? 0 }}</h2>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-database me-1"></i> Registros
                    </p>
                </div>
                <div class="rounded-3 p-3 stat-icon">
                    <i class="fas fa-file-alt fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-6 col-md-3">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-info">
        <div class="card-body p-3 p-xl-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Promedio General</p>
                    <h2 class="display-4 fw-bold mb-0">{{ $stats['promedio'] ?? 0 }}<span class="fs-2">%</span></h2>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-chart-line me-1"></i> Calificación promedio
                    </p>
                </div>
                <div class="rounded-3 p-3 stat-icon">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-6 col-md-3">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-success">
        <div class="card-body p-3 p-xl-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Excelentes</p>
                    <h2 class="display-4 fw-bold mb-0">{{ $stats['excelentes'] ?? 0 }}</h2>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-star me-1"></i> ≥80%
                    </p>
                </div>
                <div class="rounded-3 p-3 stat-icon">
                    <i class="fas fa-star fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-6 col-md-3">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card stat-card-danger">
        <div class="card-body p-3 p-xl-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Reprobados</p>
                    <h2 class="display-4 fw-bold mb-0">{{ $stats['reprobados'] ?? 0 }}</h2>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i> &lt;60%
                    </p>
                </div>
                <div class="rounded-3 p-3 stat-icon">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>