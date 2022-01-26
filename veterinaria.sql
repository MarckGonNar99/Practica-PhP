-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-01-2022 a las 00:36:37
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `veterinaria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `codigo_m` int(2) NOT NULL COMMENT 'Mascota',
  `codigo_s` int(2) NOT NULL COMMENT 'Servicio',
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`codigo_m`, `codigo_s`, `fecha`, `hora`) VALUES
(13, 2, '2022-01-27', '12:30:00'),
(15, 2, '2022-01-31', '18:00:00'),
(17, 2, '2021-12-30', '19:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(2) NOT NULL,
  `tipo_animal` varchar(25) NOT NULL,
  `nombre` varchar(25) NOT NULL COMMENT 'nombre del animal',
  `edad` int(2) NOT NULL,
  `dni_dueño` varchar(9) NOT NULL,
  `foto` varchar(350) NOT NULL COMMENT 'url de imagen'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `tipo_animal`, `nombre`, `edad`, `dni_dueño`, `foto`) VALUES
(13, 'Pato', 'Foskitos', 3, '12345678M', '../imagenes/clientes/Foskitos_13.jpg'),
(14, 'Perro', 'Wolfie', 4, '12345678M', '../imagenes/clientes/Firulais_14.jpg'),
(15, 'Caballo', 'juan', 4, '75573548D', '../imagenes/clientes/juan_15.jpg'),
(17, 'Gato', 'Pelusa', 2, '78945123X', '../imagenes/clientes/Pelusa_17.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dueño`
--

