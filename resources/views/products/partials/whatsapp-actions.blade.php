<div class="d-flex flex-wrap gap-2">
    @if($product->bisa_dijual)
    <a href="{{ $product->whatsapp_sale_url }}"
       class="btn btn-success rounded-pill {{ !empty($compact) ? 'btn-sm flex-fill' : '' }}"
       target="_blank" rel="noopener noreferrer" data-product-name="{{ $product->name }}">
        <i class="bi bi-whatsapp me-1"></i>Beli via WhatsApp
    </a>
    @endif
    @if($product->bisa_disewa)
    <a href="{{ $product->whatsapp_rental_url }}"
       class="btn btn-outline-success rounded-pill {{ !empty($compact) ? 'btn-sm flex-fill' : '' }}"
       target="_blank" rel="noopener noreferrer" data-product-name="{{ $product->name }}">
        <i class="bi bi-whatsapp me-1"></i>Sewa via WhatsApp
    </a>
    @endif
</div>
