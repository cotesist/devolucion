@extends('app')

@section('titulo', 'Panel - Devolución de Equipos')

@section('contenido')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark no-print">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-box-seam"></i> Devolución de Equipos</span>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-light btn-sm" data-seccion="registro">Registro de Equipos</button>
            <button class="btn btn-outline-light btn-sm d-none" id="navUsuarios" data-seccion="usuarios">Usuarios</button>
            <span class="text-white small ms-3" id="lblUsuario"></span>
            <button class="btn btn-danger btn-sm ms-2" id="btnLogout">Salir</button>
        </div>
    </div>
</nav>

<div class="container-fluid py-4">

    {{-- ================= SECCIÓN USUARIOS ================= --}}
    <div id="seccionUsuarios" class="d-none">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Gestión de Usuarios</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="nuevoUsuario()">
                <i class="bi bi-plus-circle"></i> Nuevo usuario
            </button>
        </div>
        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0" id="tablaUsuarios">
                    <thead class="table-light">
                        <tr><th>Nombre</th><th>Correo</th><th>Rol</th><th class="text-end">Acciones</th></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Usuario --}}
    <div class="modal fade" id="modalUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formUsuario">
                    <div class="modal-header">
                        <h5 class="modal-title">Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="usuarioId">
                        <div class="mb-2">
                            <label class="form-label">Nombre</label>
                            <input class="form-control" id="usuarioNombre" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" id="usuarioEmail" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Contraseña <small class="text-muted">(vacío = no cambiar)</small></label>
                            <input type="password" class="form-control" id="usuarioPassword">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Rol</label>
                            <select class="form-select" id="usuarioRol" required>
                                <option value="registrador">Registrador</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= SECCIÓN REGISTRO DE EQUIPOS ================= --}}
    <div id="seccionRegistro">
        <h4 class="mb-3">Registro de Equipos Devueltos</h4>

        <div class="card mb-3">
            <div class="card-body">
                <label class="form-label">Teléfono del cliente</label>
                <div class="input-group" style="max-width:400px;">
                    <input type="text" class="form-control" id="inputTelefono" maxlength="7" placeholder="7 dígitos, ej: 6453562">
                    <button class="btn btn-primary" id="btnBuscarTelefono"><i class="bi bi-search"></i> Buscar</button>
                </div>
                <div class="form-text text-danger" id="errorTelefono"></div>
            </div>
        </div>

        <div class="card mb-3 d-none" id="cardCliente">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="card-title" id="clienteNombre"></h5>
                <p class="mb-1">Teléfono: <span id="clienteTelefono"></span></p>
                <p class="mb-0">
                    Servicios:
                    <span class="badge bg-info" id="badgeInternet">Internet</span>
                    <span class="badge bg-warning text-dark" id="badgeTv">Televisión</span>
                </p>
            </div>
            <div>
                <button class="btn btn-success" id="btnIniciarRegistro">
                    <i class="bi bi-clipboard-plus"></i> Nueva orden / registro
                </button>
            </div>
        </div>

        <hr>

        {{-- Selector de Órdenes del Cliente --}}
        <div class="row align-items-center">
            <div class="col-auto">
                <label for="selectOrdenes" class="form-label fw-bold mb-0">Órdenes del cliente:</label>
            </div>
            <div class="col-md-5">
                <select class="form-select" id="selectOrdenes">
                    <!-- Se poblará dinámicamente con las órdenes -->
                </select>
            </div>
        </div>
    </div>
</div>

        <div id="cardEquipos" class="d-none">
            <div class="card mb-3">
                <div class="card-body">
                    <label class="form-label">Categoría de equipo</label>
                    <select class="form-select mb-3" id="selectCategoria" style="max-width:350px;">
                        <option value="">Seleccione categoría...</option>
                        <option value="settop_box">SETTOP BOX</option>
                        <option value="decodificador">DECODIFICADOR</option>
                        <option value="adsl">ADSL</option>
                        <option value="cablemodem">CABLEMODEM</option>
                        <option value="gepon">GEPON</option>
                        <option value="gpon">GPON</option>
                    </select>

                    <table class="table table-bordered table-vertical w-auto" id="tablaFormularioEquipo" style="display:none;">
                        <tbody id="camposEquipoBody"></tbody>
                    </table>

                    <input type="hidden" id="equipoEditandoId">
                    <button class="btn btn-primary d-none" id="btnGuardarEquipo"><i class="bi bi-save"></i> Guardar equipo</button>
                    <button class="btn btn-secondary d-none" id="btnCancelarEdicionEquipo">Cancelar edición</button>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body p-0">
                    <table class="table mb-0" id="tablaEquiposRegistrados">
                        <thead class="table-light">
                            <tr><th>Categoría</th><th>Serie/ID</th><th>Estado</th><th>Detalle</th><th class="text-end">Acciones</th></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <label class="form-label">Observaciones generales</label>
                    <textarea class="form-control mb-3" id="observacionesGenerales" rows="3"></textarea>
                    <button class="btn btn-primary" id="btnGuardarRegistro"><i class="bi bi-save"></i> Guardar</button>
                    <button class="btn btn-outline-dark" id="btnImprimir"><i class="bi bi-printer"></i> Imprimir</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const token = () => localStorage.getItem('token');

