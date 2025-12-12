-- Creando base de datos completa con datos de ejemplo
CREATE DATABASE IF NOT EXISTS galeria;
USE galeria;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255) NULL
);

-- Tabla de imágenes
CREATE TABLE IF NOT EXISTS imagenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    url VARCHAR(255) NOT NULL,
    categoria_id INT NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de favoritos vinculada a usuarios
CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_imagen INT NOT NULL,
    fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_imagen) REFERENCES imagenes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorito (id_usuario, id_imagen)
);

-- Insertar categorías
INSERT INTO categorias (nombre, descripcion) VALUES
('naturaleza', 'Fotos de paisajes, flora, fauna, etc.'),
('arquitectura', 'Fotos de edificios, puentes y estructuras.'),
('personas', 'Retratos y fotografías de personas.'),
('tecnologia', 'Imágenes de dispositivos y avances tecnológicos.');

-- Insertar imágenes de ejemplo
INSERT INTO imagenes (titulo, descripcion, url, categoria_id) VALUES
-- Naturaleza
('Bosque de pinos', 'Hermoso paisaje de bosque con pinos', 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400', 1),
('Montañas nevadas', 'Vista panorámica de montañas con nieve', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400', 1),
('Lago al atardecer', 'Reflejo dorado en un lago tranquilo', 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=400', 1),
('Cascada tropical', 'Cascada rodeada de vegetación', 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=400', 1),
('Flores silvestres', 'Campo de flores coloridas', 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?w=400', 1),

-- Arquitectura
('Edificio moderno', 'Rascacielos con diseño contemporáneo', 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?w=400', 2),
('Catedral gótica', 'Arquitectura gótica antigua', 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400', 2),
('Puente colgante', 'Estructura de puente al amanecer', 'https://images.unsplash.com/photo-1470259078422-826894b933aa?w=400', 2),
('Casa minimalista', 'Diseño arquitectónico minimalista', 'https://images.unsplash.com/photo-1494526585095-c41746248156?w=400', 2),
('Torre de reloj', 'Torre histórica en plaza central', 'https://images.unsplash.com/photo-1533929736458-ca588d08c8be?w=400', 2),

-- Personas
('Retrato profesional', 'Persona en entorno corporativo', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400', 3),
('Familia en el parque', 'Momento familiar al aire libre', 'https://images.unsplash.com/photo-1511895426328-dc8714191300?w=400', 3),
('Músico en concierto', 'Artista tocando guitarra', 'https://images.unsplash.com/photo-1511735111819-9a3f7709049c?w=400', 3),
('Niños jugando', 'Infancia y diversión', 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?w=400', 3),
('Deportista corriendo', 'Atleta en acción', 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?w=400', 3),

-- Tecnología
('Laptop y código', 'Programación y desarrollo', 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=400', 4),
('Robot futurista', 'Inteligencia artificial moderna', 'https://images.unsplash.com/photo-1527430253228-e93688616381?w=400', 4),
('Circuitos electrónicos', 'Placa de circuitos detallada', 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=400', 4),
('Smartphone', 'Teléfono inteligente moderno', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400', 4),
('Red de datos', 'Conexiones digitales globales', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=400', 4);

-- Insertar usuarios de prueba (contraseñas sin encriptar para ejemplo)
INSERT INTO usuarios (username, password) VALUES 
('admin', '12345'),
('usuario1', 'pass123');

-- Insertar algunos favoritos de ejemplo
INSERT IGNORE INTO favoritos (id_usuario, id_imagen) VALUES
(1, 1),
(1, 5),
(1, 8);
