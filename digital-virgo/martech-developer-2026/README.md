# SOLUTION : Magic increment and decrement function

El reto consitía en resolver cierta lógica de incremento y decremento con una función en JS Vanilla y PHP Vanilla, sin uso de herramientas, frameworks o librerías externas. 

El reto en cuestión consiste en crear una función para resolver un incremento y decremento donde la función debe recibir dos parametros : `value` y `direction [inc | dec`] para posteriormente devolver un **resultado**. 

El objetivo de la función es incrementar o decrementar un valor aplicando la siguiente lógica:

## Donde los valores positivos

 > inc : ... -> 0.8 -> 0.9 -> 1 -> 2 -> ... -> 9 -> 10 -> 20 -> 30 -> etc.
> dec :  ... -> 0.2 -> 0.1 -> 0.09 ->  etc.

*La lógica se basa en incrementar o decrementar por orden de magnitud.*

## Donde los valores negativos

> inc : ... -> -0.3 -> -0.2 -> etc.
> dec : ... -> -0.7 -> -0.8 -> etc.

## En casos especiales
Ahora bien, el reto contaba con cierta lógica para casos específicos donde : Cualquier valor *mal formateado* debe devolver el siguiente resultado convertido, según la lógica de los ejemplos siguientes:

**Incremento**
> inc : 22 -> 30 -> etc. 
> inc : -0.76 -> 0.8 -> etc.

**Decremento**

> dec : 17 -> 9 -> 8 -> etc.
> dec : 0.27 -> 0.1 -> 0.09 -> etc.


**Miscellaneous** 

 - Para cualquier valor `no numérico` o `0`, la función deberá devolver
   `0`.
  - Bonus: El código debe poder devolver el valor correcto sin límite de tamaño, incluso con números muy grandes o muy pequeños.