// Helper API flexible para manejar prefijos /api sin duplicarlos
async function api(path, options = {}) {
    const cleanPath = path.startsWith('/') ? path : '/' + path;
    const url = cleanPath.startsWith('/api') ? cleanPath : '/api' + cleanPath;

    options.headers = Object.assign({
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token(),
    }, options.headers || {});

    const res = await fetch(url, options);

    if (res.status === 401) {
        localStorage.clear();
        window.location.href = '/login';
        throw new Error('No autorizado');
    }

    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        const err = new Error(data.message || 'Error de solicitud');
        err.data = data;
        throw err;
    }
    return data;
}

function mostrarErrores(err) {
    let msg = err.message;
    if (err.data && err.data.errors) {
        msg = Object.values(err.data.errors).flat().join(' | ');
    }
    alert(msg);
}

// ---------- Sesión / navegación ----------
if (!token()) window.location.href = '/login';

let usuarioActual = JSON.parse(localStorage.getItem('usuario') || '{}');
let usuariosCache = []; // Caché local para evitar concatenaciones HTML peligrosas

document.getElementById('lblUsuario').textContent = `${usuarioActual.name || ''} (${usuarioActual.role || ''})`;
if (usuarioActual.role === 'admin') {
    document.getElementById('navUsuarios').classList.remove('d-none');
}

document.querySelectorAll('[data-seccion]').forEach(btn => {
    btn.addEventListener('click', () => mostrarSeccion(btn.dataset.seccion));
});

function mostrarSeccion(nombre) {
    document.getElementById('seccionUsuarios').classList.toggle('d-none', nombre !== 'usuarios');
    document.getElementById('seccionRegistro').classList.toggle('d-none', nombre !== 'registro');
    if (nombre === 'usuarios') cargarUsuarios();
}

document.getElementById('btnLogout').addEventListener('click', async () => {
    try { await api('/logout', { method: 'POST' }); } catch (e) {}
    localStorage.clear();
    window.location.href = '/login';
});

// ---------- USUARIOS ----------
async function cargarUsuarios() {
    try {
        usuariosCache = await api('/usuarios');
        const tbody = document.querySelector('#tablaUsuarios tbody');
        tbody.innerHTML = '';
        usuariosCache.forEach(u => {
            tbody.innerHTML += `
            <tr>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td><span class="badge bg-${u.role === 'admin' ? 'dark' : 'secondary'}">${u.role}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary btn-editar-usr" data-id="${u.id}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarUsuario(${u.id})"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`;
        });

        document.querySelectorAll('.btn-editar-usr').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                const user = usuariosCache.find(x => x.id === id);
                if (user) editarUsuario(user);
            });
        });
    } catch (err) { mostrarErrores(err); }
}

function nuevoUsuario() {
    document.getElementById('formUsuario').reset();
    document.getElementById('usuarioId').value = '';
}

function editarUsuario(u) {
    document.getElementById('usuarioId').value = u.id;
    document.getElementById('usuarioNombre').value = u.name;
    document.getElementById('usuarioEmail').value = u.email;
    document.getElementById('usuarioPassword').value = '';
    document.getElementById('usuarioRol').value = u.role;
    new bootstrap.Modal(document.getElementById('modalUsuario')).show();
}

async function eliminarUsuario(id) {
    if (!confirm('¿Eliminar este usuario?')) return;
    try {
        await api(`/usuarios/${id}`, { method: 'DELETE' });
        cargarUsuarios();
    } catch (err) { mostrarErrrores(err); }
}

