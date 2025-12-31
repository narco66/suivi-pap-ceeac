<section id="features" class="features-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-ceeac-blue mb-3">Fonctionnalités clés</h2>
            <p class="text-muted">Découvrez les principales fonctionnalités de la plateforme</p>
        </div>
        <div class="row g-4">
            @foreach($config['features'] ?? [] as $feature)
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card card-ceeac h-100">
                        <div class="feature-icon feature-icon-{{ $feature['color'] ?? 'blue' }} mb-3">
                            <i class="bi {{ $feature['icon'] ?? 'bi-star' }}"></i>
                        </div>
                        <h4 class="mb-3">{{ $feature['title'] ?? '' }}</h4>
                        <p class="text-muted mb-0">{{ $feature['description'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>



