@props(['product', 'category'])

@php
    // 1. Precio variante papas
    $precioPapas = 0;
    if (!empty($product->id_variante_papas)) {
        $prodPapas = \App\Models\Producto::find($product->id_variante_papas);
        if ($prodPapas) $precioPapas = $prodPapas->precio;
    }

    // 2. ¿Es hamburguesa? (basado en nombre de categoria)
    $esHamburguesa = false;
    if (!empty($category)) {
        $esHamburguesa = \Illuminate\Support\Str::contains(strtolower($category), 'hamburguesa');
    }

    // 3. Resolver ruta de imagen: primero buscar en public/images/productos/, luego en public/images/
    $imgUrl = null;
    if (!empty($product->imagen_url)) {
        $p1 = public_path('images/productos/' . $product->imagen_url);
        $p2 = public_path('images/' . $product->imagen_url);
        if (file_exists($p1)) {
            $imgUrl = asset('images/productos/' . $product->imagen_url);
        } elseif (file_exists($p2)) {
            $imgUrl = asset('images/' . $product->imagen_url);
        }
    }
    if (!$imgUrl) {
        $imgUrl = asset('images/logo.jpeg');
    }
@endphp

<div class="card product-card-component"
     data-base-id="{{ $product->id_prod ?? $product->ID_prod ?? '' }}"
     data-base-price="{{ $product->precio }}"
     data-papas-id="{{ $product->id_variante_papas }}"
     data-papas-price="{{ $precioPapas }}">

    <img src="{{ $imgUrl }}" alt="{{ $product->nombre }}">

    <div class="card-body">
        <h4 class="card-title">{{ $product->nombre }}</h4>
        <p class="card-description">{{ $product->descripcion }}</p>

        <div class="customization-zone">
            @if(!empty($product->id_variante_papas))
                <div class="toggle-papas" style="margin-bottom: 10px;">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 5px; color: #ddd; font-size: 0.9rem;">
                        <input type="checkbox" class="check-papas">
                        Con Papas (+${{ number_format($precioPapas - $product->precio, 0) }})
                    </label>
                </div>
            @endif

            @if($esHamburguesa)
                <div class="extras-counters">
                    <div class="extra-row">
                        <span>Carne Extra</span>
                        <div class="counter-control">
                            <button class="btn-cnt" onclick="ajustarExtra(this, -1)">-</button>
                            <span class="cnt-val" data-extra-id="41">0</span>
                            <button class="btn-cnt" onclick="ajustarExtra(this, 1)">+</button>
                        </div>
                    </div>

                    <div class="extra-row">
                        <span>Ingr. Extra</span>
                        <div class="counter-control">
                            <button class="btn-cnt" onclick="ajustarExtra(this, -1)">-</button>
                            <span class="cnt-val" data-extra-id="42">0</span>
                            <button class="btn-cnt" onclick="ajustarExtra(this, 1)">+</button>
                        </div>
                    </div>

                          <input type="text" class="notas-extras" placeholder="Especifique (tocino...)"
                              style="display:none; width:100%; margin-top:5px; font-size:0.8rem; color: #000; background: #fff; padding: 6px; border-radius: 4px; border: none;">
                </div>
            @endif

        </div>

            {{-- [NUEVO] SECCIÓN DE NOTAS GENERALES --}}
            <div class="product-note-section" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.1);">
                <button onclick="toggleNota(this)" class="btn-toggle-note" style="background:none; border:none; color:#accent; cursor:pointer; font-size:0.8rem; display:flex; align-items:center; gap:5px; color: #aaa;">
                    <span style="font-size:1.2em;">✎</span> Agregar nota / modificación
                </button>

                <input type="text" class="input-nota-principal" 
                       placeholder="Ej. Sin cebolla, carne bien cocida..." 
                       style="display:none; width:100%; margin-top:5px; background:#222; border:1px solid #444; color:white; font-size:0.85rem; padding:6px; border-radius:4px;">
            </div>
            {{-- [FIN NUEVO] --}}

        <div class="card-footer">
            <span class="card-price display-price">${{ number_format($product->precio, 2) }}</span>
            <button class="btn-add-to-cart" onclick="agregarAlCarrito(this)">Añadir</button>
        </div>
    </div>
</div>
