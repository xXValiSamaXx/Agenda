/**
 * Script de protección de sesión
 * Previene el acceso a páginas protegidas después de cerrar sesión
 */

// Prevenir que el navegador use el botón "Atrás" para acceder a páginas en caché
(function() {
    if (typeof window.history.pushState === 'function') {
        window.history.pushState(null, null, window.location.href);
        
        window.addEventListener('popstate', function() {
            window.history.pushState(null, null, window.location.href);
        });
    }
})();

// Deshabilitar el caché de la página
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});

// Prevenir el almacenamiento en caché del navegador
if (window.performance && window.performance.navigation.type === 2) {
    // La página fue accedida mediante el botón atrás/adelante
    window.location.reload();
}
