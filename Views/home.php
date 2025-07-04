<?php
require_once('./Classes/db_config.php');
require_once('./Classes/Personajes.php');
require_once('./Classes/Pdf.php');


$pdf = new Pdf();
$conexion = new Conexion();
$conexion = $conexion->GetConexion();

$personaje = new Personajes($conexion);

// --- PROCESAR ACCIONES ---
$accion = $_GET['accion'] ?? 'listar';

//Editar
if ($accion === 'editar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $personaje->obtenerPorId($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'editar') {
        $nombre = $_POST['nombre'];
        $color = $_POST['color'];
        $tipo = $_POST['tipo'];
        $nivel = $_POST['nivel'];
        $foto = $_POST['foto'];

        $personaje->editar($nombre, $color, $tipo, $nivel, $foto, $id);
        header('Location: ?vista=home');
        exit();
    }
}

// Pdf
if ($accion === 'pdf' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $personaje->obtenerPorId($id);

    if ($data) {
        // Limpia cualquier salida previa para evitar errores de encabezado
        if (ob_get_length()) {
            ob_end_clean();
        }
        $pdf->CrearPDF($data['nombre'], $data['color'], $data['tipo'], $data['nivel'], $data['foto']);
        exit; // Muy importante terminar aquí
    } else {
        echo "Personaje no encontrado";
        exit;
    }
}

// Aquí continúa el resto del código que imprime el HTML...

//Crear

if (isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {
        var_dump($_POST);
        $nombre = $_POST['nombre'];
        $color = $_POST['color'];
        $tipo =  $_POST['tipo'];
        $nivel = $_POST['nivel'];
        $foto = $_POST['foto'];

        $personaje->insertar($nombre, $color, $tipo, $nivel, $foto);
    }
    header('Location: ?vista=home');

    exit();
}

//Eliminar

if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $personaje->eliminar($id);
    header('Location: ?vista=home');

    exit();
}



//Listar 
$personajes = $personaje->listar();




?>
<h2 class="text-secondary text-center">Listado de Personajes de Naruto Shippuden</h2>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">🧍‍♂️ Personajes Registrados</h2>
        <a href="?accion=anadir" class="btn btn-success">
            <img src="./Views/Components/add-file-8-svgrepo-com.svg" width="30" height="30" alt="add"> Añadir Obra
        </a>
    </div>

    <?php if ($accion === 'anadir' || $accion === 'editar'): ?>
        <?php $formClass = $accion === 'anadir' ? 'border border-success p-4' : 'border border-warning p-4'; ?>
        <form class="<?= $formClass ?>" method="post" action="?vista=home&accion=<?= $accion ?>&id=<?= $data['id'] ?? '' ?>">

            <input type="hidden" name="id" value="<?= $data['id'] ?? '' ?>">
            <input type="hidden" name="accion" value="<?= $accion === 'anadir' ? 'crear' : 'editar' ?>">



            <div class="mb-2">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= $data['nombre'] ?? '' ?>" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Color:</label>
                <input type="text" name="color" value="<?= $data['color'] ?? '' ?>" class="form-control" required>
            </div>


            <div class="mb-2">
                <label>Foto (URL):</label>
                <input type="text" name="foto" value="<?= $data['foto'] ?? '' ?>" class="form-control">
            </div>


            <div class="mb-2">
                <label>Tipo:</label>
                <select name="tipo" class="form-select" required>
                    <?php
                    $tipos = ['Humano', 'Ninja De Konoha', 'Ninja De Iwagakure', 'Ninja De Kirigakure', 'Ninja de Sunagakure', 'Ninja de Kumogakure'];
                    $tipoSeleccionado = $data['tipo'] ?? '';
                    foreach ($tipos as $tipo) {
                        $selected = ($tipoSeleccionado === $tipo) ? 'selected' : '';
                        // Sanitizamos el valor y texto antes de imprimir
                        $valor = htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8');
                        echo "<option value=\"$valor\" $selected>$valor</option>";
                    }
                    ?>
                </select>

            </div>
            <div class="mb-2">
                <label>Nivel:</label>
                <?php
                $niveles = [
                    0 => 'Sin rango',
                    1 => 'Genin',
                    2 => 'Chunnin',
                    3 => 'Jounin',
                    4 => 'Anbu',
                    5 => 'Kage'
                ];
                $nivelSeleccionado = $data['nivel'] ?? '';
                ?>

                <select name="nivel" class="form-select" required>
                    <?php foreach ($niveles as $valor => $texto): ?>
                        <?php $selected = ((int)$nivelSeleccionado === $valor) ? 'selected' : ''; ?>
                        <option value="<?= $valor ?>" <?= $selected ?>><?= $texto ?></option>
                    <?php endforeach; ?>
                </select>

            </div>

</div>



<button class="btn btn-primary"><?= $accion === 'anadir' ? 'Crear' : 'Actualizar' ?></button>

<a href="?vista=home" class="btn btn-secondary">Cancelar</a>
</form>
<?php else: ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Color</th>
                    <th>Tipo</th>
                    <th>Nivel</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personajes as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['color']) ?></td>
                        <td><?= htmlspecialchars($p['tipo']) ?></td>
                        <td><?= htmlspecialchars($p['nivel']) ?></td>
                        <td>
                            <?php if (!empty($p['foto'])): ?>
                                <img src="<?= htmlspecialchars($p['foto']) ?>" alt="Foto" width="300" height="250">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?vista=home&accion=editar&id=<?= urlencode($p['id']) ?>"
                                class="btn btn-sm btn-warning mb-1">Editar</a>
                            <a href="?vista=home&accion=eliminar&id=<?= urlencode($p['id']) ?>"
                                class="btn btn-sm btn-danger mb-1"
                                onclick="return confirm('¿Eliminara este personaje?')">Eliminar</a>



                            <a href="?vista=home&accion=pdf&id=<?= urlencode($p['id']) ?>"
                                class="btn btn-sm btn-info">PDF</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
                <?php if (count($personajes) === 0): ?>
                    <tr>
                        <td colspan="8" class="text-center">No hay personajes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


    </div>
<?php endif; ?>