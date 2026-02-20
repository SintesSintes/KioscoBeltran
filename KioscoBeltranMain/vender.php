<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once "encabezado.php";

// Cargar productos
$productos = $base_de_datos->query("SELECT * FROM productos ORDER BY descripcion ASC;");
$productos = $productos->fetch_all(MYSQLI_ASSOC);
?>

<style>
/* ===== ESTILO PREMIUM ===== */

.page-title {
    font-size: 2rem;
    color: #073a67;
    font-weight: 800;
    text-shadow: 0 3px 12px rgba(0,0,0,0.15);
}

.wrapper-glass {
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(10px);
    padding: 25px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.btn-premium {
    background: linear-gradient(135deg,#009ffd,#00c3ff);
    color: white;
    border: none;
    padding: 12px 18px;
    border-radius: 10px;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(0,149,255,0.25);
}
.btn-premium:hover {
    opacity: .85;
}

.table-premium thead th {
    background: linear-gradient(90deg,#009ffd,#42baff);
    color: white;
    border: none;
    text-align: center;
    font-weight: 700;
    padding: 12px;
}

.table-premium tbody td {
    background: white;
    vertical-align: middle;
}

.search-product {
    border-radius: 12px;
    padding: 10px;
    border: 1px solid rgba(0,0,0,0.25);
}

.total-box {
    font-size: 1.7rem;
    padding: 18px;
    background: rgba(255,255,255,0.25);
    border-radius: 12px;
    font-weight: 800;
    text-align: center;
    color: #073a67;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
}

.remove-btn {
    background: #dc3545;
    padding: 6px 10px;
    border-radius: 8px;
    color: white;
}

.remove-btn:hover {
    opacity: 0.75;
}
</style>

<div class="container">

    <h1 class="page-title mb-4"> Nueva Venta</h1>

    <div class="wrapper-glass mb-4">
        <h4>Buscar producto</h4>
        <input 
            type="text" 
            id="searchInput" 
            class="form-control search-product"
            placeholder="Buscar por nombre o código..."
        >
    </div>

    <div class="wrapper-glass mb-4">
        <h4>Productos</h4>

        <div class="table-responsive">
            <table class="table table-premium table-hover" id="productsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Agregar</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($productos as $p): ?>
                    <tr>
                        <td><?= $p["id"] ?></td>
                        <td><?= $p["codigo"] ?></td>
                        <td><?= $p["descripcion"] ?></td>
                        <td>$<?= number_format($p["precioVenta"],2,",",".") ?></td>
                        <td><?= $p["existencia"] ?></td>
                        <td>
                            <button 
                                class="btn-premium btn-sm"
                                onclick='addToSale(<?= json_encode($p) ?>)'
                            >+</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- CARRITO -->
    <div class="wrapper-glass mb-4">
        <h4>Carrito</h4>

        <div class="table-responsive">
            <table class="table table-premium table-hover mb-0" id="cartTable">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Quitar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="mt-4 text-center">
            <div class="total-box">
                Total: $<span id="total">0.00</span>
            </div>
        </div>

        <form action="terminarVenta.php" method="POST" id="saleForm" class="mt-4 text-center">
            <input type="hidden" name="carrito" id="carritoInput">
            <button class="btn-premium w-50" type="submit">Confirmar Venta</button>
        </form>
    </div>

</div>

<script>
let cart = [];

// Agregar al carrito
function addToSale(product) {
    const exist = cart.find(p => p.id == product.id);

    if (exist) {
        if (exist.cantidad < product.existencia) {
            exist.cantidad++;
        } else {
            alert("No hay más stock disponible.");
        }
    } else {
        cart.push({
            id: product.id,
            codigo: product.codigo,
            descripcion: product.descripcion,
            precio: parseFloat(product.precioVenta),
            cantidad: 1
        });
    }

    renderCart();
}

// Quitar del carrito
function removeItem(id) {
    cart = cart.filter(p => p.id != id);
    renderCart();
}

// Renderizar carrito
function renderCart() {
    const tbody = document.querySelector("#cartTable tbody");
    tbody.innerHTML = "";

    let total = 0;

    cart.forEach(p => {
        const subtotal = p.precio * p.cantidad;
        total += subtotal;

        tbody.innerHTML += `
            <tr>
                <td>${p.codigo}</td>
                <td>${p.descripcion}</td>
                <td>${p.cantidad}</td>
                <td>$${subtotal.toFixed(2)}</td>
                <td>
                    <button class="remove-btn" onclick="removeItem(${p.id})">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    document.getElementById("total").textContent = total.toFixed(2);

    // Enviar carrito al backend
    document.getElementById("carritoInput").value = JSON.stringify(cart);
}

// Buscador en vivo
document.getElementById("searchInput").addEventListener("input", function () {
    const q = this.value.toLowerCase().trim();

    document.querySelectorAll("#productsTable tbody tr").forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? "" : "none";
    });
});
</script>

<?php include_once "pie.php"; ?>
