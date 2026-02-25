@push('styles')
    @vite(['resources/css/orden.css'])
@endpush

@push('scripts')
    @vite(['resources/js/orden.js'])
@endpush

<x-pos-layout>
    <div id="orden-container" class="orden-layout" data-orden-id="{{ $orden->id_orden }}" data-orden-total="{{ $orden->total }}">

        {{-- 1. SIDEBAR CATEGORÍAS (Izquierda) --}}
        <aside class="sidebar-categorias" id="mobile-menu">
            <div class="brand-area">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo">
                <span>LA HAMBURGUESA</span>
                <button id="close-menu-btn" class="mobile-close-btn">&times;</button>
            </div>

            <nav class="cat-nav">
                <ul>
                    @foreach($categorias as $categoria)
                        <li>
                            <a href="#cat-{{ $categoria->id_categoria }}" class="cat-link">
                                {{ $categoria->nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

        {{-- 2. GRID PRODUCTOS (Centro - FONDO BLANCO) --}}
        <main class="main-products">
            {{-- Header interno (Título y Botones Móviles) --}}
            <header class="products-header">
                <div class="header-left">
                    <button id="mobile-menu-btn" class="icon-btn mobile-only">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div class="mesa-info">
                        <h1>Mesa: {{ $orden->mesa->nombre }}</h1>
                        <span class="subtitle">Selecciona tus productos</span>
                    </div>
                </div>

                <button id="mobile-cart-btn" class="icon-btn cart-btn-mobile mobile-only">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="badge-mobile">{{ $carrito->count() }}</span>
                </button>
            </header>

            <div class="scroll-area">
                @foreach($categorias as $categoria)
                    <section id="cat-{{ $categoria->id_categoria }}" class="cat-section">
                        <h2 class="cat-title">{{ $categoria->nombre }}</h2>
                        <div class="product-grid">
                            @foreach($categoria->productos as $producto)
                                <x-product-card :product="$producto" :category="$categoria->nombre" />
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </main>

        {{-- 3. CARRITO (Derecha - FONDO OSCURO) --}}
        <aside class="sidebar-carrito" id="sidebar-carrito">
            <div class="cart-header">
                <h3>Tu Pedido</h3>
                <button id="close-cart-btn" class="mobile-close-btn mobile-only">&times;</button>
            </div>

            <div class="cart-items-container">
                @if($carrito->isEmpty())
                    <div class="empty-state">
                        <p>Carrito vacío</p>
                    </div>
                @else
                    @foreach($carrito as $item)
                        @php
                            $imgItem = null;
                            if (!empty($item->producto->imagen_url)) {
                                $p1 = public_path('images/productos/' . $item->producto->imagen_url);
                                $p2 = public_path('images/' . $item->producto->imagen_url);
                                if (file_exists($p1)) {
                                    $imgItem = asset('images/productos/' . $item->producto->imagen_url);
                                } elseif (file_exists($p2)) {
                                    $imgItem = asset('images/' . $item->producto->imagen_url);
                                }
                            }
                            if (!$imgItem) {
                                $imgItem = asset('images/' . ($item->producto->categoria_id == 2 ? 'hamburguesa.png' : 'entypa.png'));
                            }
                        @endphp

                        <div class="cart-item-row">
                            {{-- [NUEVO] Checkbox para excluir de comanda --}}
                            <div style="display:flex; align-items:center; padding-right:8px;">
                                <input type="checkbox"
                                       class="check-exclude w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                       data-id="{{ $item->id_detalle }}"

                                       title="Marcar si YA se pidió (No imprimir en cocina)">
                            </div>

                            <div class="item-img">
                                <img src="{{ $imgItem }}">
                            </div>
                            
                            <div class="item-details">
                                <div class="header-row">
                                    <h4>{{ $item->producto->nombre }}</h4>
                                    <button onclick="eliminarItem({{ $item->id_detalle }})" class="btn-remove" title="Quitar">&times;</button>
                                </div>

                                @if($item->notas)
                                    <p class="item-notes">+ {{ $item->notas }}</p> 
                                @endif

                                <div class="item-meta">
                                    <div class="qty-controls">
                                        <button onclick="cambiarCantidad({{ $item->id_detalle }}, 'decrementar')" class="btn-qty">-</button>
                                        <span class="qty-number">{{ $item->cantidad }}</span>
                                        <button onclick="cambiarCantidad({{ $item->id_detalle }}, 'incrementar')" class="btn-qty">+</button>
                                    </div>

                                    <span class="price">${{ number_format($item->precio * $item->cantidad, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="cart-footer">
                <div class="actions-container" style="display:flex; gap:8px; margin-bottom:12px;">
                    <button type="button" onclick="imprimirComanda()" class="btn-action btn-cocina">Imprimir Comanda</button>
                    <button type="button" onclick="imprimirTicket()" class="btn-action btn-ticket">Imprimir Ticket</button>
                    <button type="button" onclick="abrirModalPago()" class="btn-action btn-pagar">Pagar Cuenta</button>
                </div>

                <div class="totals">
                    <div class="row total"><span>Total</span> <span>${{ number_format($orden->total, 2) }}</span></div>
                </div>
            </div>
        </aside>

        <div id="overlay" class="overlay"></div>

        {{-- Modal de Pago --}}
        <div id="modalPago" class="modal-overlay" style="display:none;">
            <div class="modal-content">
                <h3>Cerrar Mesa {{ $orden->mesa->nombre }}</h3>
                <div class="total-display" style="margin-top:8px;">
                    Total a Pagar: <span id="modalTotal" style="font-weight:bold; font-size:1.5em;">${{ number_format($orden->total, 2) }}</span>
                </div>

                <div class="payment-tabs" style="margin: 20px 0;">
                    <button onclick="setMetodo('efectivo')" class="tab active" id="tab-efectivo">💵 Efectivo</button>
                    <button onclick="setMetodo('tarjeta')" class="tab" id="tab-tarjeta">💳 Tarjeta</button>
                    <button onclick="setMetodo('mixto')" class="tab" id="tab-mixto">🔀 Mixto</button>
                </div>

                <div id="input-container" class="mt-4" style="margin-bottom: 20px;"></div>

                <div class="modal-actions">
                    <button onclick="cerrarModal()" class="btn-cancelar">Cancelar</button>
                    <button onclick="procesarPago()" class="btn-confirmar-pago">CONFIRMAR PAGO</button>
                </div>
            </div>
        </div>

    </div>
</x-pos-layout>