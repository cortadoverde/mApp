<?php

/**
 * Clase NavegadorDelDesierto para manejar la navegación en un desierto
 * basado en un mapa de navegación y un conjunto de instrucciones.
 */
class NavegadorDelDesierto
{
    // Mapa de navegación donde la clave es el nombre del elemento y el valor son las opciones izquierda y derecha.
    private $mapaDeNavegacion = [];
    // Instrucciones de navegación, representadas como un array de caracteres ('L' o 'R').
    private $instruccionesDeNavegacion = [];

    // Input actual
    private $contenidoPuzzle;

    /**
     * Carga los datos de navegación desde una cadena de texto.
     *
     * @param string $datosDeNavegacion Cadena que contiene las instrucciones y el mapa de navegación.
     */
    public function cargarDatosDeNavegacion($rutaArchivo)
    {   
        // Leer el archivo 
        $datosDeNavegacion = file_get_contents($rutaArchivo);

        $this->contenidoPuzzle = $datosDeNavegacion;
        // Dividir los datos de navegación en líneas.
        $lineas = explode("\n", trim($datosDeNavegacion));

        // Obtener las instrucciones de navegación (la primera línea).
        $this->instruccionesDeNavegacion = str_split(trim($lineas[0]));

        // Configurar el mapa de navegación desde la tercera línea en adelante.
        for ($i = 2; $i < count($lineas); $i++) {
            // Dividir la línea en partes usando delimitadores.
            $linea = $lineas[$i];
            $partes = preg_split('/[\s=(),]+/', $linea, -1, PREG_SPLIT_NO_EMPTY);

            // Asegurarse de que la línea se divide en 3 partes (ubicación, opción izquierda y opción derecha).
            if (count($partes) === 3) {
                // Añadir la entrada al mapa de navegación.
                $this->mapaDeNavegacion[$partes[0]] = [$partes[1], $partes[2]];
            }
        }
    }

    /**
     * Calcula el número de pasos necesarios para llegar al destino 'ZZZ'.
     *
     * @return int Número de pasos necesarios para llegar al destino.
     */
    public function parte1()
    {
        // Empezar en el elemento 'AAA'.
        $elementoActual = "AAA";
        $pasos = 0;
        $turnoActual = 0;
        $cantidadDeInstrucciones = count($this->instruccionesDeNavegacion);

        // Ejecutar la navegación hasta llegar al destino 'ZZZ'.
        while ($elementoActual !== "ZZZ") {
            // Obtener las opciones posibles para el siguiente elemento.
            if (!isset($this->mapaDeNavegacion[$elementoActual])) {
                throw new Exception("Elemento no encontrado en el mapa: $elementoActual");
            }

            $siguientesElementos = $this->mapaDeNavegacion[$elementoActual];
            $caracterDeTurno = $this->instruccionesDeNavegacion[$turnoActual];

            // Determinar la dirección a tomar según la instrucción.
            $elementoActual = ($caracterDeTurno === 'L') ? $siguientesElementos[0] : $siguientesElementos[1];

            // Pasar a la siguiente instrucción, con un ciclo si es necesario.
            $turnoActual = ($turnoActual + 1) % $cantidadDeInstrucciones;

            // Incrementar el contador de pasos.
            $pasos++;
        }

        return $pasos;
    }

