<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $article = Article::create([
            'title' => 'Memorias CAMM2: El fin de los módulos SO-DIMM',
            'subtitle' => 'La mayor revolución en el diseño de portátiles en los últimos 25 años llega para optimizar el rendimiento y el espacio.',
            'banner_url' => '/img/banners/banner1.jpg',
            'user_id' => 1,
            'content' => 'El estándar SO-DIMM, que ha dominado la industria de las computadoras portátiles por décadas, finalmente se enfrenta a su sucesor definitivo. El nuevo formato CAMM2 (Compression Attached Memory Module) no es solo una actualización estética, sino una reingeniería total de cómo la memoria RAM se conecta a la placa base. Al eliminar la necesidad de ranuras verticales voluminosas y acortar las pistas eléctricas entre el procesador y la memoria, este diseño permite reducir drásticamente el grosor de los equipos, facilitando la creación de portátiles más potentes y, al mismo tiempo, más compactos.\n Más allá del ahorro de espacio, la verdadera ventaja de CAMM2 reside en su capacidad para romper las barreras de velocidad y eficiencia energética. Mientras que los módulos tradicionales sufrían de degradación de señal al intentar alcanzar frecuencias extremadamente altas, la tecnología CAMM2 permite implementar memorias LPDDR5X de doble canal en un solo módulo sustituible. Esto significa que los usuarios ya no tendrán que elegir entre la velocidad de la memoria soldada y la versatilidad de la memoria reemplazable; el nuevo estándar ofrece lo mejor de ambos mundos, marcando el inicio de una era donde el alto rendimiento y la reparabilidad van de la mano.',
        ]);
        $article = Article::create([
            'title' => 'IA agéntica: Autonomía total en Windows y macOS',
            'subtitle' => 'Los sistemas operativos evolucionan de simples herramientas a compañeros que ejecutan tareas complejas por sí mismos.',
            'banner_url' => '/img/banners/banner2.jpg',
            'user_id' => 1,
            'content' => 'La era de los asistentes que solo responden preguntas ha quedado atrás para dar paso a la IA agéntica, una tecnología capaz de actuar de forma autónoma dentro del sistema operativo. A diferencia de los chatbots tradicionales, estos nuevos "agentes" integrados en Windows y macOS no solo sugieren acciones, sino que las ejecutan: pueden desde organizar una agenda cruzando correos y archivos locales, hasta gestionar flujos de trabajo completos entre aplicaciones de terceros sin intervención humana constante. Esta integración profunda permite que el sistema operativo comprenda el contexto global del usuario, transformando la interfaz en un centro de mando donde la IA toma decisiones lógicas para alcanzar objetivos específicos. \nEl despliegue de esta autonomía ha obligado a Microsoft y Apple a rediseñar sus arquitecturas de seguridad, introduciendo capas de "permisos agénticos" y registros de actividad a prueba de manipulaciones. Mientras que Windows ha apostado por funciones experimentales que permiten a la IA interactuar con el registro y la administración del sistema bajo supervisión, Apple ha enfocado su estrategia en una Siri potenciada con memoria persistente y capacidad de ejecución privada en el dispositivo. En 2026, el éxito de estos sistemas ya no se mide por la rapidez de sus respuestas, sino por su capacidad para resolver problemas de extremo a extremo, reduciendo la carga cognitiva del usuario y convirtiendo al ordenador en una entidad proactiva.',
        ]);
        $article = Article::create([
            'title' => 'Windows 12: Requisitos de NPU confirmados',
            'subtitle' => 'El nuevo estándar exige un mínimo de 40 TOPS para desbloquear las funciones de IA de nueva generación.',
            'banner_url' => '/img/banners/banner3.jpg',
            'user_id' => 1,
            'content' => 'Microsoft ha establecido finalmente la línea roja para el hardware del futuro: Windows 12 requerirá una NPU (Unidad de Procesamiento Neuronal) con una potencia mínima de 40 TOPS (billones de operaciones por segundo) para ejecutar de forma nativa sus funciones de IA más avanzadas. Esta decisión marca un cambio de paradigma en los requisitos del sistema, similar a lo que supuso el chip TPM 2.0 en su momento. Aunque el sistema operativo podrá arrancar en procesadores convencionales, las experiencias clave de la interfaz, como la búsqueda semántica profunda y el procesamiento de lenguaje natural en tiempo real, quedarán reservadas para equipos equipados con chips como los Intel Core Ultra, AMD Ryzen AI o Snapdragon X Elite.Además del requisito de la NPU, el gigante tecnológico ha elevado el listón de la memoria base, confirmando que 16 GB de RAM serán el nuevo estándar recomendado para garantizar la fluidez de los modelos de lenguaje que correrán en segundo plano. Los fabricantes de hardware ya están alineando sus lanzamientos para finales de 2026, asegurando que los nuevos dispositivos cumplan con esta "puerta de enlace" de hardware. Para el usuario final, esto significa que la actualización a Windows 12 no será solo una cuestión de software, sino una invitación a entrar en la era de los Copilot+ PCs, donde el silicio dedicado a la inteligencia artificial es tan crítico como la propia CPU.',
        ]);
        $article = Article::create([
            'title' => 'Pantallas OLED 3.0: El adiós definitivo al parpadeo',
            'subtitle' => 'La nueva generación de paneles introduce frecuencias de PWM ultraaltas y control híbrido para proteger la salud visual.',
            'banner_url' => '/img/banners/banner4.jpg',
            'user_id' => 1,
            'content' => 'La tecnología OLED 3.0 ha llegado para resolver uno de los problemas más persistentes de las pantallas orgánicas: el parpadeo invisible o flicker. Hasta ahora, muchos usuarios experimentaban fatiga ocular y dolores de cabeza debido a la modulación por ancho de pulsos (PWM) utilizada para controlar el brillo en niveles bajos. Los nuevos paneles presentados este año implementan frecuencias de refresco de modulación que superan los 4.800 Hz, junto con sistemas de atenuación por corriente continua (DC Dimming) mejorados. Esto logra que la fluctuación de luz sea imperceptible para el sistema nervioso humano, ofreciendo una experiencia de lectura y visualización tan estable como la del papel, incluso en entornos de total oscuridad.\nAdemás de la mejora en la salud visual, la arquitectura OLED 3.0 introduce materiales orgánicos de mayor eficiencia que permiten alcanzar picos de brillo superiores sin aumentar el consumo energético. Esta generación utiliza una estructura de píxeles rediseñada que minimiza la degradación (burn-in) y mejora la precisión del color en ángulos de visión extremos. Al eliminar la fatiga visual como barrera, los fabricantes buscan que estos paneles se conviertan en el estándar no solo para smartphones de gama alta, sino también para monitores profesionales y dispositivos de lectura prolongada, donde la comodidad ocular es la prioridad absoluta.',
        ]);
        $article = Article::create([
            'title' => 'Wi-Fi 7: Routers de bajo coste llegan al mercado',
            'subtitle' => 'La tecnología de conectividad más rápida del mundo rompe la barrera de los 100 euros para llegar a todos los hogares.',
            'banner_url' => '/img/banners/banner5.jpg',
            'user_id' => 1,
            'content' => 'El despliegue de Wi-Fi 7 ha alcanzado un punto de inflexión crítico este 2026 con la llegada masiva de dispositivos asequibles. Si bien hace un año esta tecnología estaba reservada a entusiastas con presupuestos elevados, fabricantes como TP-Link y Mercusys han lanzado modelos que sitúan el precio de entrada por debajo de las tres cifras. Estos nuevos routers "budget" permiten a los usuarios domésticos acceder a funciones antes exclusivas, como la operación multienlace (MLO), que permite conectar dispositivos a varias bandas simultáneamente para reducir la latencia, y puertos de 2.5 Gbps que eliminan los cuellos de botella en las conexiones de fibra óptica modernas.\nLa democratización de este estándar es clave para el ecosistema del hogar conectado, especialmente con el auge de las gafas de realidad mixta y el streaming en 8K. Aunque los modelos más económicos suelen prescindir de la banda de 6 GHz para abaratar costes, mantienen las mejoras de eficiencia y capacidad de gestión de múltiples dispositivos simultáneos propias del protocolo 802.11be. Este movimiento de la industria asegura que el Wi-Fi 7 no sea solo un lujo para gamers o profesionales, sino el nuevo cimiento de la red doméstica, permitiendo que incluso los usuarios con necesidades básicas disfruten de una conexión más estable y preparada para el futuro.',
        ]);
        $article = Article::create([
            'title' => 'GPU 2026: Llegan las primeras tarjetas con GDDR7',
            'subtitle' => 'La nueva arquitectura de memoria alcanza velocidades de hasta 32 Gbps, duplicando el ancho de banda para el gaming en 4K y 8K.',
            'banner_url' => '/img/banners/banner6.jpg',
            'user_id' => 1,
            'content' => 'Tras meses de anticipación, las primeras tarjetas gráficas equipadas con memoria GDDR7 finalmente han desembarcado en el mercado, lideradas por las series de gama alta de NVIDIA y AMD. Este nuevo estándar de memoria no es una simple evolución, sino un salto tecnológico que introduce la señalización PAM3, permitiendo transmitir más datos por ciclo de reloj que la anterior GDDR6X. Con anchos de banda que pueden superar con creces el 1.5 TB/s en modelos insignia, las GPUs de 2026 eliminan los cuellos de botella en resoluciones ultra altas, permitiendo que el trazado de rayos (Ray Tracing) y las texturas de alta fidelidad fluyan con una suavidad sin precedentes.\nSin embargo, el estreno de la GDDR7 no ha estado exento de desafíos, ya que la industria enfrenta una escasez de módulos debido a la altísima demanda de los centros de datos de IA. Aunque fabricantes como Samsung y SK Hynix han acelerado la producción, las primeras unidades se han concentrado en las variantes entusiastas, dejando las gamas medias a la espera de una mayor disponibilidad de componentes. A pesar de esto, la eficiencia energética mejorada de la GDDR7 está permitiendo que estas tarjetas mantengan temperaturas más estables bajo carga máxima, consolidando este estándar como el pilar fundamental para la próxima década de procesamiento gráfico.',
        ]);
    }
}
