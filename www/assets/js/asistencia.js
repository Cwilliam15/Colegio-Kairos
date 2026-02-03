document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formAsistencia');
    const inputCUI = document.getElementById('inputCUI');
    const mensaje = document.getElementById('mensaje');

    inputCUI.focus();

    inputCUI.addEventListener('change', function() {
        const cui = inputCUI.value.trim();

        if (cui !== '') {
            fetch('../../backend/php_Rep/registrar_asistencia.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'cui=' + encodeURIComponent(cui)
            })
            .then(response => response.text())
            .then(data => {
                mensaje.innerText = data;
                mensaje.style.color = data.includes('✅') ? 'green' : 'red';

                inputCUI.value = '';
                inputCUI.focus();
            })
            .catch(error => {
                console.error('Error:', error);
                mensaje.innerText = '❌ Error al registrar.';
                mensaje.style.color = 'red';
            });
        }
    });
});