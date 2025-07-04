<?php


class Personajes
{

    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }


    public function insertar($nombre, $color, $tipo, $nivel, $foto)
    {

        $sql = "insert into personajes (nombre, color, tipo, nivel, foto) VALUES (?,?,?,?,?);";

        $estado = $this->conexion->prepare($sql);

        $estado->bind_param("sssis", $nombre, $color, $tipo, $nivel, $foto);

        $resultado = $estado->execute();

        if (!$resultado) {
            die('Error en la consulta: ' . $estado->error);
        }


        return $resultado;
    }

    public function editar($nombre, $color, $tipo, $nivel, $foto, $id)
    {


        $sql = "Update personajes set nombre = ?, color = ?, tipo = ? , nivel =?, foto = ? where id = ?;";

        $estado = $this->conexion->prepare($sql);

        $estado->bind_param("sssisi", $nombre, $color, $tipo, $nivel, $foto, $id);

        $resultado = $estado->execute();

        if (!$resultado) {
            die('Error en la consulta: ' . $estado->error);
        }


        return $resultado;
    }

    public function obtenerPorId($id)
    {
        $sql = "select * from personajes WHERE id = ?;";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if (!$resultado) {
            die('Error al obtener personaje: ' . $this->conexion->error);
        }

        return $resultado->fetch_assoc(); // Devuelve un solo personaje como array asociativo
    }




    public function eliminar($id)
    {
        $sql = "Delete From personajes where id = ?;";

        $estado = $this->conexion->prepare($sql);

        $estado->bind_param("i", $id);

        $resultado = $estado->execute();

        if (!$resultado) {
            die('Error en la consulta: ' . $estado->error);
        }

        return $resultado;
    }
    public function listar()
    {

        $sql = "Select * From personajes;";

        $estado = $this->conexion->query($sql);



        if (!$estado) {
            die('Error al listar personajes: ' . $this->conexion->error);
        }



        return $resultado = $estado->fetch_all(MYSQLI_ASSOC);
    }
}
