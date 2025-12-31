<section class="hero-section">
    <div class="container">
        <div class="row align-items-center py-4">
            <div class="col-lg-6 hero-content">
                <div class="text-center text-lg-start">
                    <img src="{{ asset('images/logo-ceeac.png') }}" alt="CEEAC Logo" class="logo-hero" onerror="this.style.display='none'">
                    <h1 class="display-5 fw-bold mb-3">{{ $config['hero']['title'] ?? 'SUIVI-PAPA CEEAC' }}</h1>
                    <p class="lead mb-2">{{ $config['hero']['subtitle'] ?? '' }}</p>
                    <p class="mb-4 opacity-90 small">{{ $config['hero']['description'] ?? '' }}</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        @if(isset($config['cta']['primary']))
                            <a href="{{ route($config['cta']['primary']['route']) }}" class="btn btn-ceeac-primary">
                                <i class="bi {{ $config['cta']['primary']['icon'] ?? 'bi-box-arrow-in-right' }} me-2"></i>
                                {{ $config['cta']['primary']['label'] }}
                            </a>
                        @endif
                        @if(isset($config['cta']['secondary']))
                            <a href="#features" class="btn btn-ceeac-outline smooth-scroll">
                                <i class="bi bi-arrow-down me-2"></i>
                                {{ $config['cta']['secondary']['label'] }}
                            </a>
                        @endif
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start mt-3">
                        @if(isset($config['cta']['tertiary']))
                            <a href="{{ route($config['cta']['tertiary']['route']) }}" class="btn btn-link text-white text-decoration-none">
                                <i class="bi {{ $config['cta']['tertiary']['icon'] ?? 'bi-upload' }} me-2"></i>
                                {{ $config['cta']['tertiary']['label'] }}
                            </a>
                        @endif
                        @if(isset($config['cta']['download']))
                            <a href="{{ route($config['cta']['download']['route']) }}" class="btn btn-link text-white text-decoration-none">
                                <i class="bi {{ $config['cta']['download']['icon'] ?? 'bi-download' }} me-2"></i>
                                {{ $config['cta']['download']['label'] }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="text-center">
                    <div class="bg-white rounded-3 p-4 shadow-lg">
                        <h3 class="text-ceeac-blue mb-4">Fonctionnalités principales</h3>
                        <div class="row g-3 text-start">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <small>Gestion PAPA</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <small>Suivi des Objectifs</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <small>KPI et Indicateurs</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <small>Gestion des Tâches</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <small>Alertes & Notifications</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <small>Rapports & Exports</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