document.getElementById('formUsuario').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('usuarioId').value;
    const payload = {
        name: document.getElementById('usuarioNombre').value,
        email: document.getElementById('usuarioEmail').value,
        password: document.getElementById('usuarioPassword').value,
        role: document.getElementById('usuarioRol').value,
    };
    if (!payload.password) delete payload.password; // No enviar contraseña si viene vacía

    try {
        if (id) {
            await api(`/usuarios/${id}`, { method: 'PUT', body: JSON.stringify(payload) });
        } else {
            await api('/usuarios', { method: 'POST', body: JSON.stringify(payload) });
        }
        bootstrap.Modal.getInstance(document.getElementById('modalUsuario')).hide();
        cargarUsuarios();
    } catch (err) { mostrarErrores(err); }
});

// ---------- REGISTRO DE EQUIPOS ----------
let registroActual = null;

function telefonoValido(tel) {
    return /^6[1-4][0-9]{5}$/.test(tel);
}

document.getElementById('btnBuscarTelefono').addEventListener('click', buscarCliente);

// Variable global para almacenar la lista de órdenes encontradas
let listaOrdenesCliente = [];

async function buscarCliente() {
    const tel = document.getElementById('inputTelefono').value.trim();
    const errorEl = document.getElementById('errorTelefono');
    errorEl.textContent = '';

    if (!telefonoValido(tel)) {
        errorEl.textContent = 'El teléfono debe tener 7 dígitos, en el rango 6100000 - 6499999.';
        return;
    }

    try {
        // 1. Datos del cliente (API externa)
        const cliente = await api(`/cliente/${tel}`);
        document.getElementById('clienteNombre').textContent = cliente.nombre;
        document.getElementById('clienteTelefono').textContent = cliente.telefono;
        document.getElementById('badgeInternet').classList.toggle('d-none', !cliente.internet);
        document.getElementById('badgeTv').classList.toggle('d-none', !cliente.television);
        document.getElementById('cardCliente').classList.remove('d-none');

        // 2. Buscar TODAS las órdenes del cliente en la BD local
        const resRegistros = await api(`/registros?telefono=${tel}`);
        listaOrdenesCliente = Array.isArray(resRegistros) ? resRegistros : (resRegistros.data || []);

        const selectOrdenes = document.getElementById('selectOrdenes');
        selectOrdenes.innerHTML = '';

        if (listaOrdenesCliente.length > 0) {
            // Llenar el combo con todas las órdenes disponibles
            listaOrdenesCliente.forEach((reg) => {
                const fecha = new Date(reg.created_at).toLocaleDateString();
                const opt = document.createElement('option');
                opt.value = reg.id;
                opt.textContent = `${reg.numero_orden} - (${fecha})`;
                selectOrdenes.appendChild(opt);
            });

            // Seleccionar por defecto la más reciente (la primera)
            seleccionarOrden(listaOrdenesCliente[0].id, cliente);
        } else {
            // Sin órdenes previas
            selectOrdenes.innerHTML = '<option value="">Sin órdenes registradas</option>';
            registroActual = { cliente };
            document.getElementById('cardEquipos').classList.add('d-none');
        }

    } catch (err) {
        document.getElementById('cardCliente').classList.add('d-none');
        document.getElementById('cardEquipos').classList.add('d-none');
        errorEl.textContent = err.message || 'Error al buscar el cliente';
    }
}

// Función auxiliar para activar la orden seleccionada en pantalla
async function seleccionarOrden(registroId, cliente) {
    const orden = listaOrdenesCliente.find(r => r.id == registroId);
    if (!orden) return;

    registroActual = orden;
    if (cliente) registroActual.cliente = cliente;

    // Mostrar sección de equipos y cargar sus observaciones/equipos
    document.getElementById('cardEquipos').classList.remove('d-none');
    document.getElementById('observacionesGenerales').value = orden.observaciones || '';
    
    // Limpiar formulario de inserción
    document.getElementById('selectCategoria').value = '';
    document.getElementById('tablaFormularioEquipo').style.display = 'none';

    // Cargar equipos de esta orden específica
    await cargarEquiposDelRegistro();
}

// Evento al cambiar de orden en el desplegable
document.getElementById('selectOrdenes').addEventListener('change', (e) => {
    const id = e.target.value;
    if (id) {
        seleccionarOrden(id, registroActual ? registroActual.cliente : null);
    }
});

