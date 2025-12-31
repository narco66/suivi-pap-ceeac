<section class="contact-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-ceeac">
                    <div class="card-body text-center p-5">
                        <div class="feature-icon feature-icon-blue mx-auto mb-4">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h3 class="text-ceeac-blue mb-3">{{ $config['contact']['title'] ?? 'Support DSI' }}</h3>
                        <p class="text-muted mb-4">{{ $config['contact']['description'] ?? '' }}</p>
                        <div class="mb-4">
                            <a href="mailto:{{ $config['contact']['email'] ?? 'dsi@ceeac.org' }}" class="btn btn-primary btn-ceeac me-2">
                                <i class="bi bi-envelope me-2"></i>
                                {{ $config['contact']['email'] ?? 'dsi@ceeac.org' }}
                            </a>
                            @if(isset($config['contact']['status_route']))
                                <a href="{{ route($config['contact']['status_route']) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-activity me-2"></i>
                                    Statut plateforme
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



