$(document).ready(function() {
    let forceEntryRut, forceEntryPatente, forceEntryTipo, nextEntryRut, nextEntryPatente, nextEntryTipo, nextEntryMetodo;

    function getParameterByName(name) {
        var url = new URL(window.location.href);
        console.log (url);
        return url.searchParams.get(name);
    }

    var patente = getParameterByName('patente');
    if (!patente) {
        alert('No se ha proporcionado la patente del bus.');
    }

    $('#entradaBtn').click(function() {
        var rut = $('#rut').val().trim();
        if (rut === '') {
            alert('Por favor, ingrese su RUT.');
        } else {
            checkLastEntry(rut, 'entrada', patente, 'manual');
        }
    });

    $('#salidaBtn').click(function() {
        var rut = $('#rut').val().trim();
        if (rut === '') {
            alert('Por favor, ingrese su RUT.');
        } else {
            checkLastEntry(rut, 'salida', patente, 'manual');
        }
    });

    function checkLastEntry(rut, tipo, patente, metodo) {
        axios.get('./php/check_last_entry.php', {
            params: { rut: rut }
        })
        .then(function(response) {
            var lastRecord = response.data;
            if (lastRecord && lastRecord.tipo === tipo) {
                $('#errorModal').modal('show');
                forceEntryRut = rut;
                forceEntryPatente = patente;
                forceEntryTipo = tipo === 'entrada' ? 'salida' : 'entrada';
                nextEntryRut = rut;
                nextEntryPatente = patente;
                nextEntryTipo = tipo;
                nextEntryMetodo = metodo;
            } else {
                logEntradaSalida(rut, tipo, patente, metodo);
            }
        })
        .catch(function(error) {
            console.error('Error al verificar el último registro:', error);
            alert('Error al verificar el último registro');
        });
    }

    $('#forceEventBtn').click(function() {
        logEntradaSalida(forceEntryRut, forceEntryTipo, forceEntryPatente, 'forzado', function() {
            logEntradaSalida(nextEntryRut, nextEntryTipo, nextEntryPatente, nextEntryMetodo);
        });
        $('#errorModal').modal('hide');
    });

    function logEntradaSalida(rut, tipo, patente, metodo, callback) {
        var currentTime = new Date().toLocaleString();
        var logMessage = `<p><strong>${tipo.toUpperCase()}</strong> -Rut: ${rut} - Fecha: ${currentTime} - Patente: <strong>${patente}</strong> - Método: <strong>${metodo}</strong></p><hr>`;
        $('#log').append(logMessage);

        // Preparar datos para enviar al API
        var data = {
            rut: rut,
            patente: patente,
            currentTime: currentTime,
            tipo: tipo,
            metodo: metodo
        };

        // Enviar datos al API usando Axios
        axios.post('./php/log_entry.php', data)
            .then(function(response) {
                console.log(response.data);
                alert('Datos registrados correctamente');
                if (callback) callback();
            })
            .catch(function(error) {
                console.error('Error al registrar los datos:', error);
                alert('Error al registrar los datos');
            });
    }
});
