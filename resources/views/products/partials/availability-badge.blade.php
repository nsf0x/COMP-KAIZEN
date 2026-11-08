<span class="badge mb-2" style="background:#e8eeff; color:var(--primary);">
    @if($product->bisa_dijual && $product->bisa_disewa)
        Dijual &amp; Disewakan
    @elseif($product->bisa_dijual)
        Dijual
    @else
        Sewa
    @endif
</span>
