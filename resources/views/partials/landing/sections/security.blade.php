<section class="security-banner py-4 bg-white border-top border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h5 class="mb-2 text-ceeac-blue">
                    <i class="bi bi-shield-check me-2"></i>
                    {{ $config['security']['title'] ?? 'Accès sécurisé' }}
                </h5>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @foreach($config['security']['features'] ?? [] as $feature)
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-check-circle-fill text-success me-1"></i>{{ $feature }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-end">
                    @foreach($config['security']['badges'] ?? [] as $badge)
                        <span class="badge {{ $badge['class'] ?? 'badge-statut-en-cours' }} px-3 py-2">
                            {{ $badge['label'] ?? '' }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>



