<?= $this->include('templates/header') ?>

<style>
    /* Modern Hero Section - Role-Aware */
    .page-hero {
        padding: 40px 30px;
        margin: -20px -24px 30px -24px;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    /* Admin hero: deep navy to red */
    .page-hero.hero-admin {
        background: linear-gradient(135deg, rgba(30, 58, 95, 0.95), rgba(198, 40, 40, 0.85)) !important;
    }

    /* Staff hero: deep blue to teal */
    .page-hero.hero-staff {
        background: linear-gradient(135deg, rgba(15, 32, 65, 0.95), rgba(21, 101, 192, 0.85)) !important;
    }

    .page-hero h1 {
        color: white !important;
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-hero .subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 15px;
        margin-bottom: 20px;
    }
    
    .page-hero .stats-row {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
    }
    
    .page-hero .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
    }
    
    .page-hero .stat-item i {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .page-hero .stat-value {
        font-weight: 700;
        font-size: 18px;
        color: white;
    }
</style>

<?php $heroClass = (session()->get('role') === 'staff') ? 'hero-staff' : 'hero-admin'; ?>

<!-- Modern Page Hero -->
<div class="page-hero <?= $heroClass ?>">
    <h1><i class="fas fa-tags"></i> Route Fares</h1>
    <p class="subtitle">Official fare rates for all van and jeepney destinations</p>
    <div class="stats-row">
        <div class="stat-item">
            <img src="<?= base_url('images/van.png') ?>" alt="Van" style="width: 42px; height: auto;">
            <div>
                <div class="stat-value"><?= count($van_routes ?? []) ?></div>
                <small>Van Routes</small>
            </div>
        </div>
        <div class="stat-item">
            <img src="<?= base_url('images/jeep.png') ?>" alt="Jeepney" style="width: 42px; height: auto;">
            <div>
                <div class="stat-value"><?= count($jeepney_routes ?? []) ?></div>
                <small>Jeepney Routes</small>
            </div>
        </div>
        <div class="stat-item">
            <img src="<?= base_url('images/minibus.png') ?>" alt="Minibus" style="width: 42px; height: auto;">
            <div>
                <div class="stat-value"><?= count($minibus_routes ?? []) ?></div>
                <small>Minibus Routes</small>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <!-- Van Routes -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <img src="<?= base_url('images/van.png') ?>" alt="Van" style="width: 32px; height: auto;">Van Routes
                </h5>
                <span class="badge bg-info">Express</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush fare-list">
                    <?php if (!empty($van_routes)): ?>
                        <?php foreach ($van_routes as $route): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center fare-item">
                                <div>
                                    <strong class="d-block"><?= strtoupper(esc($route['origin'])) ?> - <?= strtoupper(esc($route['destination'])) ?></strong>
                                    <small class="text-muted">From: <?= esc($route['origin']) ?></small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success fs-6 px-3 py-2">₱<?= number_format($route['fare'], 0) ?></span>
                                    <?php if (in_array(session()->get('role'), ['admin', 'staff'])): ?>
                                    <a href="<?= base_url('admin/routes/edit/' . $route['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit Fare">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted py-4">No van fares listed.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Jeepney Routes -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <img src="<?= base_url('images/jeep.png') ?>" alt="Jeepney" style="width: 32px; height: auto;">Jeepney Routes
                </h5>
                <span class="badge bg-warning text-dark">Regular</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush fare-list">
                    <?php if (!empty($jeepney_routes)): ?>
                        <?php foreach ($jeepney_routes as $route): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center fare-item">
                                <div>
                                    <strong class="d-block"><?= strtoupper(esc($route['origin'])) ?> - <?= strtoupper(esc($route['destination'])) ?></strong>
                                    <small class="text-muted">From: <?= esc($route['origin']) ?></small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success fs-6 px-3 py-2">₱<?= number_format($route['fare'], 0) ?></span>
                                    <?php if (in_array(session()->get('role'), ['admin', 'staff'])): ?>
                                    <a href="<?= base_url('admin/routes/edit/' . $route['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit Fare">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted py-4">No jeepney fares listed.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Minibus Routes -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <img src="<?= base_url('images/minibus.png') ?>" alt="Minibus" style="width: 32px; height: auto;">Minibus Routes
                </h5>
                <span class="badge" style="background:#6d28d9;">Minibus</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush fare-list">
                    <?php if (!empty($minibus_routes)): ?>
                        <?php foreach ($minibus_routes as $route): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center fare-item">
                                <div>
                                    <strong class="d-block"><?= strtoupper(esc($route['origin'])) ?> - <?= strtoupper(esc($route['destination'])) ?></strong>
                                    <small class="text-muted">From: <?= esc($route['origin']) ?></small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success fs-6 px-3 py-2">₱<?= number_format($route['fare'], 0) ?></span>
                                    <?php if (in_array(session()->get('role'), ['admin', 'staff'])): ?>
                                    <a href="<?= base_url('admin/routes/edit/' . $route['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit Fare">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted py-4">No minibus fares listed.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?= $this->include('templates/footer') ?>

<script>
function filterFares() {
    const query = document.getElementById('fareSearch').value.toLowerCase();
    document.querySelectorAll('.fare-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(query) ? '' : 'none';
    });
}
</script>