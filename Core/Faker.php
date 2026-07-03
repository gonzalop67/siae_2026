<?php

namespace Core;

class Faker
{
    private static array $nombresMasculinos = [
        'Juan', 'Carlos', 'Luis', 'Pedro', 'Andrés', 'Santiago', 'José', 'Manuel', 'Diego', 'Mateo',
        'Francisco', 'Javier', 'David', 'Daniel', 'Alejandro', 'Fernando', 'Miguel', 'Ángel', 'Jorge', 'Christian',
        'Gabriel', 'Ricardo', 'Roberto', 'Wilson', 'Geovanny', 'Franklin', 'Pablo', 'Alex', 'Leonardo', 'Marco',
        'Esteban', 'Kevin', 'Bryan', 'Anthony', 'Josué', 'Jonathan', 'Edison', 'Hugo', 'Édgar', 'Rafael'
    ];

    private static array $nombresFemeninos = [
        'María', 'Ana', 'Luisa', 'Paula', 'Laura', 'Diana', 'Camila', 'Sofía', 'Elena', 'Valentina',
        'Andrea', 'Gabriela', 'Daniela', 'Natalia', 'Isabella', 'Lucía', 'Fernanda', 'Verónica', 'Patricia', 'Sandra',
        'Jessica', 'Katherine', 'Erika', 'Mónica', 'Carmen', 'Martha', 'Rosa', 'Blanca', 'Silvia', 'Lorena',
        'Elizabeth', 'Mayra', 'Estefanía', 'Adriana', 'Tatiana', 'Paola', 'Karla', 'Genesis', 'Nicole', 'Aracely'
    ];

    private static array $apellidos = [
        'Pérez', 'Gómez', 'Rodríguez', 'López', 'Martínez', 'Sánchez', 'García', 'Fernández', 'Torres', 'Ramírez',
        'Castillo', 'Castro', 'Chávez', 'Cueva', 'Espinoza', 'Flores', 'Guanoluisa', 'Guerrero', 'Gutiérrez', 'Herrera',
        'Jiménez', 'León', 'Maldonado', 'Mendoza', 'Morales', 'Moreno', 'Muñoz', 'Ochoa', 'Ortiz', 'Paredes',
        'Peña', 'Pinto', 'Quishpe', 'Ramos', 'Reyes', 'Ríos', 'Rivera', 'Romero', 'Salazar', 'Silva',
        'Suárez', 'Toapanta', 'Vaca', 'Vallejo', 'Vargas', 'Velasco', 'Velásquez', 'Vera', 'Villacís', 'Zambrano'
    ];

    private static array $calles = ['Av. Amazonas', 'Calle Larga', 'Av. 10 de Agosto', 'Calle de los Olivos', 'Av. de la República', 'Pasaje Central'];
    private static array $dominios = ['gmail.com', 'yahoo.com', 'outlook.com', 'example.com'];

    public static function genero(): string
    {
        return rand(0, 1) === 0 ? 'Masculino' : 'Femenino';
    }

    public static function nombre(?string $genero = null): string
    {
        if (!$genero) {
            $genero = self::genero();
        }

        $primer_nombre = ($genero === 'Masculino') 
            ? self::$nombresMasculinos[array_rand(self::$nombresMasculinos)] 
            : self::$nombresFemeninos[array_rand(self::$nombresFemeninos)];

        $segundo_nombre = ($genero === 'Masculino') 
            ? self::$nombresMasculinos[array_rand(self::$nombresMasculinos)] 
            : self::$nombresFemeninos[array_rand(self::$nombresFemeninos)];

        $apellido1 = self::$apellidos[array_rand(self::$apellidos)];
        $apellido2 = self::$apellidos[array_rand(self::$apellidos)];

        return "{$apellido1} {$apellido2} {$primer_nombre} {$segundo_nombre}";
    }

    public static function correo(string $nombre): string
    {
        $limpio = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $nombre));
        $limpio = preg_replace('/[^a-z0-9]/', '', str_replace(' ', '', $limpio));
        $dominio = self::$dominios[array_rand(self::$dominios)];
        
        return $limpio . rand(10, 99) . '@' . $dominio;
    }

    public static function contraseña(string $textoPlano = 'password123', bool $hashear = true): string
    {
        return $hashear ? password_hash($textoPlano, PASSWORD_BCRYPT) : $textoPlano;
    }

    public static function direccion(): string
    {
        $calle = self::$calles[array_rand(self::$calles)];
        $numero = rand(100, 9999);
        return "{$calle} N° {$numero}";
    }

    public static function fechaNacimiento(int $edadMinima = 18, int $edadMaxima = 65): string
    {
        $añoActual = (int)date('Y');
        $añoInicio = $añoActual - $edadMaxima;
        $añoFin = $añoActual - $edadMinima;

        $año = rand($añoInicio, $añoFin);
        $mes = str_pad((string)rand(1, 12), 2, '0', STR_PAD_LEFT);
        $dia = str_pad((string)rand(1, 28), 2, '0', STR_PAD_LEFT);

        return "{$año}-{$mes}-{$dia}";
    }

    /**
     * Genera un número de teléfono celular con formato estándar (ej: 0998765432)
     */
    public static function celular(): string
    {
        $prefijos = ['099', '098', '097', '096', '095', '093'];
        $base = $prefijos[array_rand($prefijos)];
        return $base . rand(1000000, 9999999);
    }

    /**
     * Genera un número de cédula de 10 dígitos aplicando el algoritmo del dígito verificador
     */
    public static function cedula(): string
    {
        // 1. Provincias válidas en Ecuador (01 a 24)
        $provincia = str_pad((string)rand(1, 24), 2, '0', STR_PAD_LEFT);
        
        // 2. Tercer dígito siempre menor a 6 para personas naturales
        $tercerDigito = (string)rand(0, 5);
        
        // 3. Los siguientes 6 dígitos aleatorios
        $cuerpo = '';
        for ($i = 0; $i < 6; $i++) {
            $cuerpo .= rand(0, 9);
        }

        $nueveDigitos = $provincia . $tercerDigito . $cuerpo;
        
        // 4. Algoritmo de Módulo 10 (Validación real de cédulas) - CORREGIDO REALMENTE
        $coeficientes = array(2, 1, 2, 1, 2, 1, 2, 1, 2);
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $valor = (int)$nueveDigitos[$i] * $coeficientes[$i];
            if ($valor >= 10) {
                $valor -= 9;
            }
            $suma += $valor;
        }

        $totalPrecedente = (int)ceil($suma / 10) * 10;
        $digitoVerificador = $totalPrecedente - $suma;
        if ($digitoVerificador === 10) {
            $digitoVerificador = 0;
        }

        return $nueveDigitos . $digitoVerificador;
    }

    /**
     * Genera un precio decimal aleatorio (ej: 19.99)
     */
    public static function precio(float $min = 1.00, float $max = 500.00): float
    {
        $factor = rand() / getrandmax();
        $precio = $min + $factor * ($max - $min);
        return round($precio, 2);
    }

    /**
     * Genera una cantidad de stock aleatoria
     */
    public static function stock(int $min = 0, int $max = 150): int
    {
        return rand($min, $max);
    }
}