document.getElementById('btnIniciarRegistro').addEventListener('click', async () => {
    try {
        const cliente = registroActual.cliente;
        const registro = await api('/registros', {
            method: 'POST',
            body: JSON.stringify({
                telefono: cliente.telefono,
                nombre_cliente: cliente.nombre,
                internet: !!cliente.internet,
                television: !!cliente.television,
            })
        });

        // 1. Guardar la nueva orden como la activa
        registroActual = registro;
        registroActual.cliente = cliente;

        // 2. Agregar la nueva orden al arreglo local y al <select>
        listaOrdenesCliente.unshift(registro); // La agregamos al inicio de la lista
        
        const selectOrdenes = document.getElementById('selectOrdenes');
        const fecha = new Date(registro.created_at).toLocaleDateString();
        const opt = document.createElement('option');
        opt.value = registro.id;
        opt.textContent = `${registro.numero_orden} - (${fecha})`;
        
        // Insertar la nueva opción al inicio del select y seleccionarla
        selectOrdenes.insertBefore(opt, selectOrdenes.firstChild);
        selectOrdenes.value = registro.id;

        // 3. Preparar la vista para registrar equipos en la nueva orden
        document.getElementById('cardEquipos').classList.remove('d-none');
        document.getElementById('observacionesGenerales').value = '';
        document.querySelector('#tablaEquiposRegistrados tbody').innerHTML = '';
        document.getElementById('selectCategoria').value = '';
        document.getElementById('tablaFormularioEquipo').style.display = 'none';

        alert(`Nueva orden creada: ${registro.numero_orden}`);

    } catch (err) { mostrarErrores(err); }
});

const EQUIPO_CAMPOS = {
    settop_box: {
        label: 'SETTOP BOX',
        campos: [
            { name: 'serie', label: 'Serie', tipo: 'text' },
            { name: 'box_id', label: 'Box ID', tipo: 'text' },
            { name: 'estado', label: 'Estado', tipo: 'estado' },
            { name: 'adaptador', label: 'Adaptador', tipo: 'bool' },
            { name: 'control', label: 'Control', tipo: 'bool' },
            { name: 'remoto', label: 'Remoto', tipo: 'bool' },
            { name: 'cable_hdmi', label: 'Cable HDMI', tipo: 'bool' },
            { name: 'cable_audio_video', label: 'Cable Audio/Video', tipo: 'bool' },
            { name: 'observaciones', label: 'Observaciones', tipo: 'textarea' },
        ]
    },
    decodificador: {
        label: 'DECODIFICADOR',
        campos: [
            { name: 'serie', label: 'Serie AE', tipo: 'text' },
            { name: 'estado', label: 'Estado', tipo: 'estado' },
            { name: 'control_remoto', label: 'Control Remoto', tipo: 'bool' },
            { name: 'observaciones', label: 'Observaciones', tipo: 'textarea' },
        ]
    },
    adsl: {
        label: 'ADSL',
        campos: [
            { name: 'serie', label: 'Serie', tipo: 'text' },
            { name: 'mac', label: 'MAC', tipo: 'text' },
            { name: 'estado', label: 'Estado', tipo: 'estado' },
            { name: 'adaptador', label: 'Adaptador', tipo: 'bool' },
            { name: 'cable_rj45', label: 'Cable RJ45', tipo: 'bool' },
            { name: 'cable_rj11', label: 'Cable RJ11', tipo: 'bool' },
            { name: 'filtro_tel', label: 'Filtro Tel.', tipo: 'bool' },
            { name: 'observaciones', label: 'Observaciones', tipo: 'textarea' },
        ]
    },
    cablemodem: {
        label: 'CABLEMODEM',
        campos: [
            { name: 'serie', label: 'Serie', tipo: 'text' },
            { name: 'mta_mac', label: 'MTA MAC', tipo: 'text' },
            { name: 'estado', label: 'Estado', tipo: 'estado' },
            { name: 'adaptador', label: 'Adaptador', tipo: 'bool' },
            { name: 'cable_rj45', label: 'Cable RJ45', tipo: 'bool' },
            { name: 'observaciones', label: 'Observaciones', tipo: 'textarea' },
        ]
    },
    gepon: {
        label: 'GEPON',
        campos: [
            { name: 'serie', label: 'Serie', tipo: 'text' },
            { name: 'mac', label: 'MAC', tipo: 'text' },
            { name: 'estado', label: 'Estado', tipo: 'estado' },
            { name: 'adaptador', label: 'Adaptador', tipo: 'bool' },
            { name: 'observaciones', label: 'Observaciones', tipo: 'textarea' },
        ]
    },
    gpon: {
        label: 'GPON',
        campos: [
            { name: 'serie', label: 'Serie', tipo: 'text' },
            { name: 'mac', label: 'MAC', tipo: 'text' },
            { name: 'estado', label: 'Estado', tipo: 'estado' },
            { name: 'adaptador', label: 'Adaptador', tipo: 'bool' },
            { name: 'cable_rj45', label: 'Cable RJ45', tipo: 'bool' },
            { name: 'cable_rj11', label: 'Cable RJ11', tipo: 'bool' },
            { name: 'observaciones', label: 'Observaciones', tipo: 'textarea' },
        ]
    },
};

