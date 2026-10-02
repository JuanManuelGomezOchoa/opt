-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ecommerce
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asociarproductos`
--

DROP TABLE IF EXISTS `asociarproductos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asociarproductos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproductopadre` int(255) NOT NULL,
  `idproductopack` int(255) NOT NULL,
  `cantidadpack` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asociarproductos`
--

LOCK TABLES `asociarproductos` WRITE;
/*!40000 ALTER TABLE `asociarproductos` DISABLE KEYS */;
/*!40000 ALTER TABLE `asociarproductos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asociartallas`
--

DROP TABLE IF EXISTS `asociartallas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asociartallas` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproductoprincipal` int(255) NOT NULL,
  `idproductotalla` int(255) NOT NULL,
  `talla` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asociartallas`
--

LOCK TABLES `asociartallas` WRITE;
/*!40000 ALTER TABLE `asociartallas` DISABLE KEYS */;
/*!40000 ALTER TABLE `asociartallas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogos`
--

DROP TABLE IF EXISTS `catalogos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalogos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `estatus` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogos`
--

LOCK TABLES `catalogos` WRITE;
/*!40000 ALTER TABLE `catalogos` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalogos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriasasociadas`
--

DROP TABLE IF EXISTS `categoriasasociadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoriasasociadas` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproducto` int(255) NOT NULL,
  `categoria` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriasasociadas`
--

LOCK TABLES `categoriasasociadas` WRITE;
/*!40000 ALTER TABLE `categoriasasociadas` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoriasasociadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriasasociadasventa`
--

DROP TABLE IF EXISTS `categoriasasociadasventa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoriasasociadasventa` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproducto` int(255) NOT NULL,
  `categoria` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriasasociadasventa`
--

LOCK TABLES `categoriasasociadasventa` WRITE;
/*!40000 ALTER TABLE `categoriasasociadasventa` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoriasasociadasventa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuraciones`
--

DROP TABLE IF EXISTS `configuraciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuraciones` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `detalle` varchar(255) NOT NULL,
  `valoruno` mediumtext NOT NULL,
  `valordos` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuraciones`
--

LOCK TABLES `configuraciones` WRITE;
/*!40000 ALTER TABLE `configuraciones` DISABLE KEYS */;
INSERT INTO `configuraciones` VALUES (1,'Envio','Ingresa el costo del envió y monto mínimo de compra para el envío gratis','2000','200'),(2,'Facturacion','Configura la url para redireccionar al usuario y el texto que se muestra para la advertencia al efectuar la compra','Para generar tu factura envianos un whatsapp al 449123XXXX, en un plazo no mayor a 72 horas.¡Gracias por tu compra!','https://api.whatsapp.com/send?phone=+4493523433&text=Hola,%20quiero%20facturar%20mi%20compra '),(3,'Aviso de privacidad / Términos y condiciones','Configuración para el texto que se muestra en términos y condiciones y aviso de privacidad','En cumplimiento de lo dispuesto por la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (en lo sucesivo la “Ley”), el Reglamento de la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (en delante el “Reglamento”) y los Lineamientos del Aviso de Privacidad publicado en el Diario Oficial de la Federación el día 17 de enero de 2013 (en lo sucesivo los “Lineamientos”) y demás disposiciones aplicables, Fastpack Industrial S. A. de C. V. (en lo sucesivo “FASTPACK”) desea hacer del conocimiento del usuario de los dominios o sitios web www.news.fastpack.mx (en lo sucesivo el “Usuario”) y para las personas que contraten los servicios de FASTPACK (consiguientemente referidos como “Clientes”), su Aviso de Privacidad respecto del tratamiento y protección de los datos de carácter personal de aquellas personas que voluntariamente se comunican, de forma enunciativa más no limitativa, a través de correo electrónico, telefónicamente o cualquier otro medio electrónico, oral o escrito con FASTPACK, que llenan formularios en los que se recaben datos personales y/o que ingresen al sitio web aquí mencionado, si es que esto implica la comunicación de sus datos personales.\r\n\r\nFASTPACK señala como domicilio el ubicado en Av. Viña Antigua No. 142-1, Col. Viña Antigua, Jesús María, Aguascalientes, C.P. 20908. Teléfono: (+52) 449 352 3433.\r\n\r\nConforme el artículo 3 (tres) de la Ley Federal de Protección de Datos Personales en Posesión de los Particulares, se entenderá por:\r\n1. Aviso de Privacidad: Documento físico, electrónico o en cualquier otro formato generado por el responsable que es puesto a disposición del titular, previo al tratamiento de tus datos personales, de conformidad con el artículo 15 (quince) de la mencionada Ley.\r\n2. Bases de Datos: El conjunto ordenado de datos personales referentes a una persona identificada o identificable.\r\n3. Bloqueo: La identificación y conservación de datos personales una vez cumplida la finalidad para la cual fueron recabados, con el único propósito de determinar posibles responsabilidades en relación con su tratamiento, hasta el plazo de prescripción legal o contractual de éstas. Durante dicho periodo, los datos personales no podrán ser objeto de tratamiento y transcurrido éste, se procederá a su cancelación en la base de datos que corresponde.\r\n4. Consentimiento: Manifestación de la voluntad del titular de los datos mediante la cual se efectuará el tratamiento de los mismos.\r\n5. Datos Personales: Cualquier información concerniente a una persona física identificada o identificable.\r\n6. Datos Personales Sensibles: Aquellos datos personales que afecten a la esfera más íntima de su titular, o cuya utilización indebida pueda dar origen a discriminación o conlleve un riesgo grave para éste. En particular, se consideran sensibles aquellos que puedan revelar aspectos como origen racial o étnico, estado de salud presente y futuro, información genética, creencias religiosas, filosóficas y morales, afiliación sindical, opiniones políticas, preferencia sexual.\r\n7. Días: Días hábiles.\r\n8. Disociación: El procedimiento mediante el cual los datos personales no pueden asociarse al titular ni permitir, por su estructura, contenido o grado de desagregación, la identificación del mismo.\r\n9. Encargado: la persona física o jurídica que sola o conjuntamente con otras trate datos personales por cuenta del responsable.\r\n10. Fuente de Acceso Público: Aquellas bases de datos cuya consulta puede ser realizada por cualquier persona, sin más requisito que, en su caso, el pago de una contraprestación, de conformidad con lo señalado por el reglamento de esta Ley.\r\n11. Instituto: Instituto Federal de Acceso a la Información y Protección de Datos, a que hace referencia la Ley Federal de Transparencia y Acceso a la Información Pública Gubernamental.\r\n12. Ley: Ley Federal de Protección de Datos Personales en Posesión de los Particulares.\r\n13. Reglamento: El Reglamento de la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.\r\n14. Responsable: Persona física o moral de carácter privado que decide sobre el tratamiento de datos personales.\r\n15. Secretaría: Secretaría de Economía.\r\n16. Tercero: La persona física o moral, nacional o extranjera, distinta del titular o del responsable de los datos.\r\n17. Titular: La persona física o quien corresponden los datos personales.\r\n18. Tratamiento: La obtención, uso, divulgación o almacenamiento de datos personales por cualquier medio. El uso abarca cualquier acción de acceso, manejo, aprovechamiento, transferencia o disposición de datos personales.\r\n19. Transferencia: Toda comunicación de datos realizada a persona distinta del responsable o encargado del tratamiento.\r\n\r\nPara todos los efectos relacionados con el presente Aviso de Privacidad, FASTPACK pone a disposición del Usuario el presente Aviso de Privacidad, bajo los siguientes términos:\r\n1. FINALIDAD DEL TRATAMIENTO DE SUS DATOS PERSONALES. - La información personal que proporcione el Usuario será utilizada únicamente para:\r\n• Actividades de administración interna de los clientes.\r\n• Para formar el expediente del cliente.\r\n• Con fines de identificación para el servicio que se presta.\r\n• Para usarse como dirección de origen y/o destino de los servicios de paquetería contratados por medio del sitio web de FASTPACK. Estos datos son indispensables para el control interno y para prestación de los servicios.\r\n• Proveer los servicios solicitados.\r\n• Comunicarle sobre cambios en los mismos.\r\n• Atender quejas, sugerencias y solicitudes derivadas de los servicios contratados.\r\n• Verificar la existencia de cobertura en su domicilio.\r\n• Facturación y cobranza de los servicios contratados.\r\n• Evaluar la calidad del servicio que brindamos, para dar cumplimiento a las obligaciones que hemos contraído con usted.\r\n• Contactarle a usted y a sus contactos y/o referencias para dar seguimiento a cualquier tema relacionado con los servicios o con las presentes finalidades.\r\n\r\nAsimismo, sus Datos Personales podrán ser utilizados para las siguientes actividades secundarias:\r\n• Para fines mercadotécnicos.\r\n• Fines publicitarios.\r\n• Prospección comercial; lo anterior con el propósito de ofrecerle diversos servicios que prestan terceros.\r\n• Notificarle sobre nuevos servicios o productos que tengan relación con los ya contratados o adquiridos.\r\n• Realizar estudios sobre hábitos de consumo y de mercado.\r\nfdgdfdgdgfgd\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nSi usted no está de acuerdo con las finalidades secundarias, podrá manifestar su negativa siguiendo el mecanismo previsto en el numeral “VII” del presente Aviso de Privacidad. Es preciso señalar que, en caso de actualizarse el supuesto antes señalado, FASTPACK no le podrá brindar los servicios que presta de forma correcta y ésta no será responsable por tal situación.\r\n1. DATOS PERSONALES SOLICITADOS.\r\nPara efectos del presente Aviso de Privacidad se entenderá por dato de carácter personal, cualquier información que se recabe concerniente a personas físicas o morales identificadas o identificables; y por Usuario, a cualquier persona física o moral identificada o identificable que comunique sus datos de carácter personal a través del sitio web aquí mencionados que de manera enunciativa más no limitativa pudiera ser a través de:\r\n• Correo electrónico.\r\n• Llenado de formularios por los que se recaban Datos Personales en la página web.\r\n• De manera personal al acudir a la empresa.\r\n• Creación de cuenta de Usuario en el sitio web de FASTPACK, y en general el uso de cualquier servicio presente del sitio web que impliquen la comunicación de sus Datos Personales.\r\n\r\nLos Datos Personales que recabará FASTPACK del Usuario:\r\n• Nombre y apellido.\r\n• Dirección completa.\r\n• Correo electrónico.\r\n• Datos fiscales.\r\n\r\nIII. SOBRE REDES SOCIALES.\r\nFASTPACK hace del conocimiento del Usuario que está presente en redes sociales, como Facebook, Instagram, Tiktok, sin limitar. El Tratamiento de los datos del Usuario que se hagan seguidores de las páginas oficiales de FASTPACK se regirá por las condiciones previstas en los términos y condiciones de la red social que corresponda en virtud de que cualquier dato que la red social requiera sea recabado es responsabilidad de ésta, y no de FASTPACK, por lo que se recomienda el acceso a la política de privacidad y condiciones de uso de las mencionadas páginas o, en su caso, de la propia red social, con el fin de conocer la información derivada del tratamiento de los datos de carácter personal y especialmente las condiciones y finalidades a las que serán destinados los datos que forman parte del perfil del Usuario.\r\n1. RESGUARDO DE DATOS PERSONALES.\r\nLos Datos Personales del Usuario serán resguardados con base a los principios de licitud, consentimiento, información, calidad, finalidad, lealtad, proporcionalidad y responsabilidad, mismos que se encuentran consagrados en la Ley.\r\n2. TRANSFERENCIA DE DATOS.\r\nFASTPACK no podrá transferir la información personal recabada, salvo cuando:\r\na) sea solicitada por dependencias gubernamentales, y\r\nb) sea solicitada por instituciones bancarias o de crédito, para la realización de pagos y transferencias.\r\n3. CAMBIOS O MODIFICACIONES AL AVISO.\r\nFASTPACK se reserva el derecho de modificar el presente Aviso por lo que hace de su conocimiento que cualquier cambio o modificación al contenido del presente será publicado, de manera generalizada en el sitio web www.news.fastpack.mx y/o por envío de correo electrónico a los Titulares de los Datos Personales.\r\n\r\nVII. MEDIOS Y PROCEDIMIENTO PARA EJERCER DERECHOS ARCO Y/O REVOCACIÓN DE CONSENTIMIENTO PARA EL TRATAMIENTO DE DATOS PERSONALES:\r\nDe conformidad con lo dispuesto en la Ley en su artículo 6 (seis), el Usuario podrá acceder, rectificar y cancelar sus Datos Personales, así como oponerse a la divulgación de los mismos a través de los procedimientos que FASTPACK ha implementado a través de su Área de Soporte. El Usuario podrá solicitar el formato para acceder, rectificar, cancelar y oponerse a la divulgación de los mismos. Asimismo, tendrá un plazo de 5 (cinco) Días para manifestar su negativa al Tratamiento de tus Datos Personales para actividades secundarias. Para más información sobre como ejercer sus Derechos de Acceso, Rectificación, Cancelación y Oposición (Derechos ARCO), oponerse a la divulgación de tus datos, revocar su Consentimiento o manifestar negativa al Tratamiento de los mismos, deberá ponerse en contacto mediante el envío de un correo electrónico a mkt@fastpack.mx.\r\nEn los anteriores casos se deberá indicar lo siguiente conforme el artículo 29 (veintinueve) de la Ley en cuestión:\r\nEl nombre del Titular y domicilio u otro medio para comunicarle la respuesta a su solicitud:\r\n• Los documentos que acrediten la identidad o, en su caso, la representación legal del Titular;\r\n• La descripción clara y precisa de los Datos Personales respecto de los que se busca ejercer alguno de los derechos antes mencionados; y,\r\n• Cualquier otro elemento o documento que facilite la localización de los Datos Personales.\r\n\r\nPara esto, deberá acreditar su personalidad con la que sustenta la titularidad de la cual deberá presentar además la identificación oficial en digital (legible).\r\n\r\nEn caso de que la información proporcionada en el formulario sea erróneo o insuficiente, o bien, no se acompañen los documentos de acreditación correspondientes, FASTPACK, podrá requerirle que aporte los elementos o documentos necesarios para dar trámite a su solicitud dentro de los 10 (diez) días hábiles siguientes a la recepción de esta. Usted contará con 10 (diez) días hábiles para atender el requerimiento, contados a partir del día siguiente en que lo haya recibido. De no dar respuesta dentro de dicho plazo, se tendrá por no presentada la solicitud correspondiente.\r\n\r\nFASTPACK le comunicará la determinación adoptada en un plazo máximo de 20 (veinte) días hábiles contados desde la fecha en que se recibió la solicitud, a efecto de que, si resulta procedente, haga efectiva la misma dentro de los 15 (quince) días hábiles siguientes a que se comunique la respuesta. La respuesta se dará vía electrónica a la dirección de correo electrónico que se especifique en el formulario.\r\n\r\nVIII. TRATAMIENTO DE DATOS SIN CONSENTIMIENTO.\r\nNo será necesario el Consentimiento para el Tratamiento de los Datos Personales conforme el artículo 10 (décimo) de la Ley, en los siguientes casos:\r\n• Esté previsto en una ley.\r\n• Los datos figuren en fuentes de acceso público.\r\n• Los Datos Personales se sometan a un procedimiento previo de Disociación.\r\n• Tenga el propósito de cumplir obligaciones derivadas de una relación jurídica entre el Titular y el responsable.\r\n• Exista una situación de emergencia que potencialmente pueda dañar a un individuo en su persona o en sus bienes.\r\n• Sean indispensables para la atención médica, la prevención, diagnóstico, la prestación de asistencia sanitaria, tratamientos médicos o la gestión de servicios sanitarios, mientras el Titular no esté en condiciones de otorgar el Consentimiento, en los términos que establece la Ley General de Salud y demás disposiciones jurídicas aplicables y que dicho Tratamiento de Datos se realice por una persona sujeta al secreto profesional u obligación equivalente, o;\r\n• Se dicte resolución de autoridad competente.\r\n\r\n1. OPCIONES Y MEDIOS PARA LIMITAR EL USO O DIVULGACIÓN DE SUS DATOS PERSONALES.\r\nUsted podrá limitar el uso o divulgación de tus Datos Personales enviando su solicitud al correo electrónico mkt@fastpack.mx. Los requisitos para acreditar su identidad, así como el procedimiento para atender su solicitud se regirán por los mismos criterios señalados en el apartado anterior.\r\nEn caso de que su solicitud resulte procedente, www.news.fastpack.mx lo registrará en el listado de exclusión propio de FASTPACK con el objeto de que usted deje de recibir nuestras promociones. Las notificaciones relativas a los cambios en los costos o servicios no son consideradas en los listados de exclusión por ser una obligación de FASTPACK ante la Procuraduría Federal del Consumidor.\r\n\r\n2. USO DE COOKIES\r\nFASTPACK utiliza varias tecnologías para mejorar la eficiencia de sus sitios web, incluyendo su experiencia cuando navega por dichos sitios. Entre estas tecnologías se incluye el uso de cookies. Las cookies son pequeñas cantidades de información que se almacenan en el navegador utilizado por cada Usuario para que el servidor recuerde cierta información que posteriormente pueda utilizar. Esta información permite identificarle y guardar sus preferencias personales para brindarle una mejor experiencia de navegación. Le recordamos que usted puede deshabilitar o ajustar el uso de cookies siguiendo los procedimientos del navegador de Internet que utiliza.\r\n\r\n3. MEDIO PARA COMUNICAR CAMBIOS AL AVISO DE PRIVACIDAD\r\nFASTPACK se reserva el derecho, bajo su exclusiva discreción, de cambiar, modificar, agregar o eliminar partes del presente Aviso de Privacidad en cualquier momento. En tal caso, FASTPACK publicará dichas modificaciones en el sitio web www.news.fastpack.mx en donde se indicará la fecha de última versión del aviso.\r\nLe recomendamos visitar periódicamente esta página con la finalidad de informarse si ocurre algún cambio al presente.\r\n\r\nACTUALIZADO AL 18 DE AGOSTO DEL 2023.','Terminos aqui....dsfds'),(4,'Comisión','Aquí se controla el valor de la comisión que se le aumentara a cada producto por el procesamiento de pago con openpay. (Considerar porcentaje + comisión fija)','5%','');
/*!40000 ALTER TABLE `configuraciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cupones`
--

DROP TABLE IF EXISTS `cupones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cupones` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `cupon` varchar(255) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `porcentaje` int(255) NOT NULL,
  `minimo` int(255) NOT NULL,
  `maximo` int(255) NOT NULL,
  `canjes` int(11) NOT NULL,
  `estatus` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cupones`
--

LOCK TABLES `cupones` WRITE;
/*!40000 ALTER TABLE `cupones` DISABLE KEYS */;
/*!40000 ALTER TABLE `cupones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuponescanjeados`
--

DROP TABLE IF EXISTS `cuponescanjeados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuponescanjeados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) NOT NULL,
  `identificador` varchar(255) NOT NULL,
  `monto` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuponescanjeados`
--

LOCK TABLES `cuponescanjeados` WRITE;
/*!40000 ALTER TABLE `cuponescanjeados` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuponescanjeados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `industriaasociada`
--

DROP TABLE IF EXISTS `industriaasociada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `industriaasociada` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproducto` int(255) NOT NULL,
  `industria` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `industriaasociada`
--

LOCK TABLES `industriaasociada` WRITE;
/*!40000 ALTER TABLE `industriaasociada` DISABLE KEYS */;
/*!40000 ALTER TABLE `industriaasociada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `industriaasociadaventa`
--

DROP TABLE IF EXISTS `industriaasociadaventa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `industriaasociadaventa` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproducto` int(255) NOT NULL,
  `industria` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `industriaasociadaventa`
--

LOCK TABLES `industriaasociadaventa` WRITE;
/*!40000 ALTER TABLE `industriaasociadaventa` DISABLE KEYS */;
/*!40000 ALTER TABLE `industriaasociadaventa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `industrias`
--

DROP TABLE IF EXISTS `industrias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `industrias` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `industria` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `industrias`
--

LOCK TABLES `industrias` WRITE;
/*!40000 ALTER TABLE `industrias` DISABLE KEYS */;
/*!40000 ALTER TABLE `industrias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medios`
--

DROP TABLE IF EXISTS `medios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medios` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproducto` int(255) NOT NULL,
  `medio` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medios`
--

LOCK TABLES `medios` WRITE;
/*!40000 ALTER TABLE `medios` DISABLE KEYS */;
/*!40000 ALTER TABLE `medios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mediosventa`
--

DROP TABLE IF EXISTS `mediosventa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mediosventa` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproducto` int(255) NOT NULL,
  `medio` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mediosventa`
--

LOCK TABLES `mediosventa` WRITE;
/*!40000 ALTER TABLE `mediosventa` DISABLE KEYS */;
/*!40000 ALTER TABLE `mediosventa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `identificador` varchar(255) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `nombre` varchar(100) NOT NULL,
  `apellidop` varchar(100) NOT NULL,
  `apellidom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` text DEFAULT NULL,
  `calle` text DEFAULT NULL,
  `exterior` text DEFAULT NULL,
  `interior` text DEFAULT NULL,
  `colonia` text DEFAULT NULL,
  `ciudad` varchar(150) NOT NULL,
  `estado` varchar(150) NOT NULL,
  `postal` text DEFAULT NULL,
  `pais` varchar(150) NOT NULL,
  `cupon` varchar(100) DEFAULT NULL,
  `cuponMonto` varchar(100) NOT NULL,
  `descuentoTotal` varchar(50) NOT NULL,
  `subtotal` varchar(10) DEFAULT NULL,
  `total` varchar(10) DEFAULT NULL,
  `productos` varchar(2000) NOT NULL,
  `envioMonto` varchar(50) NOT NULL,
  `estatus` int(2) NOT NULL,
  `openpay_id` varchar(500) DEFAULT NULL,
  `status_pago` varchar(500) DEFAULT NULL,
  `authorization` varchar(500) NOT NULL,
  `guia` varchar(255) DEFAULT NULL,
  `pdf_url` varchar(1000) DEFAULT NULL,
  `clabe` varchar(100) DEFAULT NULL,
  `vigencia` varchar(100) NOT NULL,
  `banco` varchar(100) NOT NULL,
  `convenio` varchar(100) NOT NULL,
  `referencia` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,'MIEMPRESA-0000001-JSN','2026-07-17','Juan Carlos','Santoyo','Navarro','utm23090764@utma.edu.mx','t08qqaBJkOZmAWUv2jw6uTZ3alpBcvXy8fvI1PFKmlJtAG5WAL4=','','2EX9CHeZ4gYOs0lcNxrvQhf1cRqudva9//Z8xRiIdA==','','YeqErwo/27LpNppEcZBHJaxRChPThJNFxYXmbvjbO/TZJg==','ags','ags','2fiTaSx7Op2aEGVHMaVKplD4bWfvKSbybUqXusHV4aTB','Islas Sandwich',NULL,'0','0','157.5','357.5','[{\"id\":\"2\",\"cantidad\":6}]','200',1,NULL,NULL,'',NULL,NULL,NULL,'','','',''),(2,'MIEMPRESA-0000002-JSN','2026-07-17','Juan Carlos','Santoyo','Navarro','utm23090764@utma.edu.mx','JYBAWWsDl4/3YET3VUUWyWSGnnwe5EhZ0bJPx3z1lyyvlgqV9Q8=','','CK8WP2fgCMR2qRISu8m9TldTZr9Ebol04Ja1vTiYvQ==','','6HmQ16/NCDdU3DbWDnqqlZiTHsns1W/1lQ+mg78l4MXXjQ==','Aguascalientes','Ags','lSouIuyKqfaB5I6OFgRHz12OBWq8YKu5hwWUWRbR3woF','Mexico',NULL,'0','0','183.75','383.75','[{\"id\":\"2\",\"cantidad\":7}]','200',1,'trqaglybapdmzedmypqc','Pagado','',NULL,NULL,NULL,'','','','');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `subtitulo` varchar(100) NOT NULL,
  `detalles` varchar(2000) NOT NULL,
  `estatus` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productospedidos`
--

DROP TABLE IF EXISTS `productospedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productospedidos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproducto` int(255) NOT NULL,
  `identificador` varchar(1000) NOT NULL,
  `cantidad` int(255) NOT NULL,
  `precio` double(10,2) NOT NULL,
  `surtido` int(255) NOT NULL,
  `estatus` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productospedidos`
--

LOCK TABLES `productospedidos` WRITE;
/*!40000 ALTER TABLE `productospedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `productospedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productosventa`
--

DROP TABLE IF EXISTS `productosventa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productosventa` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `subtitulo` varchar(100) NOT NULL,
  `detalles` varchar(2000) NOT NULL,
  `estatus` int(1) NOT NULL,
  `stock` int(255) DEFAULT NULL,
  `stockminimo` int(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `preciounitario` decimal(10,2) NOT NULL,
  `preciomayoreo` decimal(10,2) NOT NULL,
  `cantidadmayoreo` int(255) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `talla` varchar(20) DEFAULT 'Unitalla',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productosventa`
--

LOCK TABLES `productosventa` WRITE;
/*!40000 ALTER TABLE `productosventa` DISABLE KEYS */;
INSERT INTO `productosventa` VALUES (2,'Taco de Recto','Comida','Comida rica',1,54,10,'1',30.00,25.00,5,10.00,'Unitalla');
/*!40000 ALTER TABLE `productosventa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promociones`
--

DROP TABLE IF EXISTS `promociones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promociones` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `medio` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `estatus` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promociones`
--

LOCK TABLES `promociones` WRITE;
/*!40000 ALTER TABLE `promociones` DISABLE KEYS */;
/*!40000 ALTER TABLE `promociones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategorias`
--

DROP TABLE IF EXISTS `subcategorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategorias` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `subcategoria` varchar(255) NOT NULL,
  `medio` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategorias`
--

LOCK TABLES `subcategorias` WRITE;
/*!40000 ALTER TABLE `subcategorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `subcategorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategoriasasociadasventa`
--

DROP TABLE IF EXISTS `subcategoriasasociadasventa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategoriasasociadasventa` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `idproducto` int(255) NOT NULL,
  `subcategoria` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategoriasasociadasventa`
--

LOCK TABLES `subcategoriasasociadasventa` WRITE;
/*!40000 ALTER TABLE `subcategoriasasociadasventa` DISABLE KEYS */;
/*!40000 ALTER TABLE `subcategoriasasociadasventa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidopaterno` varchar(100) NOT NULL,
  `apellidomaterno` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` int(2) NOT NULL,
  `estatus` int(1) NOT NULL,
  `medio` longblob NOT NULL,
  `intentos_fallidos` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `bloqueado_hasta` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (7,'Juan Carlos','Santoyo','Navarro','utm23090764@utma.edu.mx','$2y$10$x4WBSGPd2wR9z9Gak2qkrexnSBU7BH6pPvYr.TpQajEKOaZYhAqXy',1,1,'',0,NULL),(11,'Itzel','Isaac','RIvera','utm23090687@utma.edu.mx','$2y$10$gHLwH40UYLB2g0XvNzLK2uzKxNrnhPnzxIceWPQMMnvYPGHU.ddpe',1,1,'',0,NULL),(13,'Juan Carlos','Santoyo','Navarro','juancarlossantoyo3112@gmail.com','$2y$10$XA6q6IUV035KTBzNHI0R5.9fcGERYUbjTE1kDxGkN.RS8/YmY52jq',1,1,'',0,NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `identificador` varchar(255) DEFAULT NULL,
  `titulo` varchar(100) NOT NULL,
  `subtitulo` varchar(100) NOT NULL,
  `detalles` varchar(2000) NOT NULL,
  `cantidad` int(255) DEFAULT NULL,
  `surtido` int(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `mayoreo` varchar(3) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `producto_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES (1,'MIEMPRESA-0000001-JSN','Taco de Recto','Comida','Comida rica',6,0,'1','Si',26.25,0.00,2),(2,'MIEMPRESA-0000002-JSN','Taco de Recto','Comida','Comida rica',7,0,'1','Si',26.25,0.00,2);
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `path` varchar(150) NOT NULL,
  `estatus` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'ecommerce'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-02 15:15:45