CREATE TABLE `dueño` (
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `pass` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `dueño`
--

INSERT INTO `dueño` (`dni`, `nombre`, `telefono`, `nick`, `pass`) VALUES
('00000000A', 'Administrador', '000000000', 'admin', '21232f297a57a5a743894a0e4a801fc3'),
('12345678M', 'Juan', '630457333', 'Juancho', 'a94652aa97c7211ba8954dd15a3cf838'),
('75573548D', 'Mario', '630528128', 'TheMario', 'de2f15d014d40b93578d255e6221fd60'),
('78945123X', 'María', '625265627', 'Maria12', '263bce650e68ab4e23f28263760b9fa5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticia`
--

CREATE TABLE `noticia` (
  `id` int(2) NOT NULL,
  `titulo` varchar(500) NOT NULL,
  `contenido` varchar(2000) NOT NULL,
  `imagen` varchar(350) NOT NULL COMMENT 'url de la imagen',
  `fecha` date NOT NULL COMMENT 'fecha para publicar'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `noticia`
--

INSERT INTO `noticia` (`id`, `titulo`, `contenido`, `imagen`, `fecha`) VALUES
(15, 'Desenterrado en Inglaterra un fósil de ictiosaurio de 10 metros', 'El descubrimiento se produjo por pura casualidad en febrero de 2021, cuando en el curso de unos trabajos de drenaje en una laguna se encontraron algunas vértebras sobresaliendo del fango. Durante los meses de agosto y septiembre, un grupo de arqueólogos de la Universidad de Manchester, dirigidos por Dean Lomax – cuyo foco de estudio son precisamente los ictiosaurios – excavó el fósil completo. Pero los trabajos están lejos de haber terminado, puesto que queda pendiente la separación y limpieza de la roca, que pueden llevar de 12 a 18 meses. Cuando se haya completado, la intención es devolverlo a Rutland para exponerlo.', '../imagenes/noticias/noticia_15.jpg', '2022-01-23'),
(16, 'Más de 678 toneladas de flora exótica invasora retiradas en 2021 en Tenerife', 'En 2021 los servicios del Cabildo de Tenerife retiraron de los espacios naturales de la isla 678.677 kilos de restos de flora exótica invasora, de los cuales se han trasladado al vertedero 170.860,2 kilos y se han incorporado al terreno en forma de abono 507.817,5 kilos.<br />\r\n<br />\r\nEs decir, solo una cuarta parte de los residuos se ha llevado al vertedero, precisa el Cabildo en un comunicado.<br />\r\n<br />\r\nPara alcanzar esta cifra, las veinte cuadrillas encargadas han realizado 1.136 actuaciones alrededor de la isla, entre las que se incluyen revisiones de zonas en las que es necesario repasar los trabajos para asegurar el terreno y evitar que las especies vuelvan a germinar.<br />\r\n<br />\r\nLa superficie trabajada comprende 2.568,7 hectáreas, esto implica 359,78 metros cuadrados más que en 2020.<br />\r\n<br />\r\nDe entre las diferentes especies invasoras con las que se ha trabajado, destaca el Plumero (Cortaderia selloana) y la Tunera India (Opuntia dellinii) que suponen casi la mitad (44,6%) del total de kilos retirados de entre 60 especies de plantas diferentes.', '../imagenes/noticias/noticia_16.jpg', '2022-01-10'),
(17, 'Identificados 459 milanos en el último censo en Mallorca', 'El último censo de milanos realizado en Mallorca ha identificado 459 ejemplares y 17 dormideros activos, 5 de los cuales son nuevos, aunque otros 3 registrados en años anteriores han quedado inactivos.<br />\r\n<br />\r\nSegún ha informado en un comunicado el Grupo Balear de Ornitología y Defensa de la Naturaleza (Gob), este censo forma parte de un proyecto de ámbito europeo y se ejecutó del 8 al 10 enero.<br />\r\n<br />\r\nEl Gob explica que en invierno los milanos se agrupan en dormideros comunales que les ofrecen protección y facilitan las comunicaciones y la relación de los grupos de aves.<br />\r\n<br />\r\nSi no hay problemas como molestias por factores externos o cambios de configuración, los grupos de milanos utilizan estos emplazamientos año tras año, aunque muchas veces cambian de unos a otros.', '../imagenes/noticias/noticia_17.jpg', '2022-01-14'),
(18, 'Desprotegidos legalmente el 80% de los vertebrados amenazados en España', 'uatro de cada cinco especies de vertebrados terrestres vulnerables o en peligro de extinción en España carecen de un plan de protección pese a que la legislación obliga a las comunidades autónomas a dotar de uno de estos planes de gestión y conservación.<br />\r\n<br />\r\nAsí se desprende de un estudio sobre la desprotección publicado recientemente en la revista internacional ‘Journal for Nature Conservation’ firmado por Jorge García-Macía, del área de Zoología de la Universidad de Alicante; Irene Pérez Ibarra, de la Universidad de Zaragoza; y Roberto C. Rodríguez-Caro, del Departamento de Biología Aplicada de la Universidad Miguel Hernández de Elche (UMH).', '../imagenes/noticias/noticia_18.jpg', '2022-01-18'),
(19, 'Los perros diferencian idiomas', 'La revista NeuroImage fue el medio que publicó el estudio que tuvo como objetivo, investigar si los perros eran capaces de discernir entre los diferentes idiomas, algo que hasta ahora se consideraba potestad de los seres humanos, que pueden hacerlo a edades muy tempranas. Laura V. Cuaya es la primera autora del artículo y es quien explica que al trasladarse de un país a otro y llevarse consigo a su can se preguntó cómo se comportaría su perro cuando la gente a su alrededor solo hablara otro idioma. Esta etóloga húngara se mudó a México para completar un trabajo postdoctoral.<br />\r\n<br />\r\n', '../imagenes/noticias/noticia_19.jpg', '2022-01-20'),
(20, 'Un huevo de dinosaurio hallado en China refuerza la teoría de su relación con las aves', 'Un grupo de científicos chinos, canadienses y británicos publicaron en la revista Science un artículo sobre un huevo de dinosaurio fosilizado hallado en la provincia central china de Jiangxi que contiene un embrión de 27 centímetros, recoge hoy la cadena estatal CCTV.<br />\r\n<br />\r\nEl huevo fosilizado, de una forma alargada y de 17 centímetros de longitud, tiene alrededor de 70 millones de años y contiene uno de los fósiles de embrión de dinosaurio mejor conservados, según CCTV.<br />\r\n<br />\r\nEl embrión pertenece a la suborden de los terópodos, del período Triásico, y se halla acurrucado dentro del huevo en una postura, con la cabeza entre las patas, que hasta ahora solo se había detectado en dinosaurios aviares, según el estudio.', '../imagenes/noticias/noticia_20.jpg', '2022-01-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(2) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `precio` double(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `precio`) VALUES
(1, 'Galletas de perro', 5.50),
(8, 'Peine de gato', 8.00),
(9, 'pelota de goma', 1.00),
(10, 'Pienso para aves', 4.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `codigo` int(2) NOT NULL,
  `descripcion` varchar(25) NOT NULL,
  `duracion` int(3) NOT NULL,
  `precio` double(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`codigo`, `descripcion`, `duracion`, `precio`) VALUES
(1, 'Peinado', 20, 4.99),
(2, 'Corte de pelo', 30, 7.99),
(7, 'Desparasitar', 15, 9.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonio`
--

CREATE TABLE `testimonio` (
  `id` int(2) NOT NULL,
  `dni_autor` varchar(9) NOT NULL,
  `contenido` varchar(800) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `testimonio`
--

INSERT INTO `testimonio` (`id`, `dni_autor`, `contenido`, `fecha`) VALUES
(1, '12345678M', 'Gente muy eficiente, profesionales y amables', '2022-01-12'),
(2, '12345678M', 'muy maja la gente', '2022-01-23'),
(4, '78945123X', 'A mi gata antes le daba miedo ir al veterinario, gracias a mi hermano los conocí y ahora me quedo tranquila de que mi gata no se angustie', '2022-01-24');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`codigo_m`,`codigo_s`,`fecha`),
  ADD KEY `codigo_m` (`codigo_m`,`codigo_s`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `dueño`
--
ALTER TABLE `dueño`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `noticia`
--
ALTER TABLE `noticia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `testimonio`
--
ALTER TABLE `testimonio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dni_autor` (`dni_autor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `noticia`
--
ALTER TABLE `noticia`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `codigo` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `testimonio`
--
ALTER TABLE `testimonio`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`codigo_m`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`codigo_s`) REFERENCES `servicio` (`codigo`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`dni_dueño`) REFERENCES `dueño` (`dni`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `testimonio`
--
ALTER TABLE `testimonio`
  ADD CONSTRAINT `testimonio_ibfk_1` FOREIGN KEY (`dni_autor`) REFERENCES `dueño` (`dni`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