document.getElementById('selectCategoria').addEventListener('change', () => {
    document.getElementById('equipoEditandoId').value = '';
    document.getElementById('btnCancelarEdicionEquipo').classList.add('d-none');
    renderFormularioEquipo();
});

function renderFormularioEquipo(valores = {}) {
    const cat = document.getElementById('selectCategoria').value;
    const tabla = document.getElementById('tablaFormularioEquipo');
    const tbody = document.getElementById('camposEquipoBody');
    tbody.innerHTML = '';

    if (!cat || !EQUIPO_CAMPOS[cat]) {
        tabla.style.display = 'none';
        document.getElementById('btnGuardarEquipo').classList.add('d-none');
        return;
    }

    EQUIPO_CAMPOS[cat].campos.forEach(campo => {
        let input = '';
        const val = valores[campo.name];
        if (campo.tipo === 'text') {
            input = `<input type="text" class="form-control" data-campo="${campo.name}" value="${val ?? ''}">`;
        } else if (campo.tipo === 'estado') {
            input = `
            <select class="form-select" data-campo="estado">
                <option value="bien" ${val === 'bien' || !val ? 'selected' : ''}>Bien</option>
                <option value="mal" ${val === 'mal' ? 'selected' : ''}>Mal</option>
            </select>`;
        } else if (campo.tipo === 'bool') {
            input = `<input type="checkbox" class="form-check-input" data-campo="${campo.name}" ${val ? 'checked' : ''}>`;
        } else if (campo.tipo === 'textarea') {
            input = `<textarea class="form-control" data-campo="${campo.name}" rows="2">${val ?? ''}</textarea>`;
        }
        tbody.innerHTML += `<tr><th>${campo.label}</th><td>${input}</td></tr>`;
    });

    tabla.style.display = '';
    document.getElementById('btnGuardarEquipo').classList.remove('d-none');
}

document.getElementById('btnGuardarEquipo').addEventListener('click', async () => {
    const cat = document.getElementById('selectCategoria').value;
    if (!cat) return;

    const payload = { categoria: cat, estado: 'bien' };
    document.querySelectorAll('#camposEquipoBody [data-campo]').forEach(el => {
        if (el.type === 'checkbox') {
            payload[el.dataset.campo] = el.checked;
        } else {
            payload[el.dataset.campo] = el.value;
        }
    });

    const editandoId = document.getElementById('equipoEditandoId').value;

    try {
        if (editandoId) {
            await api(`/equipos/${editandoId}`, { method: 'PUT', body: JSON.stringify(payload) });
        } else {
            await api(`/registros/${registroActual.id}/equipos`, { method: 'POST', body: JSON.stringify(payload) });
        }
        await cargarEquiposDelRegistro();
        document.getElementById('selectCategoria').value = '';
        document.getElementById('equipoEditandoId').value = '';
        document.getElementById('btnCancelarEdicionEquipo').classList.add('d-none');
        renderFormularioEquipo();
    } catch (err) { mostrarErrores(err); }
});

document.getElementById('btnCancelarEdicionEquipo').addEventListener('click', () => {
    document.getElementById('equipoEditandoId').value = '';
    document.getElementById('selectCategoria').value = '';
    document.getElementById('btnCancelarEdicionEquipo').classList.add('d-none');
    renderFormularioEquipo();
});

