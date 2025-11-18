CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50)
);
CREATE TABLE imagenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100),
    descripcion TEXT,
    url VARCHAR(255),
    categoria_id INT,
    fecha_subida DATETIME,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    imagen_id INT,
    fecha DATETIME,
    FOREIGN KEY (imagen_id) REFERENCES imagenes(id)
);
