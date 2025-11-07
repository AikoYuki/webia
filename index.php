<?php
// Directorio actual
$directorio_actual = './';
$carpetas_info = [];

// Obtener la lista de archivos y directorios
$elementos = scandir($directorio_actual);

// 1. Filtrar, recopilar la fecha de modificación y almacenar en un array asociativo
foreach ($elementos as $elemento) {
    if (($elemento !== '.' && $elemento !== '..') && is_dir($directorio_actual . $elemento)) {
        // Obtenemos la marca de tiempo de la última modificación
        $tiempo_modificacion = filemtime($directorio_actual . $elemento);

        $carpetas_info[$elemento] = [
            'nombre' => $elemento,
            'modificacion' => $tiempo_modificacion,
            // Formato legible: Ej. 01/11/2025 21:15
            'fecha_formateada' => date("d/m/Y H:i", $tiempo_modificacion)
        ];
    }
}

// 2. 🚨 ¡CAMBIO CLAVE! Ordenar las carpetas por la fecha de última modificación, de MÁS RECIENTE a más antigua.
// uasort mantiene la asociación de claves. La función anónima compara las marcas de tiempo.
uasort($carpetas_info, function($a, $b) {
    // Orden descendente (el más grande/reciente primero)
    return $b['modificacion'] <=> $a['modificacion'];
});


$titulo = "🚀 Prácticos de Alumnos";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📚</text></svg>">
    
    <style>
        :root {
            --color-primario: #007bff;
            --color-secundario: #28a745;
            --color-fondo: #f8f9fa;
            --color-tarjeta: #ffffff;
            --color-sombra: rgba(0, 0, 0, 0.1);
            --color-texto: #333;
            --color-enlace-hover: #0056b3;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-fondo);
            color: var(--color-texto);
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        .contenedor {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 15px;
        }

        header {
            margin-bottom: 40px;
            padding: 20px 0;
            border-bottom: 2px solid var(--color-primario);
        }

        h1 {
            color: var(--color-primario);
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .descripcion {
            font-size: 1.1em;
            color: #6c757d;
        }

        .lista-carpetas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .carpeta-item {
            background-color: var(--color-tarjeta);
            border-radius: 8px;
            box-shadow: 0 4px 12px var(--color-sombra);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
            overflow: hidden;
        }

        .carpeta-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px var(--color-sombra);
        }

        .carpeta-link {
            display: block;
            padding: 20px 20px 10px 20px;
            text-decoration: none;
            color: var(--color-texto);
            font-weight: 600;
            font-size: 1.2em;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-wrap: nowrap;
        }
        
        .carpeta-link:hover {
            background-color: #f1f1f1;
            color: var(--color-enlace-hover);
        }

        .icono {
            margin-right: 15px;
            font-size: 1.5em;
            color: var(--color-primario);
            flex-shrink: 0;
        }

        .info-carpeta {
            flex-grow: 1;
        }

        .nombre-carpeta {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--color-texto);
        }

        .fecha-modificacion {
            display: block;
            font-size: 0.85em;
            color: var(--color-secundario);
            margin-top: 5px;
        }

        footer {
            margin-top: 50px;
            padding: 20px 0 10px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 0.9em;
        }

        /* Media Query para mejorar la responsividad en pantallas pequeñas */
        @media (max-width: 600px) {
            h1 {
                font-size: 2em;
            }
            .lista-carpetas {
                grid-template-columns: 1fr; /* Una sola columna en móviles */
            }
            .carpeta-link {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <header>
            <h1><?php echo $titulo; ?> 📚</h1>
            <p class="descripcion">Listado de prácticas. Las prácticas más recientes se muestran primero.</p>
        </header>

        <?php if (!empty($carpetas_info)): ?>
            <ul class="lista-carpetas">
                <?php foreach ($carpetas_info as $info): ?>
                    <li class="carpeta-item">
                        <a href="<?php echo htmlspecialchars($info['nombre']); ?>" class="carpeta-link">
                            <span class="icono">📁</span>
                            <div class="info-carpeta">
                                <span class="nombre-carpeta"><?php echo htmlspecialchars($info['nombre']); ?></span>
                                <span class="fecha-modificacion">Última modificación: **<?php echo $info['fecha_formateada']; ?>**</span>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alerta">
                <p>⚠️ No se encontraron carpetas de prácticas en este directorio.</p>
                <p>Crea una nueva carpeta para tu próximo práctico.</p>
            </div>
        <?php endif; ?>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Índice de Prácticas | Desarrollado con 💖 y PHP.</p>
        </footer>
    </div>
</body>
</html>