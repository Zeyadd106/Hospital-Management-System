<div class="card h-100 shadow-sm hover:shadow-lg transition-all duration-300">
    <div class="card-body">
        <div class="text-center mb-3">
            <i class="fas fa-syringe text-primary" style="font-size: 2.5rem;"></i>
        </div>
        <h3 class="card-title h5 text-center mb-3">{{ $vaccine->name }}</h3>
        <p class="card-text text-muted mb-3">{{ Str::limit($vaccine->description, 100) }}</p>
        
        <div class="mb-3">
            <small class="d-block"><strong>Manufacturer:</strong> {{ $vaccine->manufacturer }}</small>
            <small class="d-block"><strong>Recommended Age:</strong> {{ $vaccine->recommended_age }}</small>
            <small class="d-block"><strong>Doses Required:</strong> {{ $vaccine->doses_required }}</small>
            <small class="d-block"><strong>Price:</strong> 
                @if($vaccine->price == 0)
                    Free
                @else
                    ${{ number_format($vaccine->price, 2) }}
                @endif
            </small>
        </div>
        
        <div class="text-center mt-auto">
            <a href="{{ route('vaccinations.show', $vaccine) }}" class="btn btn-primary">
                {{ $buttonText ?? 'View Details' }}
            </a>
        </div>
    </div>
</div>

