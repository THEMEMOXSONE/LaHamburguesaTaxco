// resources/js/orden.js

document.addEventListener('DOMContentLoaded', () => {
    const ordenContainer = document.getElementById('orden-container');
    if (!ordenContainer) return;

    const ordenId = ordenContainer.dataset.ordenId;
    const ordenTotal = parseFloat(ordenContainer.dataset.ordenTotal || 0);

    // 1. Cambio de precio al marcar "Con Papas"
    document.addEventListener('change', (e) => {
        if (e.target.classList && e.target.classList.contains('check-papas')) {
            const card = e.target.closest('.product-card-component');
            if (!card) return;
            const priceDisplay = card.querySelector('.display-price');

            const basePrice = parseFloat(card.dataset.basePrice) || 0;
            const papasPrice = parseFloat(card.dataset.papasPrice) || basePrice;

            if (e.target.checked) {
                if (priceDisplay) priceDisplay.textContent = '$' + papasPrice.toFixed(2);
            } else {
                if (priceDisplay) priceDisplay.textContent = '$' + basePrice.toFixed(2);
            }
        }
    });

    // 2. Contadores de extras (función global para botones inline)
    window.ajustarExtra = function (btn, cambio) {
        const counterSpan = btn.parentElement.querySelector('.cnt-val');
        if (!counterSpan) return;
        let val = parseInt(counterSpan.textContent) || 0;
        val = Math.max(0, val + cambio);
        counterSpan.textContent = val;

        if (counterSpan.dataset.extraId == '42') {
            const card = btn.closest('.product-card-component');
            const inputNotas = card.querySelector('.notas-extras');
            if (inputNotas) inputNotas.style.display = val > 0 ? 'block' : 'none';
        }
    };

    // Función para mostrar/ocultar el campo de nota principal
    window.toggleNota = function(btn) {
        const card = btn.closest('.product-card-component') || btn.closest('.card-body');
        const input = card ? card.querySelector('.input-nota-principal') : null;
        if (!input) return;

        if (input.style.display === 'none' || input.style.display === '') {
            input.style.display = 'block';
            input.focus();
            btn.innerHTML = '<span style="color:#DCA548">✕</span> Cancelar nota';
        } else {
            input.style.display = 'none';
            input.value = '';
            btn.innerHTML = '<span>✎</span> Agregar nota / modificación';
        }
    };

    // 3. Agregar al carrito: enviar múltiples líneas
    window.agregarAlCarrito = function (btn) {
        const card = btn.closest('.product-card-component');
        if (!card) return;

        let mainProductId = card.dataset.baseId;
        const checkPapas = card.querySelector('.check-papas');
        if (checkPapas && checkPapas.checked && card.dataset.papasId) {
            mainProductId = card.dataset.papasId;
        }

        const itemsToSend = [];

        // Capturamos nota principal si está visible
        const inputNotaPrincipal = card.querySelector('.input-nota-principal');
        let notaPrincipal = '';
        if (inputNotaPrincipal && inputNotaPrincipal.style.display !== 'none') {
            notaPrincipal = inputNotaPrincipal.value.trim();
        }

        itemsToSend.push({
            producto_id: mainProductId,
            cantidad: 1,
            notas: notaPrincipal
        });

        const extras = card.querySelectorAll('.cnt-val');
        extras.forEach(extra => {
            const qty = parseInt(extra.textContent) || 0;
            if (qty > 0) {
                let notas = '';
                if (extra.dataset.extraId == '42') {
                    const notasInput = card.querySelector('.notas-extras');
                    notas = notasInput ? notasInput.value : '';
                }

                itemsToSend.push({
                    producto_id: extra.dataset.extraId,
                    cantidad: qty,
                    notas: notas
                });
            }
        });

        // CSRF token
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        fetch(`/orden/${ordenId}/agregar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ items: itemsToSend })
        })
            .then(res => res.json())
            .then(data => {
                // Por ahora recargamos para ver los cambios
                alert(data.mensaje || 'Items agregados');
                location.reload();
            })
            .catch(err => {
                console.error(err);
                alert('Error al agregar productos');
            });
    };

    // Lógica Móvil
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileCartBtn = document.getElementById('mobile-cart-btn');
    const closeMenuBtn = document.getElementById('close-menu-btn');
    const closeCartBtn = document.getElementById('close-cart-btn');
    const overlay = document.getElementById('overlay');

    const sidebarMenu = document.getElementById('mobile-menu');
    const sidebarCart = document.getElementById('sidebar-carrito');

    function closeAll() {
        if (sidebarMenu) sidebarMenu.classList.remove('open');
        if (sidebarCart) sidebarCart.classList.remove('open');
        if (overlay) overlay.classList.remove('open');
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            if (sidebarMenu) sidebarMenu.classList.add('open');
            if (overlay) overlay.classList.add('open');
        });
    }

    // Manejo de clicks en categorías: evitar salto de página y hacer scroll dentro de .scroll-area
    document.querySelectorAll('.cat-link').forEach(link => {
        link.addEventListener('click', function (ev) {
            // prevenir comportamiento por defecto (saltos en el documento)
            ev.preventDefault();

            const href = this.getAttribute('href') || '';
            let targetId = null;
            if (href.startsWith('#')) targetId = href.slice(1);
            else if (this.dataset.target) targetId = this.dataset.target;
            if (!targetId) return;

            const container = document.querySelector('.scroll-area');
            const targetEl = document.getElementById(targetId);
            if (!container || !targetEl) {
                // fallback al comportamiento por defecto
                location.hash = '#' + targetId;
                return;
            }

            // calcular posición relativa dentro del contenedor y scrollear suavemente
            const containerRect = container.getBoundingClientRect();
            const targetRect = targetEl.getBoundingClientRect();
            const currentScroll = container.scrollTop;
            const offset = targetRect.top - containerRect.top + currentScroll - 10; // pequeño offset
            container.scrollTo({ top: offset, behavior: 'smooth' });

            // actualizar hash sin forzar scroll del documento
            if (history && history.replaceState) {
                history.replaceState(null, null, '#' + targetId);
            }

            // cerrar menú móvil sólo si está abierto (no interferir en desktop)
            if (sidebarMenu && sidebarMenu.classList.contains('open')) {
                cerrarMenuMovil();
            }
        });
    });

    if (mobileCartBtn) {
        mobileCartBtn.addEventListener('click', () => {
            if (sidebarCart) sidebarCart.classList.add('open');
            if (overlay) overlay.classList.add('open');
        });
    }

    if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeAll);
    if (closeCartBtn) closeCartBtn.addEventListener('click', closeAll);
    if (overlay) overlay.addEventListener('click', closeAll);
});

// Función global para cerrar menú al dar click en un link
window.cerrarMenuMovil = function () {
    const sidebarMenu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('overlay');
    if (sidebarMenu) sidebarMenu.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
}

/* ==========================================
   LÓGICA DE PAGO Y MODAL (EXPORTADA A WINDOW)
   ==========================================
*/

// Variable global para el método
window.metodoSeleccionado = 'efectivo';

// Función para abrir el modal
window.abrirModalPago = function() {
    const modal = document.getElementById('modalPago');
    if(modal) {
        modal.style.display = 'flex';
        // Forzamos "Efectivo" al abrir para evitar errores
        window.setMetodo('efectivo');
    }
}

// Función para cerrar
window.cerrarModal = function() {
    const modal = document.getElementById('modalPago');
    if(modal) modal.style.display = 'none';
}

// Cambiar pestañas (Efectivo / Tarjeta / Mixto)
window.setMetodo = function(metodo) {
    window.metodoSeleccionado = metodo;
    const ordenContainer = document.getElementById('orden-container');
    const total = parseFloat(ordenContainer.dataset.ordenTotal || 0);

    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    const tabEl = document.getElementById(`tab-${metodo}`);
    if (tabEl) tabEl.classList.add('active');

    const container = document.getElementById('input-container');
    container.innerHTML = '';

    if (metodo === 'efectivo') {
        container.innerHTML = `<p style="color:#aaa; margin-top:10px;">Cobrar <b style="color:white; font-size:1.2em;">$${total.toFixed(2)}</b> en Efectivo.</p>`;
    } else if (metodo === 'tarjeta') {
        container.innerHTML = `<p style="color:#aaa; margin-top:10px;">Cobrar <b style="color:white; font-size:1.2em;">$${total.toFixed(2)}</b> con Tarjeta.</p>`;
    } else if (metodo === 'mixto') {
        container.innerHTML = `
            <div style="display:flex; flex-direction:column; gap:10px; text-align:left; margin-top:10px;">
                <div>
                    <label style="color:#aaa;">Efectivo:</label>
                    <input type="number" id="monto-efectivo" value="0" min="0" step="0.50" 
                           oninput="window.calcularRestante()" 
                           style="width:100%; padding:10px; background:#222; border:1px solid #444; color:white;">
                </div>
                <div>
                    <label style="color:#aaa;">Tarjeta (Restante):</label>
                    <input type="number" id="monto-tarjeta" value="${total.toFixed(2)}" readonly 
                           style="width:100%; padding:10px; background:#111; border:1px solid #333; color:#888;">
                </div>
            </div>
        `;
    }
}

// Calcular el restante para pago mixto
window.calcularRestante = function() {
    const ordenContainer = document.getElementById('orden-container');
    const total = parseFloat(ordenContainer.dataset.ordenTotal || 0);
    const inputEfec = document.getElementById('monto-efectivo');
    const inputTarj = document.getElementById('monto-tarjeta');

    if(inputEfec && inputTarj) {
        let ef = parseFloat(inputEfec.value) || 0;
        let rest = total - ef;
        if(rest < 0) rest = 0;
        inputTarj.value = rest.toFixed(2);
    }
}

// PROCESAR EL PAGO (La función importante)
window.procesarPago = async function() {
    const ordenContainer = document.getElementById('orden-container');
    const ordenId = ordenContainer.dataset.ordenId;
    const total = parseFloat(ordenContainer.dataset.ordenTotal || 0);
    let pagoEfectivo = 0;
    let pagoTarjeta = 0;

    if (window.metodoSeleccionado === 'efectivo') {
        pagoEfectivo = total;
    } else if (window.metodoSeleccionado === 'tarjeta') {
        pagoTarjeta = total;
    } else {
        pagoEfectivo = parseFloat(document.getElementById('monto-efectivo')?.value) || 0;
        pagoTarjeta = parseFloat(document.getElementById('monto-tarjeta')?.value) || 0;
        if (Math.abs((pagoEfectivo + pagoTarjeta) - total) > 0.5) {
            alert('Los montos no suman el total exacto.');
            return;
        }
    }

    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await fetch('/orden/pagar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                orden_id: ordenId,
                metodo: window.metodoSeleccionado,
                monto_efectivo: pagoEfectivo,
                monto_tarjeta: pagoTarjeta
            })
        });

        const data = await response.json();
        if (data.success) {
            alert('¡Cuenta Pagada y Mesa Liberada!');
            window.location.href = '/mesas';
        } else {
            alert('Error: ' + data.message);
        }

    } catch (error) {
        console.error(error);
        alert('Error de conexión con el servidor.');
    }
}

// IMPRIMIR (Arreglado para abrir ventana)
window.imprimirTicket = function() {
    const ordenId = document.getElementById('orden-container').dataset.ordenId;
    const url = `/imprimir/ticket/${ordenId}`;
    window.open(url, 'Ticket', 'width=400,height=600,scrollbars=yes');
}

window.imprimirComanda = function() {
    const ordenId = document.getElementById('orden-container').dataset.ordenId;
    const url = `/imprimir/comanda/${ordenId}`;
    window.open(url, 'Comanda', 'width=400,height=600,scrollbars=yes');
}
