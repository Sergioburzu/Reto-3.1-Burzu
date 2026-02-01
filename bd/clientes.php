<?php
require_once("bd.php");

/**
 * Clase para la gestión de la tabla de clientes.
 * 
 * Esta clase extiende de BD y proporciona métodos específicos para
 * interactuar con la tabla 'clientes'.
 * 
 * @package RestAPI\BD
 */
class ClientesBD extends BD
{
    /** @var int ID del cliente. */
    public $id;

    /** @var string Nombre del cliente. */
    public $nombre;

    /** @var string Email del cliente. */
    public $email;

    /** @var array|null Soportará las filas devueltas por una consulta. */
    public $filas;

    /**
     * Inserta un nuevo cliente en la base de datos.
     * 
     * @return int Número de filas afectadas o -1 en caso de error.
     */
    public function Insertar()
    {
        $sql = "INSERT INTO clientes VALUES" .
            " (default, '$this->nombre', '$this->email')";
        return $this->_ejecutar($sql);
    }

    /**
     * Modifica los datos de un cliente existente.
     * 
     * @return int Número de filas afectadas o -1 en caso de error.
     */
    public function Modificar()
    {
        $sql = "UPDATE clientes SET" .
            " nombre='$this->nombre', email='$this->email'" .
            " WHERE id=$this->id";
        return $this->_ejecutar($sql);
    }

    /**
     * Borra un cliente de la base de datos.
     * 
     * @return int Número de filas afectadas o -1 en caso de error.
     */
    public function Borrar()
    {
        $sql = "DELETE FROM clientes WHERE id=$this->id";
        return $this->_ejecutar($sql);
    }

    /**
     * Selecciona uno o todos los clientes de la base de datos.
     * 
     * Si la propiedad $id es distinta de 0, selecciona solo ese cliente.
     * 
     * @return bool True si se encontraron registros, false en caso contrario.
     */
    public function Seleccionar()
    {
        $sql = 'SELECT * FROM clientes';
        // Si me han pasado un id, obtenemos solo el registro indicado.
        if ($this->id != 0)
            $sql .= " WHERE id=$this->id";
        $this->filas = $this->_consultar($sql);
        if ($this->filas == null)
            return false;
        if ($this->id != 0) {
            // Guardamos los campos en las propiedades.
            $this->nombre = $this->filas[0]->nombre;
            $this->email = $this->filas[0]->email;
        }
        return true;
    }
}
