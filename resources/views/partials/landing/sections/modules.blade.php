<section class="modules-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-ceeac-blue mb-3">Modules de la plateforme</h2>
            <p class="text-muted">Accédez aux différents modules selon vos droits</p>
        </div>
        <div class="row g-4">
            @foreach($config['modules'] ?? [] as $module)
                <div class="col-md-6 col-lg-4">
                    <div class="card card-ceeac h-100">
                        <div class="card-body text-center">
                            <div class="feature-icon feature-icon-blue mx-auto mb-3">
                                <i class="bi {{ $module['icon'] ?? 'bi-box' }}"></i>
                            </div>
                            <h5 class="card-title">{{ $module['name'] ?? '' }}</h5>
                            <p class="card-text text-muted">{{ $module['description'] ?? '' }}</p>
                            <a href="{{ route($module['route'] ?? 'login') }}" class="btn btn-primary btn-ceeac">
                                Accéder
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>



