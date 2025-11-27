
    <style>
        .card {
            border: 1px solid rgba(0,0,0,.125);
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .activity-timeline .timeline-item {
            position: relative;
            padding-left: 30px;
        }
        .activity-timeline .timeline-item:before {
            content: '';
            position: absolute;
            left: 4px;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: #e9ecef;
        }
        .activity-timeline .timeline-item:after {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            height: 10px;
            width: 10px;
            border-radius: 50%;
            background-color: #0d6efd;
        }
    </style>



<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2 class="h3">Dashboard</h2>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Total Members -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-3 me-3">
                            <i class="bi bi-people fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Members</h6>
                            <h3 class="card-title mb-0">547</h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> 12% increase
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Living Members -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 rounded-3 me-3">
                            <i class="bi bi-person-heart fs-4 text-success"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Living Members</h6>
                            <h3 class="card-title mb-0">324</h3>
                            <small class="text-muted">59% of total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generations -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 rounded-3 me-3">
                            <i class="bi bi-diagram-3 fs-4 text-info"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Generations</h6>
                            <h3 class="card-title mb-0">7</h3>
                            <small class="text-info">Oldest: 1875</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Countries -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-3 me-3">
                            <i class="bi bi-globe-americas fs-4 text-warning"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Countries</h6>
                            <h3 class="card-title mb-0">12</h3>
                            <small class="text-muted">4 continents</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <!-- Recent Activity -->
        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                    <button class="btn btn-sm btn-outline-primary">View All</button>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        <div class="timeline-item mb-3">
                            <div class="text-muted small mb-1">2 hours ago</div>
                            <div class="fw-medium">New member added</div>
                            <div class="text-muted small">Sarah Johnson added Emma Thompson to the family tree</div>
                        </div>
                        <div class="timeline-item mb-3">
                            <div class="text-muted small mb-1">Yesterday</div>
                            <div class="fw-medium">Profile updated</div>
                            <div class="text-muted small">Michael Brown updated marriage date for James & Mary Wilson</div>
                        </div>
                        <div class="timeline-item mb-3">
                            <div class="text-muted small mb-1">3 days ago</div>
                            <div class="fw-medium">Photos added</div>
                            <div class="text-muted small">Lisa Anderson uploaded 5 family photos from 1965</div>
                        </div>
                        <div class="timeline-item">
                            <div class="text-muted small mb-1">1 week ago</div>
                            <div class="fw-medium">Document shared</div>
                            <div class="text-muted small">Robert Davis shared marriage certificate from 1932</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Age Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="ageChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Family Events -->
        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Upcoming Events</h5>
                    <button class="btn btn-sm btn-outline-primary">Add Event</button>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Sarah's Birthday</h6>
                                <small class="text-primary">Tomorrow</small>
                            </div>
                            <small class="text-muted">Turns 35 • New York, USA</small>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">James & Emma Anniversary</h6>
                                <small class="text-muted">In 5 days</small>
                            </div>
                            <small class="text-muted">25th Anniversary • London, UK</small>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Family Reunion</h6>
                                <small class="text-muted">In 2 weeks</small>
                            </div>
                            <small class="text-muted">Annual gathering • Toronto, Canada</small>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Michael's Graduation</h6>
                                <small class="text-muted">In 3 weeks</small>
                            </div>
                            <small class="text-muted">University ceremony • Boston, USA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Photos -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recently Added Photos</h5>
                    <button class="btn btn-sm btn-outline-primary">Upload Photos</button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <img src="/api/placeholder/200/150" class="img-fluid rounded" alt="Family photo">
                            <div class="small text-muted mt-1">Added by Lisa • 2 days ago</div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <img src="/api/placeholder/200/150" class="img-fluid rounded" alt="Family photo">
                            <div class="small text-muted mt-1">Added by John • 3 days ago</div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <img src="/api/placeholder/200/150" class="img-fluid rounded" alt="Family photo">
                            <div class="small text-muted mt-1">Added by Emma • 5 days ago</div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <img src="/api/placeholder/200/150" class="img-fluid rounded" alt="Family photo">
                            <div class="small text-muted mt-1">Added by Mike • 1 week ago</div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <img src="/api/placeholder/200/150" class="img-fluid rounded" alt="Family photo">
                            <div class="small text-muted mt-1">Added by Sarah • 1 week ago</div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <img src="/api/placeholder/200/150" class="img-fluid rounded" alt="Family photo">
                            <div class="small text-muted mt-1">Added by Tom • 2 weeks ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // Age Distribution Chart
    const ctx = document.getElementById('ageChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['0-18', '19-30', '31-45', '46-60', '61-75', '76+'],
            datasets: [{
                label: 'Number of Members',
                data: [45, 78, 92, 65, 34, 10],
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: 'rgb(13, 110, 253)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
