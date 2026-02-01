<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestAPI Clientes </title>
    <meta name="description" content="Dashboard premium para la gestión de clientes.">
    <!-- Google Fonts: Outfit y JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-glow: rgba(139, 92, 246, 0.4);
            --secondary: #10b981;
            --secondary-glow: rgba(16, 185, 129, 0.4);
            --bg-dark: #050505;
            --card-bg: rgba(15, 15, 15, 0.6);
            --text-main: #ffffff;
            --text-muted: #94a3b8;
            --danger: #ff4d4d;
            --glass-border: rgba(255, 255, 255, 0.08);
            --transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.1) 0%, transparent 40%);
        }

        /* Fondo animado */
        .bg-mesh {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            opacity: 0.5;
            filter: blur(100px);
        }
        .bg-orb-1 { position: absolute; top: -10%; left: 30%; width: 500px; height: 500px; background: var(--primary); border-radius: 50%; opacity: 0.2; animation: float 20s infinite alternate; }
        .bg-orb-2 { position: absolute; bottom: -10%; right: 20%; width: 400px; height: 400px; background: var(--secondary); border-radius: 50%; opacity: 0.15; animation: float 15s infinite alternate-reverse; }

        @keyframes float {
            from { transform: translate(0, 0); }
            to { transform: translate(50px, 100px); }
        }

        .container {
            width: 100%;
            max-width: 1300px;
            padding: 3rem 1.5rem;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        header { text-align: left; margin-bottom: 4rem; display: flex; align-items: flex-end; justify-content: space-between; }
        .header-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            letter-spacing: -2px;
            background: linear-gradient(135deg, #fff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header-content p { color: var(--text-muted); font-size: 1.1rem; }

        .grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 2.5rem;
            align-items: start;
        }

        @media (max-width: 1000px) { .grid { grid-template-columns: 1fr; } }

        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        h2 { margin-bottom: 2rem; font-size: 1.25rem; font-weight: 600; color: #fff; letter-spacing: 0.5px; opacity: 0.9; }

        /* Estilos de Formulario */
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.6rem; color: var(--text-muted); font-size: 0.85rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; }
        input {
            width: 100%;
            padding: 1rem 1.2rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            color: #fff;
            font-family: inherit;
            font-size: 1rem;
            outline: none;
            transition: var(--transition);
        }
        input:focus {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--primary);
            box-shadow: 0 0 20px var(--primary-glow);
        }

        .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .btn-primary { 
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 10px 20px -5px var(--primary-glow);
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -5px var(--primary-glow); }
        
        .btn-outline { 
            background: transparent; 
            border: 1px solid var(--glass-border); 
            color: var(--text-muted);
            margin-top: 0.75rem;
        }
        .btn-outline:hover { background: rgba(255, 255, 255, 0.05); color: #fff; border-color: #fff; }

        /* Tabla Estilizada */
        .table-container { 
            overflow: hidden; 
            border-radius: 1.5rem; 
        }
        table { width: 100%; border-collapse: separate; border-spacing: 0 0.75rem; }
        th { 
            text-align: left; 
            padding: 1rem 1.5rem; 
            color: var(--text-muted); 
            font-weight: 500; 
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tbody tr { 
            background: rgba(255, 255, 255, 0.02); 
            transition: var(--transition);
        }
        tbody tr:hover { 
            background: rgba(255, 255, 255, 0.05); 
            transform: scale(1.01);
            box-shadow: 0 10px 20px -10px rgba(0,0,0,0.5);
        }
        td { 
            padding: 1.25rem 1.5rem; 
            font-size: 0.95rem;
        }
        td:first-child { border-radius: 1rem 0 0 1rem; color: var(--primary); font-family: 'JetBrains Mono', monospace; font-weight: 500; }
        td:last-child { border-radius: 0 1rem 1rem 0; }

        .actions { display: flex; gap: 1rem; }
        .icon-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            cursor: pointer;
            padding: 0.6rem;
            border-radius: 0.75rem;
            color: var(--text-muted);
            transition: var(--transition);
        }
        .icon-btn:hover { color: #fff; transform: translateY(-2px); }
        .edit-icon:hover { background: var(--secondary-glow); color: var(--secondary); border-color: var(--secondary); }
        .delete-icon:hover { background: rgba(239, 68, 68, 0.2); color: var(--danger); border-color: var(--danger); }

        /* Notificaciones Toast */
        #toast-container { position: fixed; bottom: 2rem; right: 2rem; z-index: 1000; }
        .toast {
            background: rgba(20, 20, 20, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            margin-top: 0.75rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            animation: slideIn 0.3s ease-out forwards;
        }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast.success { border-left: 4px solid var(--secondary); }
        .toast.error { border-left: 4px solid var(--danger); }

        .loading-dots { font-family: 'JetBrains Mono', monospace; }

        /* Símbolos API */
        .api-badge {
            font-family: 'JetBrains Mono', monospace;
            padding: 0.2rem 0.6rem;
            border-radius: 0.4rem;
            font-size: 0.7rem;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
        }
    </style>
</head>
<body>
    <div class="bg-mesh">
        <div class="bg-orb-1"></div>
        <div class="bg-orb-2"></div>
        <!-- CAMBIO 1: Agregado un tercer orbe decorativo para más profundidad visual -->
        <div class="bg-orb-3"></div>
    </div>

    <div class="container">
        <header>
            <div class="header-content">
                <!-- CAMBIO 2: Se añadió "Pro" y el número de versión al título principal -->
                <h1>Burzu <span style="font-weight: 300; opacity: 0.5;">Pro v2.0</span></h1>
                
                <!-- CAMBIO 3: Se cambió el subtítulo de "Clientes" a "Panel Administrativo" -->
                <p>Panel Administrativo</p>
            </div>
            <div class="api-info">
                <!-- CAMBIO 4: Se actualizó la ruta del ENDPOINT para que sea más específica -->
                <span class="api-badge">ENDPOINT: /api/v1/users/</span>
                <span class="api-badge" style="color: var(--secondary);">STATUS: ONLINE</span>
            </div>
        </header>

        <div class="grid">
            <!-- Formulario de Entrada -->
            <div class="panel">
                <!-- Codigo de conflicto -->
                <h2 id="form-title">Datos</h2>
                <form id="cliente-form">
                    <input type="hidden" id="cliente-id">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" placeholder="Nombre completo" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Dirección de Enlace (Email)</label>
                        <input type="email" id="email" placeholder="correo@servidor.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        <span id="btn-text">PROCESAR DATOS</span>
                    </button>
                    <button type="button" class="btn btn-outline" id="btn-cancel" style="display: none; background: transparent; border: 1px solid var(--text-muted); color: var(--text-muted);">REVERTIR CAMBIOS</button>
                </form>
            </div>

            <!-- Listado de Clientes -->
            <div class="panel">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h2>📦 Buffer de Registros</h2>
                    <button class="icon-btn" onclick="fetchClientes()" title="Sincronizar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                    </button>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>CRC_ID</th>
                                <th>NOMBRE</th>
                                <th>Correo Electronico</th>
                                <th style="text-align: right;">OPERACIONES</th>
                            </tr>
                        </thead>
                        <tbody id="clientes-body">
                            <tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 4rem;">
                                <div class="loading-dots">INICIALIZANDO_SISTEMA...</div>
                            </td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 100;"></div>

    <script>
        // Simulación de datos para que la vista previa funcione sin backend
        const MOCK_DATA = [
            { id: 1, nombre: "Juan Pérez", email: "juan@tech.com" },
            { id: 2, nombre: "Ana García", email: "ana@systems.net" }
        ];

        const API_BASE = 'clientes/';
        const form = document.getElementById('cliente-form');
        const btnSubmit = document.getElementById('btn-submit');
        const btnText = document.getElementById('btn-text');
        const btnCancel = document.getElementById('btn-cancel');
        const formTitle = document.getElementById('form-title');
        const clientesBody = document.getElementById('clientes-body');
        
        let editingId = null;

        // Cargar clientes al iniciar
        document.addEventListener('DOMContentLoaded', fetchClientes);

        async function fetchClientes() {
            try {
                // Simulación para el preview (reemplazar con fetch real)
                const data = { records: MOCK_DATA }; 
                // const response = await fetch(`${API_BASE}seleccionar.php`);
                // const data = await response.json();
                
                clientesBody.innerHTML = '';
                
                if (data.records && data.records.length > 0) {
                    data.records.forEach(cliente => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>#${cliente.id.toString().padStart(3, '0')}</td>
                            <td style="font-weight: 600;">${cliente.nombre}</td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">${cliente.email}</td>
                            <td style="text-align: right;">
                                <div class="actions" style="display: flex; gap: 10px; justify-content: flex-end;">
                                    <button class="icon-btn edit-icon" onclick="editCliente(${cliente.id}, '${cliente.nombre}', '${cliente.email}')" title="Modificar">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <button class="icon-btn delete-icon" onclick="deleteCliente(${cliente.id})" title="Purgar">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </div>
                            </td>
                        `;
                        clientesBody.appendChild(tr);
                    });
                } else {
                    clientesBody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 4rem; color: var(--text-muted);">Sin registros en el buffer.</td></tr>';
                }
            } catch (error) {
                showToast('Error de sincronización con el servidor', 'error');
                clientesBody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 4rem; color: var(--danger);">[ERROR_DE_CONEXIÓN]</td></tr>';
            }
        }

        form.onsubmit = async (e) => {
            e.preventDefault();
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            
            toggleLoading(true);
            
            // Simulación de éxito
            setTimeout(() => {
                showToast('Solicitud enviada (Simulación)', 'success');
                resetForm();
                toggleLoading(false);
            }, 1000);
        };

        async function deleteCliente(id) {
            if (!confirm(`¿ESTÁS SEGURO DE PURGAR EL REGISTRO #${id}?`)) return;
            showToast('Registro purgado (Simulación)', 'success');
        }

        function editCliente(id, nombre, email) {
            editingId = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('email').value = email;
            
            formTitle.innerText = '⚠️ Modificando Registro';
            btnText.innerText = 'APLICAR CAMBIOS';
            btnCancel.style.display = 'block';
            
            const inputNombre = document.getElementById('nombre');
            inputNombre.focus();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        btnCancel.onclick = resetForm;

        function resetForm() {
            editingId = null;
            form.reset();
            formTitle.innerText = '⚡ Acceso de Datos';
            btnText.innerText = 'PROCESAR DATOS';
            btnCancel.style.display = 'none';
        }

        function toggleLoading(isLoading) {
            btnSubmit.disabled = isLoading;
            btnText.innerHTML = isLoading ? '<span class="loading-dots">PROCESANDO...</span>' : (editingId ? 'APLICAR CAMBIOS' : 'PROCESAR DATOS');
        }

        function showToast(msg, type) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.style.cssText = `background: var(--panel-bg); color: white; padding: 1rem; margin-top: 10px; border-left: 4px solid ${type === 'success' ? 'var(--secondary)' : 'var(--danger)'}; border-radius: 4px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); transition: all 0.3s ease;`;
            
            toast.innerHTML = `
                <div style="width: 8px; height: 8px; border-radius: 50%; background: ${type === 'success' ? 'var(--secondary)' : 'var(--danger)'}"></div>
                <span>${msg}</span>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(20px)';
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }
    </script>
</body>
</html>
