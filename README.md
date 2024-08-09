# mApp
https://adventofcode.com/2023/day/8 implementacion de solucion
# Diagrama de Navegación


```mermaid

graph TD
    A1[Inicio] --> B1[Cargar Datos de Navegación]
    B1 --> C1[Obtener Instrucciones]
    C1 --> D1[Construir Mapa de Nodos]
    D1 --> E1[Inicializar Nodo Actual = AAA]
    E1 --> F1[Inicializar Pasos = 0]
    F1 --> G1[Inicializar Índice Instrucción = 0]
    G1 --> H1{Nodo Actual ≠ ZZZ}
    H1 -->|Sí| I1[Obtener Siguiente Nodo]
    I1 --> J1{Instrucción = L?}
    J1 -->|Sí| K1[Nodo Actual = Siguiente Nodo Izquierda]
    J1 -->|No| L1[Nodo Actual = Siguiente Nodo Derecha]
    K1 --> M1[Actualizar Índice Instrucción]
    L1 --> M1
    M1 --> N1[Incrementar Pasos]
    N1 --> O1{Índice Instrucción >= Longitud Instrucciones?}
    O1 -->|Sí| P1[Reiniciar Índice Instrucción]
    O1 -->|No| Q1[Continuar]
    P1 --> Q1
    Q1 --> H1
    H1 -->|No| R1[Fin Parte 1]
    
    R1 --> A2[Inicio Parte 2]
    A2 --> B2[Cargar Datos de Navegación]
    B2 --> C2[Obtener Instrucciones]
    C2 --> D2[Construir Mapa de Nodos]
    D2 --> E2[Obtener Nodos Iniciales que Terminan en 'A']
    E2 --> F2[Inicializar Longitudes de Caminos]
    E2 --> G2[Inicializar Pasos = 0]
    G2 --> H2[Inicializar Índice Instrucción = 0]
    H2 --> I2[Para Cada Nodo Inicial]
    I2 --> J2[Inicializar Nodo Actual = Nodo Inicial]
    J2 --> K2{Nodo Actual Termina en 'Z'?}
    K2 -->|Sí| L2[Agregar Pasos a Longitudes de Caminos]
    K2 -->|No| M2[Obtener Siguiente Nodo]
    M2 --> N2{Instrucción = L?}
    N2 -->|Sí| O2[Nodo Actual = Siguiente Nodo Izquierda]
    N2 -->|No| P2[Nodo Actual = Siguiente Nodo Derecha]
    O2 --> Q2[Actualizar Índice Instrucción]
    P2 --> Q2
    Q2 --> R2[Incrementar Pasos]
    R2 --> S2{Índice Instrucción >= Longitud Instrucciones?}
    S2 -->|Sí| T2[Reiniciar Índice Instrucción]
    S2 -->|No| U2[Continuar]
    T2 --> U2
    U2 --> K2
    L2 --> V2[Calcular MCM de Longitudes de Caminos]
    V2 --> W2[Fin Parte 2]

```

# Descripción del Problema
El problema se centra en la navegación a través de un conjunto de nodos interconectados, donde cada nodo tiene caminos hacia otros nodos y las instrucciones para moverse de un nodo a otro están dadas en forma de una secuencia de giros (izquierda o derecha).

En la Parte 1, el objetivo es calcular el número de pasos necesarios para llegar a un nodo de destino desde un nodo inicial, siguiendo un conjunto de instrucciones.

En la Parte 2, el problema se complica al considerar múltiples nodos iniciales y la necesidad de calcular el ciclo de tiempo para que todos los nodos iniciales terminen simultáneamente en nodos de destino, además de determinar el número mínimo de pasos necesarios para alcanzar dicha condición.

## Abordaje del Problema

### Análisis del Problema

- **Datos de Entrada**: Los datos están formateados como una secuencia de instrucciones seguida de una lista de nodos con sus respectivos destinos para giros a la izquierda y a la derecha.
- **Objetivos**:
    - Parte 1: Encontrar el número de pasos necesarios para llegar del nodo inicial al nodo de destino.
    - Parte 2: Encontrar el número de pasos necesarios para que todos los nodos iniciales terminen simultáneamente en nodos de destino.

### Implementación en PHP
Para abordar el problema, se desarrolló una solución en PHP que utiliza clases para representar los nodos y las instrucciones de navegación. El proceso se desglosa en los siguientes pasos:

1 - Definir la Estructura de Datos: Se crean las clases NavegadorDesierto para manejar los nodos y las instrucciones.

2 - Cargar Datos de Entrada: Se carga el mapa de nodos y las instrucciones desde una cadena de texto, procesando cada línea para construir un mapa de nodos con sus destinos.

3 - Calcular Pasos para la Parte 1: Se simula el movimiento a través de los nodos según las instrucciones para contar los pasos necesarios para alcanzar el destino.

4 - Calcular Ciclos para la Parte 2: Se inicia desde todos los nodos que terminan en 'A' y se sigue el mismo proceso hasta que todos los nodos iniciales terminen en nodos que terminan en 'Z'. Se calcula el mínimo común múltiplo (MCM) de los pasos necesarios para cada camino para determinar el tiempo de sincronización

<img src="data:image/png;base64,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">