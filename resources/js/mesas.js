// resources/js/mesas.js

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menuToggle');
    const mainNav = document.getElementById('mainNav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', () => {
            mainNav.classList.toggle('active');
        });
        document.addEventListener('click', (event) => {
            if (!mainNav.contains(event.target) && !menuToggle.contains(event.target) && mainNav.classList.contains('active')) {
                mainNav.classList.remove('active');
            }
        });
    }

    const zonaSelector = document.getElementById('zona-selector');
    const mapasMesas = document.querySelectorAll('.mapa-mesas');

    if (zonaSelector && mapasMesas.length > 0) {
        zonaSelector.addEventListener('change', (event) => {
            const selectedZonaId = `mapa-mesas-${event.target.value}`;

            mapasMesas.forEach(mapa => {
                if (mapa.id === selectedZonaId) {
                    mapa.classList.remove('hidden');
                    mapa.classList.add('active');
                } else {
                    mapa.classList.add('hidden');
                    mapa.classList.remove('active');
                }
            });
        });

        const initialZonaId = `mapa-mesas-${zonaSelector.value}`;
        mapasMesas.forEach(mapa => {
            if (mapa.id === initialZonaId) {
                mapa.classList.remove('hidden');
                mapa.classList.add('active');
            } else {
                mapa.classList.add('hidden');
                mapa.classList.remove('active');
            }
        });
    }

    document.querySelectorAll('.mesa').forEach(mesa => {
        mesa.addEventListener('click', () => {
            const mesaId = mesa.dataset.mesaId;

            // Aplicar cambio visual inmediato: marcar como ocupada/activa (verde)
            mesa.classList.remove('libre');
            mesa.classList.add('ocupada');

            // Opcional: actualizar el contenido visual
            if (mesa.textContent) {
                // Mantener texto pero podrías mostrar "Activa" o similar
            }

            // Redirigir a la ruta que gestiona la orden por mesa
            // (la acción en el servidor también marcará la mesa como ocupada en la BD)
            window.location.href = `/orden/mesa/${mesaId}`;
        });
    });

});