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
    
      Validación básica
      // Si value es diferente a un valor númerico o igual a 0 entonces la función devuelve 0
      if (typeof value !== 'number' || value === 0) {
        return 0;
      }
    
      // Obtenemos el signo si es + o - 
      const sign = Math.sign(value);
    
      // Obtenemos el valor absoluto 
      let abs = Math.abs(value);
    
      // Calculamos el orden de magnitud y como tenemos el valor absoluto no nos preocupamos por el negativo en estos momentos
      // Ej:
      // 0.5   → exp = -1
      // 5     → exp = 0
      // 50    → exp = 1
      const exp = Math.floor(Math.log10(abs));
    
      // En base a 10 calculomos el "step" que realizara la función 
      // Ej:
      // abs = 0.5  → step = 0.1
      // abs = 5    → step = 1
      // abs = 50   → step = 10
      const step = Math.pow(10, exp);
    
      // "Normalizamos" el valor al siguiente bucket válido
      // Para generar una conversión en los valores « badly formatted »
      // Ej:
      // 12     → 20
      // 0.76   → 0.8
      // 1568   → 2000
      const normalized = Math.ceil(abs / step) * step;
    
      // Si el valor NO era canónico,
      // la normalización ya cuenta como el incremento/decremento
      if (normalized !== abs) {
        return sign * normalized; // Aquí es donde nos sirve la variable "sign" que contiene el signo
      }
    
      // Si el valor ya es canónico,
      // ahora sí aplicamos inc o dec real
      if (direction === 'inc') {
        return sign * (abs + step);
      }
    
      if (direction === 'dec') {
        const result = abs - step;
    
        // Nunca devolvemos valores <= 0
        return result <= 0 ? 0 : sign * result;
      }
    
      // Cuando <direction> es un valor invalido devolvemos 0
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










