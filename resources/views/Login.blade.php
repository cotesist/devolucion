@extends('app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-sm" style="width: 380px;">
        <div class="card-body p-4">
            <h4 class="text-center mb-4">Devolución de Equipos</h4>
            <div id="alerta" class="alert alert-danger d-none"></div>
            <form id="formLogin">
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('formLogin').addEventListener('submit', async (e) => {
    e.preventDefault();
    const alerta = document.getElementById('alerta');
    alerta.classList.add('d-none');

    try {
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: {'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
            })
        });
        const data = await res.json();

        if (!res.ok) {
            alerta.textContent = data.message || 'No se pudo iniciar sesión';
            alerta.classList.remove('d-none');
            return;
        }

        localStorage.setItem('token', data.access_token);
        localStorage.setItem('usuario', JSON.stringify(data.user));
        window.location.href = '/dashboard';
    } catch (err) {
        alerta.textContent = 'Error de conexión con el servidor.';
        alerta.classList.remove('d-none');
    }
});
</script>
@endsection