async function cargarEquiposDelRegistro() {
    try {
        const registro = await api(`/registros/${registroActual.id}`);
        registroActual.equipos = registro.equipos;
        const tbody = document.querySelector('#tablaEquiposRegistrados tbody');
        tbody.innerHTML = '';
        
        registro.equipos.forEach(eq => {
            const configCat = EQUIPO_CAMPOS[eq.categoria];
            const labelCat = configCat ? configCat.label : eq.categoria;
            const detalle = configCat ? configCat.campos
                .filter(c => c.tipo === 'bool' && eq[c.name])
                .map(c => c.label).join(', ') : '';

            tbody.innerHTML += `
            <tr>
                <td>${labelCat}</td>
                <td>${eq.serie || eq.box_id || eq.mac || '-'}</td>
                <td><span class="badge bg-${eq.estado === 'bien' ? 'success' : 'danger'}">${eq.estado}</span></td>
                <td class="small text-muted">${detalle}</td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary btn-editar-eq" data-id="${eq.id}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarEquipo(${eq.id})"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`;
        });

        document.querySelectorAll('.btn-editar-eq').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                const eq = registroActual.equipos.find(x => x.id === id);
                if (eq) cargarEdicionEquipo(eq);
            });
        });
    } catch (err) { mostrarErrores(err); }
}

function cargarEdicionEquipo(eq) {
    document.getElementById('selectCategoria').value = eq.categoria;
    document.getElementById('equipoEditandoId').value = eq.id;
    document.getElementById('btnCancelarEdicionEquipo').classList.remove('d-none');
    renderFormularioEquipo(eq);
}

async function eliminarEquipo(id) {
    if (!confirm('¿Eliminar este equipo del registro?')) return;
    try {
        await api(`/equipos/${id}`, { method: 'DELETE' });
        await cargarEquiposDelRegistro();
    } catch (err) { mostrarErrores(err); }
}

document.getElementById('btnGuardarRegistro').addEventListener('click', async () => {
    try {
        await api(`/registros/${registroActual.id}`, {
            method: 'PUT',
            body: JSON.stringify({ observaciones: document.getElementById('observacionesGenerales').value })
        });
        alert('Registro guardado correctamente.');
    } catch (err) { mostrarErrores(err); }
});

document.getElementById('btnImprimir').addEventListener('click', async () => {
    try {
        const registro = await api(`/registros/${registroActual.id}`);
        imprimirRegistro(registro);
    } catch (err) { mostrarErrores(err); }
});

function imprimirRegistro(registro) {
    const filasEquipos = (registro.equipos || []).map(eq => {
        const configCat = EQUIPO_CAMPOS[eq.categoria];
        const campos = configCat ? configCat.campos.map(c => {
            let valor = eq[c.name];
            if (c.tipo === 'bool') valor = valor ? 'Sí' : 'No';
            return `<b>${c.label}:</b> ${valor ?? '-'}`;
        }).join(' &nbsp;|&nbsp; ') : '';
        return `<tr><td>${configCat ? configCat.label : eq.categoria}</td><td>${campos}</td></tr>`;
    }).join('');

    const html = `
    <html>
    <head>
        <title>Orden ${registro.numero_orden}</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            h2, h4 { margin: 0 0 6px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 12px; }
            td, th { border: 1px solid #999; padding: 6px 8px; font-size: 13px; vertical-align: top; }
            .cabecera { display:flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom:10px; margin-bottom:10px;}
        </style>
    </head>
    <body onload="window.print()">
        <div class="cabecera">
            <div>
                <h2>Orden de Devolución de Equipos</h2>
                <div>Cliente: <b>${registro.nombre_cliente}</b></div>
                <div>Teléfono: <b>${registro.telefono}</b></div>
                <div>Servicios: ${registro.internet ? 'Internet ' : ''} ${registro.television ? 'Televisión' : ''}</div>
            </div>
            <div>
                <h4>N° Orden: ${registro.numero_orden}</h4>
                <div>Fecha: ${new Date(registro.created_at).toLocaleString()}</div>
                <div>Registrado por: ${registro.usuario ? registro.usuario.name : ''}</div>
            </div>
        </div>

        <table>
            <thead><tr><th style="width:150px;">Categoría</th><th>Detalle</th></tr></thead>
            <tbody>${filasEquipos || '<tr><td colspan="2">Sin equipos registrados</td></tr>'}</tbody>
        </table>

        <h4 style="margin-top:16px;">Observaciones</h4>
        <p>${registro.observaciones || '-'}</p>
    </body>
    </html>`;

    const ventana = window.open('', '_blank');
    ventana.document.write(html);
    ventana.document.close();
}
</script>
@endsection