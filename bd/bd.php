<?php
require_once("../config.php");

/**
 * Clase base para la gestión de la base de datos.
 * 
 * Esta clase proporciona métodos protegidos para realizar consultas y
 * ejecuciones en la base de datos utilizando PDO.
 * 
 * @package RestAPI\BD
 */
class BD
{
    /** @var PDO|null Instancia de la conexión PDO. */
    private $con = null;

    /** @var string Mensaje de error producido en las operaciones. */
    private $error = '';

    /**
     * Constructor de la clase BD.
     * 
     * Inicializa la conexión PDO con los parámetros definidos en config.php.
     */
    function __construct()
    {
        $this->error = '';
        try {
            // Creamos la conexión.
            $this->con = new PDO(
                'mysql:host=' . SERVIDOR .
                    ';dbname=' . BASEDATOS .
                    ';charset=utf8',
                USUARIO,
                CONTRASENA
            );
            // Si se logra crear la conexión.
            if ($this->con) {
                // Ponemos los atributos para gestionar los errores con excepciones.
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // El juego de caracteres será utf-8
                $this->con->exec('SET CHARACTER SET utf8');
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
     * Destructor de la clase BD.
     * 
     * Cierra la conexión PDO.
     */
    function __destruct()
    {
        // Cerramos la conexión a la BBDD.
        $this->con = null;
    }

    /**
     * Realiza una consulta SELECT a la base de datos.
     * 
     * @param string $query La consulta SQL a ejecutar.
     * @return array|null Un array de objetos con los resultados o null si no hay resultados.
     */
    protected function _consultar($query)
    {
        $this->error = '';
        $filas = null;
        try {
            // Preparamos la consulta...
            $stmt = $this->con->prepare($query);
            // y la ejecutamos.
            $stmt->execute();
            // Si nos devuelve alguna fila...
            if ($stmt->rowCount() > 0) {
                // Creamos el array...
                $filas = array();
                // y lo rellenamos con los datos de la consulta.
                while ($registro = $stmt->fetchObject())
                    $filas[] = $registro;
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
        // Devolvemos las filas obtenidas de la consulta.
        return $filas;
    }

    /**
     * Ejecuta una sentencia SQL (INSERT, UPDATE, DELETE).
     * 
     * @param string $query La sentencia SQL a ejecutar.
     * @return int El número de filas afectadas o -1 en caso de error.
     */
    protected function _ejecutar($query)
    {
        $this->error = '';
        $filas = 0;
        try {
            // Ejecutamos la sentencia y guardamos el número de filas afectadas.
            $filas = $this->con->exec($query);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            $filas = -1;
        }
        // Devolvemos el número de filas afectadas.
        return $filas;
    }

    /**
     * Obtiene el ID del último registro insertado.
     * 
     * @return string|false El ID del último registro insertado o false en caso de error.
     */
    protected function _ultimoId()
    {
        // Devolvemos el id de la última fila insertada.
        return $this->con->lastInsertId();
    }

    /**
     * Obtiene el último mensaje de error.
     * 
     * @return string El mensaje de error.
     */
    public function GetError()
    {
        // Obtenemos el mensaje del error, si este se produce.
        return $this->error;
    }

    /**
     * Indica si se ha producido un error.
     * 
     * @return bool True si hay un error, false en caso contrario.
     */
    public function Error()
    {
        // Indicamos si ha habido algún error.
        return ($this->error != '');
    }
}
