<footer class="footer-ceeac">
    <div class="container">
        <div class="row py-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="mb-3">SUIVI-PAPA CEEAC</h5>
                <p class="mb-2">{{ $config['footer']['copyright'] ?? '' }}</p>
                <p class="mb-0 small text-white-50">{{ $config['footer']['disclaimer'] ?? '' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3">Liens rapides</h6>
                <ul class="list-unstyled mb-0">
                    @foreach($config['footer']['links'] ?? [] as $link)
                        <li class="mb-2">
                            <a href="{{ route($link['route']) }}" class="text-white text-decoration-none">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</footer>



