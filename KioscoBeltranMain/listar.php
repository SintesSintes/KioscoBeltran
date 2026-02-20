<?php
session_start();
require "conexion.php"; // Carga $base_de_datos

// Verificá sesión (por si alguien entra sin loguearse)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once "encabezado.php";

// CONSULTA
$sentencia = $base_de_datos->query("SELECT * FROM productos ORDER BY id DESC;");
$productos = $sentencia ? $sentencia->fetch_all(MYSQLI_ASSOC) : [];
?>
<style>
/* Estilos premium (si no los tenés en un css global) */
.page-header {
    margin-bottom: 18px;
    padding: 20px;
    background: linear-gradient(135deg, #e6f7ff 0%, #ffffff 100%);
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(2,48,71,0.08);
    border-left: 6px solid #0d6efd;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 12px;
}
.page-header h1 { margin:0; color:#073a67; font-weight:700; }
.controls { display:flex; gap:10px; align-items:center; }
.input-search {
    min-width:240px;
    max-width:360px;
    border-radius: 10px;
    padding:8px 12px;
    border:1px solid rgba(13,110,253,0.12);
    box-shadow: 0 4px 12px rgba(13,110,253,0.05) inset;
}
.btn-premium {
    padding: 10px 18px;
    border-radius: 10px;
    background: linear-gradient(135deg,#0d6efd,#1ba8ff);
    color:white;
    font-weight:600;
    border:none;
    box-shadow: 0 8px 20px rgba(10,120,255,0.18);
}
.btn-secondary-light {
    background: rgba(255,255,255,0.85);
    border:1px solid rgba(13,110,253,0.12);
    color:#073a67;
    padding:8px 14px;
    border-radius:10px;
    font-weight:600;
}
.table-premium {
    width:100%;
    border-radius:12px;
    overflow:hidden;
    box-shadow: 0 10px 25px rgba(2,48,71,0.06);
}
.table-premium thead th {
    background: linear-gradient(90deg,#0d6efd,#42baff);
    color:white;
    font-weight:700;
    border:0;
    text-align:center;
}
.table-premium tbody td { background:white; vertical-align:middle; }
.icon-btn {
    padding:8px 10px;
    border-radius:8px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    text-decoration:none;
    border:0;
}
.icon-edit { background:#ffc107; color:#0b0b0b; }
.icon-delete { background:#dc3545; color:white; }
.small-muted { color:#6c757d; font-size:0.9rem; }
@media (max-width: 800px) {
    .controls { flex-direction: column; align-items:stretch; }
    .input-search { width:100%; }
}
</style>

<div class="container">

    <div class="page-header">
        <div>
            <h1>Productos</h1>
            <div class="small-muted">Gestión de stock — Agregá, editá y vendé rápido</div>
        </div>

        <div class="controls">
            <input id="searchInput" class="input-search" placeholder="Buscar por código o descripción..." type="text" />
            <a href="formulario.php" class="btn-premium">Nuevo Producto <i class="fa fa-plus ms-2"></i></a>
            <a href="vender.php" class="btn-secondary-light">Ir a Vender <i class="fa fa-cart-shopping ms-2"></i></a>
        </div>
    </div>

    <div class="table-responsive table-premium">
        <table id="productosTable" class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width:70px">ID</th>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th style="width:120px">Compra</th>
                    <th style="width:120px">Venta</th>
                    <th style="width:90px">Stock</th>
                    <th style="width:110px">Editar</th>
                    <th style="width:110px">Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos) === 0): ?>
                    <tr><td colspan="8" class="text-center p-4 small-muted">No hay productos. Agregá uno nuevo.</td></tr>
                <?php else: ?>
                    <?php foreach($productos as $producto): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($producto['id']) ?></td>
                            <td><?= htmlspecialchars($producto['codigo']) ?></td>
                            <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                            <td class="text-end">$<?= number_format($producto['precioCompra'],2,',','.') ?></td>
                            <td class="text-end">$<?= number_format($producto['precioVenta'],2,',','.') ?></td>
                            <td class="text-center"><?= htmlspecialchars($producto['existencia']) ?></td>
                            <td class="text-center">
                                <a class="icon-btn icon-edit" href="editar.php?id=<?= urlencode($producto['id']) ?>" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a class="icon-btn icon-delete" href="eliminar.php?id=<?= urlencode($producto['id']) ?>" title="Eliminar" onclick="return confirmDelete(event, <?= htmlspecialchars($producto['id']) ?>);">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
// Buscador en vivo (filtra filas de la tabla)
document.getElementById('searchInput').addEventListener('input', function(){
    const q = this.value.trim().toLowerCase();
    const rows = document.querySelectorAll('#productosTable tbody tr');
    rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        if (!cols.length) return;
        const codigo = cols[1].textContent.toLowerCase();
        const desc = cols[2].textContent.toLowerCase();
        const match = codigo.includes(q) || desc.includes(q);
        row.style.display = match ? '' : 'none';
    });
});

// Confirmación bonita al eliminar
function confirmDelete(e, id) {
    e.preventDefault();
    if (confirm('¿Querés eliminar el producto #' + id + '? Esta acción es irreversible.')) {
        window.location = e.currentTarget.getAttribute('href');
    }
    return false;
}
</script>

<?php include_once "pie.php"; ?>
