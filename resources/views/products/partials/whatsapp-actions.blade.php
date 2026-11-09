@if(!empty($compact))
<div class="product-card-cta">
    <div class="product-card-cta__actions{{ $product->bisa_dijual && $product->bisa_disewa ? ' product-card-cta__actions--paired' : '' }}">
        @if($product->bisa_dijual)
        <a href="{{ $product->whatsapp_sale_url }}"
           class="product-card-cta__button product-card-cta__button--buy"
           aria-label="Beli {{ $product->name }} via WhatsApp"
           target="_blank" rel="noopener noreferrer" data-product-name="{{ $product->name }}">
            <i class="bi bi-whatsapp" aria-hidden="true"></i><span>Beli</span>
        </a>
        @endif
        @if($product->bisa_disewa)
        <a href="{{ $product->whatsapp_rental_url }}"
           class="product-card-cta__button product-card-cta__button--rent"
           aria-label="Sewa {{ $product->name }} via WhatsApp"
           target="_blank" rel="noopener noreferrer" data-product-name="{{ $product->name }}">
            <i class="bi bi-whatsapp" aria-hidden="true"></i><span>Sewa</span>
        </a>
        @endif
    </div>
    <a href="{{ route('products.show', $product) }}"
       class="product-card-cta__button product-card-cta__detail"
       aria-label="Lihat detail {{ $product->name }}">Detail</a>
</div>
@else
<div class="d-flex flex-wrap gap-3">
    @if($product->bisa_dijual)
    <a href="{{ $product->whatsapp_sale_url }}" class="btn btn-success btn-lg rounded-pill px-5"
       aria-label="Beli {{ $product->name }} via WhatsApp"
       target="_blank" rel="noopener noreferrer" data-product-name="{{ $product->name }}">
        <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Beli via WhatsApp
    </a>
    @endif
    @if($product->bisa_disewa)
    <a href="{{ $product->whatsapp_rental_url }}" class="btn btn-outline-success btn-lg rounded-pill px-5"
       aria-label="Sewa {{ $product->name }} via WhatsApp"
       target="_blank" rel="noopener noreferrer" data-product-name="{{ $product->name }}">
        <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Sewa via WhatsApp
    </a>
    @endif
</div>
@endif
