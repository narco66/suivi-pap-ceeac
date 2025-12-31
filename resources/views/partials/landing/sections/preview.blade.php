<section class="preview-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-ceeac-blue mb-3">Aperçu du tableau de bord</h2>
            <p class="text-muted">Découvrez l'interface de la plateforme</p>
        </div>
        <div class="card card-ceeac shadow-lg">
            <div class="card-body p-4">
                <!-- KPI Cards Preview -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="dashboard-card bg-white p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="dashboard-card-icon dashboard-card-icon-blue me-3">
                                    <i class="bi bi-file-text"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">PAPA Actifs</div>
                                    <div class="h4 mb-0 text-ceeac-blue">12</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card bg-white p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="dashboard-card-icon dashboard-card-icon-green me-3">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Objectifs</div>
                                    <div class="h4 mb-0 text-success">45</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card bg-white p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="dashboard-card-icon dashboard-card-icon-yellow me-3">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">En cours</div>
                                    <div class="h4 mb-0 text-warning">28</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dashboard-card bg-white p-3 rounded">
                            <div class="d-flex align-items-center">
                                <div class="dashboard-card-icon dashboard-card-icon-orange me-3">
                                    <i class="bi bi-bell"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Alertes</div>
                                    <div class="h4 mb-0 text-danger">5</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mini Chart Preview -->
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <div class="bg-white p-3 rounded">
                            <h6 class="mb-3">Évolution des KPI</h6>
                            <div class="d-flex align-items-end" style="height: 150px;">
                                <div class="flex-fill d-flex flex-column align-items-center me-2">
                                    <div class="rounded-top" style="height: 60%; width: 100%; background: linear-gradient(180deg, var(--ceeac-blue) 0%, var(--ceeac-blue-light) 100%);"></div>
                                    <small class="text-muted mt-2">Jan</small>
                                </div>
                                <div class="flex-fill d-flex flex-column align-items-center me-2">
                                    <div class="rounded-top" style="height: 80%; width: 100%; background: linear-gradient(180deg, var(--ceeac-blue) 0%, var(--ceeac-blue-light) 100%);"></div>
                                    <small class="text-muted mt-2">Fév</small>
                                </div>
                                <div class="flex-fill d-flex flex-column align-items-center me-2">
                                    <div class="rounded-top" style="height: 45%; width: 100%; background: linear-gradient(180deg, var(--ceeac-blue) 0%, var(--ceeac-blue-light) 100%);"></div>
                                    <small class="text-muted mt-2">Mar</small>
                                </div>
                                <div class="flex-fill d-flex flex-column align-items-center me-2">
                                    <div class="rounded-top" style="height: 90%; width: 100%; background: linear-gradient(180deg, var(--ceeac-blue) 0%, var(--ceeac-blue-light) 100%);"></div>
                                    <small class="text-muted mt-2">Avr</small>
                                </div>
                                <div class="flex-fill d-flex flex-column align-items-center me-2">
                                    <div class="rounded-top" style="height: 70%; width: 100%; background: linear-gradient(180deg, var(--ceeac-blue) 0%, var(--ceeac-blue-light) 100%);"></div>
                                    <small class="text-muted mt-2">Mai</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white p-3 rounded h-100">
                            <h6 class="mb-3">Alertes prioritaires</h6>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 border-0 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-gravite-critique me-2">Critique</span>
                                        <small>Échéance approche</small>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 border-0 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-gravite-vigilance me-2">Vigilance</span>
                                        <small>Action en retard</small>
                                    </div>
                                </div>
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-gravite-normal me-2">Normal</span>
                                        <small>Rapport à valider</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Accéder au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>