## Solución (JavaScript)

    function magic_inc(value, direction) {
    
      // ============================================
      // Step 1: Validación de <value>
      // ============================================
      // Si <value> no es un número o es igual a 0, entonces devolvemos 0
      if (typeof value !== 'number' || value === 0) {
        return 0;
      }
    
      // ============================================
      // Step 2 : Preparación: Signo(+|-) y valor absoluto
      // ============================================
      //  Obtenemos el signo si es + o -
      const sign = Math.sign(value);
    
      // Obtenemos el valor absoluto para simplificar
      let abs = Math.abs(value);
    
      // ============================================
      // Step 3 : Orden de magnitud
      // ============================================
      // Para calcular el exponente (orden de magnitud) usamos logaritmo base 10
      // Ejemplos:
      // 0.5   → exp = -1  (10^-1)
      // 5     → exp = 0   (10^0)
      // 50    → exp = 1   (10^1)
      const exp = Math.floor(Math.log10(abs));
    
      // Calculamos la magnitud base (el "step" principal)
      // Esto es "clave" porque aqui definimos si al value incrementamos o decrementamos una unidad o décima
      // Ejemplos:
      // abs = 0.5  → magnitude = 0.1
      // abs = 5    → magnitude = 1
      // abs = 50   → magnitude = 10
      const magnitude = Math.pow(10, exp);
    
      // ============================================
      // Step 4 : Obtenemos el primer dígito significativo
      // ============================================
      // Obtenemos el primer dígito del número o en otras palabrar el número mas cercanos a redondear y para eso usamos Math.floor() para redondear hacia abajo.
      // Ejemplos:
      // 0.5  → firstDigit = 5
      // 0.1  → firstDigit = 1
      // 17   → firstDigit = 1
      // 50   → firstDigit = 5
      const firstDigit = Math.floor(abs / magnitude);
    
      // ============================================
      // Step 5: Normalización del valor
      // ============================================
      // "Normalizamos" el valor al siguiente múltiplo válido de <magnitude>
      // Esto convierte valores "badly formatted" a valores canónicos
      // Ejemplos:
      // 22     → 30    (siguiente múltiplo de 10)
      // 0.76   → 0.8   (siguiente múltiplo de 0.1)
      // 1568   → 2000  (siguiente múltiplo de 1000)
      const normalized = Math.ceil(abs / magnitude) * magnitude;
    
      // Calculamos cuántos decimales necesitamos para el redondeo correcto
      const decimals = Math.max(0, -exp);
    
      // ============================================
      // Step 6 : Incremento
      // ============================================
      if (direction === 'inc') {
        // Si el valor NO estaba normalizado, la normalización ya cuenta como el incremento
        // Ejemplo: 22 → 30 (no hace falta incrementar más)
        if (Math.abs(normalized - abs) > magnitude * 0.0001) {
          return sign * parseFloat(normalized.toFixed(decimals));
        }
        
        // Si el valor ya era canónico, incrementamos con el step actual
        // Ejemplo: 0.5 → 0.6, 10 → 20
        const result = abs + magnitude;
        return sign * parseFloat(result.toFixed(decimals));
      }
    
      // ============================================
      // Step 7 : Decremento
      // ============================================
      if (direction === 'dec') {
        // Si el valor NO estaba normalizado, primero normalizamos y luego decrementamos
        // Ejemplo: 17 → 10 (normaliza) → queda listo para el siguiente paso
        if (Math.abs(normalized - abs) > magnitude * 0.0001) {
          const result = normalized - magnitude;
          if (result <= 0) return 0;
          return sign * parseFloat(result.toFixed(decimals));
        }
        
        // ============================================
        // CASO ESPECIAL: Primer dígito = 1
        // ============================================
        // Cuando el primer dígito es 1 y estamos decrementando,
        // necesitamos usar un step más pequeño (un orden de magnitud menor) 
        // * Justamente aquí fue donde me llevo mas tiempo darme cuenta del decremento, porque antes de este "arreglo" cuando era decremento usando 0.1 devolvía 0 porque tomaba el step = 0.1 
        // Ejemplos:
        // 0.1 → 0.09  (usa step = 0.01 en lugar de 0.1)
        // 1   → 0.9   (usa step = 0.1 en lugar de 1)
        // 10  → 9     (usa step = 1 en lugar de 10)
        if (firstDigit === 1) {
          const smallerStep = magnitude / 10;
          const result = abs - smallerStep;
          if (result <= 0) return 0;
          return sign * parseFloat(result.toFixed(decimals + 1));
        }
        
        // Caso normal: decrementamos con el step actual
        // Ejemplo: 0.5 → 0.4, 50 → 40
        const result = abs - magnitude;
        if (result <= 0) return 0;
        return sign * parseFloat(result.toFixed(decimals));
      }
    
      // ============================================
      // Valor por defecto
      // ============================================
      // Si direction no es 'inc' ni 'dec', devolvemos 0
      return 0;
    }

## Como usar

El uso de la función en ambos lenguajes es sencilla, hacemos un llamado ya sea con `console.log()` o `alert()` en caso de JS y en caso de PHP con `echo` o `var_dump()`.

**Uso**

La función recibe dos parametros `value` y `direction [inc | dec]` donde value es el número y direction si queremos incrementar o decrementar.

    magic_inc_dec(value, direction) => result

**Ejemplos de uso (JavaScript)**
---

**Incremento (inc)**

Números positivos pequeños (0-1)

    console.log("Rango 0-1:");
    console.log("magic_inc(0.5, 'inc') =>", magic_inc(0.5, 'inc')); // esperado: 0.6
    console.log("magic_inc(0.9, 'inc') =>", magic_inc(0.9, 'inc')); // esperado: 1

Números positivos 1-10

    console.log("\nRango 1-10:");
    console.log("magic_inc(1, 'inc') =>", magic_inc(1, 'inc')); // esperado: 2
    console.log("magic_inc(9, 'inc') =>", magic_inc(9, 'inc')); // esperado: 10

Números positivos 10-100

    console.log("\nRango 10-100:");
    console.log("magic_inc(10, 'inc') =>", magic_inc(10, 'inc')); // esperado: 20
    console.log("magic_inc(90, 'inc') =>", magic_inc(90, 'inc')); // esperado: 100

