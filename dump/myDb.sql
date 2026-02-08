USE asistencia_alumno;

/* =========================
   TABLAS BASE (SIN FK)
   ========================= */

CREATE TABLE jornadas (
    Id_Jornada VARCHAR(4) PRIMARY KEY NOT NULL, -- J-1
    Tipo_Jornada VARCHAR(13) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE grados (
    Id_Grado VARCHAR(4) PRIMARY KEY NOT NULL, -- G-01 a G-99
    Nombre_Grado VARCHAR(70) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE secciones (
    Id_Seccion VARCHAR(4) PRIMARY KEY NOT NULL, -- S-1 a S-9
    Nombre_Seccion VARCHAR(2) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Parentescos (
    Id_Parentesco VARCHAR(4) PRIMARY KEY NOT NULL,
    parentesco VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE lectores (
    Id_Lector INT AUTO_INCREMENT PRIMARY KEY,
    Ubicacion VARCHAR(50) NOT NULL,
    Tipo_Lector VARCHAR(30) NOT NULL
) ENGINE=InnoDB;

/* =========================
   TABLA DE USUARIOS
   ========================= */

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100),
    usuario VARCHAR(50),
    correo VARCHAR(100),
    contrasena VARCHAR(255),
    rol VARCHAR(50),
    token_recuperacion VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =========================
   TABLAS PRINCIPALES
   ========================= */

CREATE TABLE alumnos (
    Id_Alumno VARCHAR(7) PRIMARY KEY NOT NULL, -- Código estudiante
    Nombres_Alumno VARCHAR(50) NOT NULL,
    Apellido1_Alumno VARCHAR(15) NOT NULL,
    Apellido2_Alumno VARCHAR(15),
    Genero ENUM('M','F') NOT NULL,
    Telefono_Alumno VARCHAR(15),
    Direccion_Alumno VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE registro_alumnos (
    Id_Registro_Alumno VARCHAR(6) PRIMARY KEY NOT NULL, -- R-1 a R-9999
    Id_Alumno VARCHAR(7) NOT NULL,
    Id_Jornada VARCHAR(4) NOT NULL,
    Id_Grado VARCHAR(4) NOT NULL,
    Id_Seccion VARCHAR(4),

    CONSTRAINT fk_registro_alumno
        FOREIGN KEY (Id_Alumno) REFERENCES alumnos(Id_Alumno),

    CONSTRAINT fk_registro_jornada
        FOREIGN KEY (Id_Jornada) REFERENCES jornadas(Id_Jornada),

    CONSTRAINT fk_registro_grado
        FOREIGN KEY (Id_Grado) REFERENCES grados(Id_Grado),

    CONSTRAINT fk_registro_seccion
        FOREIGN KEY (Id_Seccion) REFERENCES secciones(Id_Seccion)
) ENGINE=InnoDB;

CREATE TABLE detalle_alumnos (
    Id_Detalle VARCHAR(6) PRIMARY KEY NOT NULL,
    Id_Registro_Alumno VARCHAR(6) NOT NULL,
    Cui_Encargado VARCHAR(13) NOT NULL,
    Nombres_Encargado VARCHAR(50) NOT NULL,
    Apellido1_Encargado VARCHAR(15) NOT NULL,
    Apellido2_Encargado VARCHAR(15),
    Telefono_Encargado VARCHAR(15) NOT NULL,
    Direccion_Encargado VARCHAR(100) NOT NULL,
    Id_Parentesco VARCHAR(4) NOT NULL,

    CONSTRAINT fk_detalle_registro
        FOREIGN KEY (Id_Registro_Alumno)
        REFERENCES registro_alumnos(Id_Registro_Alumno),

    CONSTRAINT fk_detalle_parentesco
        FOREIGN KEY (Id_Parentesco)
        REFERENCES Parentescos(Id_Parentesco)
) ENGINE=InnoDB;

/* =========================
   ASISTENCIAS
   ========================= */

CREATE TABLE asistencias (
    Id_Asistencia INT AUTO_INCREMENT PRIMARY KEY,
    Id_Detalle VARCHAR(6) NOT NULL,
    Fecha_Registro DATE NOT NULL,
    Hora_Entrada TIME NOT NULL,
    Hora_Salida TIME,
    Registro_Asistencia BIT,
    Justificacion TEXT,
    Id_Lector INT NOT NULL,

    CONSTRAINT fk_asistencia_detalle
        FOREIGN KEY (Id_Detalle) REFERENCES detalle_alumnos(Id_Detalle),

    CONSTRAINT fk_asistencia_lector
        FOREIGN KEY (Id_Lector) REFERENCES lectores(Id_Lector)
) ENGINE=InnoDB;

/* =========================
   HORARIOS (NUEVO MÓDULO)
   ========================= */

CREATE TABLE horarios (
    Id_Horario INT AUTO_INCREMENT PRIMARY KEY,
    Id_Jornada VARCHAR(4) NOT NULL,
    Fecha DATE NOT NULL,
    Hora_Entrada TIME NOT NULL,
    Hora_Salida TIME NOT NULL,
    Observaciones TEXT NULL,
    Estado BIT DEFAULT 1,

    CONSTRAINT FK_Horario_Jornada
        FOREIGN KEY (Id_Jornada) REFERENCES jornadas(Id_Jornada),

    UNIQUE (Id_jornada, Fecha)
) ENGINE=InnoDB;

/* =========================
   LOGS DE ACTIVIDADES
   ========================= */

CREATE TABLE logs_actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    actividad VARCHAR(255) NOT NULL,
    ip_usuario VARCHAR(45) NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_usuario (usuario_id),

    CONSTRAINT fk_logs_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =========================
   DATOS INICIALES (SEED)
   ========================= */

INSERT INTO Parentescos (Id_Parentesco, parentesco) VALUES
('P-1','Mamá'),
('P-2','Papá'),
('P-3','Tío');

INSERT INTO lectores (Ubicacion, Tipo_Lector)
VALUES ('Puerta Principal', 'COM-5970');

INSERT INTO usuarios (nombre_completo, correo, rol, usuario, contrasena)
VALUES (
    'Administrador General',
    'admin@ejemplo.com',
    'admin',
    'admin',
    SHA2('admin123', 512)
);