    /**
     * Calcula el número de pasos necesarios para encontrar el Mínimo Común Múltiplo (MCM)
     * de las longitudes de caminos desde todos los elementos que terminan en 'A'.
     *
     * @return int Mínimo Común Múltiplo (MCM) de las longitudes de caminos.
     */
    public function parte2()
    {
        // Filtrar los elementos que terminan en 'A'.
        $elementosActuales = array_filter(array_keys($this->mapaDeNavegacion), function ($clave) {
            return substr($clave, -1) === 'A';
        });

        // Arreglo para almacenar las longitudes de los caminos.
        $longitudesDeCaminos = [];

        // Recorrer cada elemento que termina en 'A' y calcular su longitud de camino.
        foreach ($elementosActuales as $inicioActual) {
            $elementoActual = $inicioActual;
            $pasos = 0;
            $turnoActual = 0;
            $cantidadDeInstrucciones = count($this->instruccionesDeNavegacion);

            while (substr($elementoActual, -1) !== 'Z') {
                // Obtener las opciones posibles para el siguiente elemento.
                if (!isset($this->mapaDeNavegacion[$elementoActual])) {
                    throw new Exception("Elemento no encontrado en el mapa: $elementoActual");
                }

                $siguientesElementos = $this->mapaDeNavegacion[$elementoActual];
                $caracterDeTurno = $this->instruccionesDeNavegacion[$turnoActual];
                $elementoActual = ($caracterDeTurno === 'L') ? $siguientesElementos[0] : $siguientesElementos[1];

                // Pasar a la siguiente instrucción, con un ciclo si es necesario.
                $turnoActual = ($turnoActual + 1) % $cantidadDeInstrucciones;

                // Incrementar el contador de pasos.
                $pasos++;
            }

            // Almacenar la longitud del camino encontrado.
            $longitudesDeCaminos[] = $pasos;
        }

        // Calcular el Mínimo Común Múltiplo (MCM) de todas las longitudes de caminos.
        $mcmDeCaminos = 1;
        foreach ($longitudesDeCaminos as $longitud) {
            $mcmDeCaminos = $this->calcularMcm($mcmDeCaminos, $longitud);
        }

        return $mcmDeCaminos;

    }

    public function verInput()
    {
        return $this->contenidoPuzzle . "\n\n";
    }

    public function generarDiagramaMermaid() {
        $resultado = "graph TD\n";
        
        foreach ($this->mapaDeNavegacion as $origen => [$izquierda, $derecha]) {
            $resultado .= "    $origen -->|L| $izquierda\n";
            $resultado .= "    $origen -->|R| $derecha\n";
        }

        return $resultado;
    }

    /**
     * Calcula el Mínimo Común Múltiplo (MCM) de dos números.
     *
     * @param int $x Primer número.
     * @param int $y Segundo número.
     * @return int Mínimo Común Múltiplo (MCM) de los dos números.
     */
    private function calcularMcm($x, $y)
    {
        return ($x * $y) / $this->calcularMcd($x, $y);
    }

    /**
     * Calcula el Máximo Común Divisor (MCD) de dos números usando el algoritmo de Euclides.
     *
     * @param int $x Primer número.
     * @param int $y Segundo número.
     * @return int Máximo Común Divisor (MCD) de los dos números.
     */
    private function calcularMcd($x, $y)
    {
        // Algoritmo de Euclides para calcular el MCD.
        while ($y !== 0) {
            $temp = $x % $y;
            $x = $y;
            $y = $temp;
        }

        return $x;
    }
}

/**
 * Función principal para leer el archivo de entrada y ejecutar las partes del problema.
 *
 * @param string $rutaArchivo Ruta al archivo de entrada.
 */
function ejecutarNavegadorDelDesierto($rutaArchivo)
{
    // Leer el contenido del archivo de entrada.
    $datosDeNavegacion = file_get_contents($rutaArchivo);

    // Crear una instancia de NavegadorDelDesierto y cargar los datos de navegación.
    $navegador = new NavegadorDelDesierto();
    $navegador->cargarDatosDeNavegacion($datosDeNavegacion);

    // Ejecutar y mostrar los resultados para ambas partes del problema.
    echo "Parte 1: " . $navegador->parte1() . "\n";
    echo "Parte 2: " . $navegador->parte2() . "\n";
}

// Establecer el tiempo máximo de ejecución en el script PHP (opcional).
ini_set('max_execution_time', 300);

echo '<pre>';
// instanciar el navegador de mapa 3000
$navegador = new NavegadorDelDesierto();
$navegador->cargarDatosDeNavegacion("input.txt");
// mostrar los resultados
echo "Parte 1: " . $navegador->parte1() . "\n";
echo "Parte 2: " . $navegador->parte2() . "\n";

// input de ingreso

echo $navegador->generarDiagramaMermaid();


echo '</pre>';
?>