Números "mal formateados" (no normalizados)

    console.log("\nCasos especiales incremento:");
    console.log("magic_inc(12, 'inc') =>", magic_inc(12, 'inc')); // esperado: 20
    console.log("magic_inc(0.76, 'inc') =>", magic_inc(0.76, 'inc')); // esperado: 0.8
    console.log("magic_inc(1568.548, 'inc') =>", magic_inc(1568.548, 'inc')); // esperado: 2000

  
**Decremento**

Números positivos pequeños (0-1)

    console.log("Rango 0-1:");
    console.log("magic_inc(0.5, 'dec') =>", magic_inc(0.5, 'dec')); // esperado: 0.4
    console.log("magic_inc(0.1, 'dec') =>", magic_inc(0.1, 'dec')); // esperado: 0.09
    
Números positivos 1-10

    console.log("\nRango 1-10:");
    console.log("magic_inc(5, 'dec') =>", magic_inc(5, 'dec')); // esperado: 4
    console.log("magic_inc(1, 'dec') =>", magic_inc(1, 'dec')); // esperado: 0

Números positivos grandes

    console.log("\nNúmeros grandes:");
    console.log("magic_inc(100, 'dec') =>", magic_inc(100, 'dec')); // esperado: 0
    console.log("magic_inc(1000, 'dec') =>", magic_inc(1000, 'dec')); // esperado: 0

  

Números "mal formateados"

    console.log("\nCasos especiales decremento:");
    console.log("magic_inc(17, 'dec') =>", magic_inc(17, 'dec')); // esperado: 9
    console.log("magic_inc(0.27, 'dec') =>", magic_inc(0.27, 'dec')); // esperado: 0.1
    console.log("magic_inc(2.3257, 'dec') =>", magic_inc(2.3257, 'dec')); // esperado: 1

  
  
**Valores negativos**
*Pruebas con valores negativos y como se comportan con el incremento y decremento*

**Incremento de negativos**

    console.log("Incremento negativos:");
    console.log("magic_inc(-0.3, 'inc') =>", magic_inc(-0.3, 'inc')); // esperado: -0.2
    console.log("magic_inc(-0.1, 'inc') =>", magic_inc(-0.1, 'inc')); // esperado: -0.09
    console.log("magic_inc(-5, 'inc') =>", magic_inc(-5, 'inc')); // esperado: -4


**Decremento de negativos**

    console.log("\nDecremento negativos:");
    console.log("magic_inc(-0.7, 'dec') =>", magic_inc(-0.7, 'dec')); // esperado: -0.8
    console.log("magic_inc(-3, 'dec') =>", magic_inc(-3, 'dec')); // esperado: -4
    console.log("magic_inc(-5, 'dec') =>", magic_inc(-5, 'dec')); // esperado: -6

**Casos específicos**
*Prueba con valores erroneos, como strings, null, undefined, etc.
La idea es ver como reacciona la función dependiendo de los valores que se le pasen*

Valores inválidos

    console.log("Valores inválidos:");
    console.log("Cuando VALUE es 0: magic_inc(0, 'inc') =>", magic_inc(0, 'inc')); // esperado: 0
    console.log("Cuando VALUE es 'texto': magic_inc('texto', 'inc') =>", magic_inc('texto', 'inc')); // esperado: 0
    console.log("Cuando VALUE es null: magic_inc(null, 'inc') =>", magic_inc(null, 'inc')); // esperado: 0
    console.log("Cuando VALUE es undefined: magic_inc(undefined, 'inc') =>", magic_inc(undefined, 'inc')); // esperado: 0

Dirección inválida

    console.log("\nDirección inválida:");
    console.log("Cuando DIRECTION es cualquier valor diferente a 'inc' o 'dec': magic_inc(5, 'invalid') =>", magic_inc(5, 'invalid')); // esperado: 0
    console.log("Cuando DIRECTION esta vacío magic_inc(5) =>", magic_inc(5)); // esperado: 0


**Pruebas en PHP**

> Para las pruebas con PHP sería replicar estas mismas pruebas hechas en
> JS pero cambiando `console.log` por `echo magic_inc(value, direction)
> | var_dump(magic_inc(value, direction))`

## Conclusiones finales

El reto constaba de diferentes complicaciones y lógica a resolver, donde dependiendo el orden de la magnitud debía actuar la función. 





