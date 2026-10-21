-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-03-2023 a las 04:10:36
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- TABLA DE USUARIOS
CREATE TABLE `usuarios` (
  `usuario` varchar(10) NOT NULL,
  `clave` varchar(15) NOT NULL,
  `rol` int(11) NOT NULL,
  PRIMARY KEY (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `usuarios` VALUES ('admin','12345',1),('empleado','12345',2);


-- TABLA DE INVENTARIO
CREATE TABLE `inventario` (
  `codigo_producto` varchar(20) NOT NULL,
  `descripcion_producto` varchar(200) DEFAULT '',
  `marca_producto` varchar(40) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock_producto` int(11) NOT NULL,
  PRIMARY KEY (`codigo_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- TABLA DE VENTAS
CREATE TABLE `ventas` (
  `id_venta` int(100) NOT NULL AUTO_INCREMENT,
  `codigo_producto` varchar(20) NOT NULL,
  `cantidad` int(100) NOT NULL,
  `total_venta` decimal(10,2) NOT NULL,
  `forma_pago` varchar(20) NOT NULL,
  `fecha_venta` DATE NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `codigo_producto` (`codigo_producto`),
  CONSTRAINT `fk_ventas_inventario` FOREIGN KEY (`codigo_producto`) REFERENCES `inventario` (`codigo_producto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- TABLA DE PROVEEDORES
CREATE TABLE `proveedores` (
  `id_proveedor` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre_empresa` VARCHAR(100) NOT NULL,
  `nombre_contacto` VARCHAR(100),
  `telefono` VARCHAR(20) NOT NULL,
  `direccion` TEXT,
  `rif` VARCHAR(30) UNIQUE,
  `estatus` ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
  `creado_en` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- TABLA DE COMPRAS Y DETALLES DE COMPRAS
-- 1. Tabla principal de Compras
CREATE TABLE `compras` (
  `id_compra` INT AUTO_INCREMENT,
  `id_proveedor` INT NOT NULL,
  `numero_factura` VARCHAR(50) NOT NULL,
  `fecha_compra` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `total_compra` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_compra`),
  KEY `idx_proveedor` (`id_proveedor`),
  CONSTRAINT `fk_compras_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabla de Detalles de la Compra
CREATE TABLE `detalle_compras` (
  `id_detalle` INT AUTO_INCREMENT,
  `id_compra` INT NOT NULL,
  `codigo_producto` VARCHAR(20) NOT NULL, 
  `cantidad` INT NOT NULL,
  `precio_costo` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `idx_compra` (`id_compra`),
  KEY `idx_codigo_producto` (`codigo_producto`),
  CONSTRAINT `fk_detalle_compras` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE
  -- Nota: Quitamos la restricción estricta de FK en detalle_inventario para permitir que el trigger cree el producto nuevo si no existe al momento de la compra
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- TRIGGERS -----------------------------------------------------------------------------------------------------------

-- 1. Trigger inteligente para Compras (Actualiza stock si existe, o crea el producto si es nuevo)
DELIMITER //

CREATE TRIGGER `trigger_aumentar_stock_compra`
AFTER INSERT ON `detalle_compras`
FOR EACH ROW
BEGIN
    IF EXISTS (SELECT 1 FROM `inventario` WHERE `codigo_producto` = NEW.codigo_producto) THEN
        -- Si el producto ya existe, suma al stock y actualiza el costo
        UPDATE `inventario`
        SET `stock_producto` = `stock_producto` + NEW.cantidad,
            `precio_compra` = NEW.precio_costo 
        WHERE `codigo_producto` = NEW.codigo_producto;
    ELSE
        -- Si el producto es nuevo, lo registra automáticamente en el inventario
        INSERT INTO `inventario` (`codigo_producto`, `descripcion_producto`, `marca_producto`, `precio_compra`, `precio_venta`, `stock_producto`)
        VALUES (
            NEW.codigo_producto, 
            'Nuevo Producto (Registrado por Compra)', 
            'Genérica', 
            NEW.precio_costo, 
            NEW.precio_costo * 1.30, -- Precio de venta sugerido con 30% de margen
            NEW.cantidad
        );
    END IF;
END //

DELIMITER ;




DELIMITER //

CREATE TRIGGER `trigger_ajustar_stock_compra_update`
AFTER UPDATE ON `detalle_compras`
FOR EACH ROW
BEGIN
    IF OLD.codigo_producto = NEW.codigo_producto THEN
        -- Mismo producto: solo ajusta la diferencia entre la cantidad vieja y la nueva
        UPDATE `inventario`
        SET `stock_producto` = `stock_producto` + (NEW.cantidad - OLD.cantidad),
            `precio_compra` = NEW.precio_costo
        WHERE `codigo_producto` = NEW.codigo_producto;
    ELSE
        -- Cambiaron el producto: le resta la cantidad vieja al producto viejo
        UPDATE `inventario`
        SET `stock_producto` = `stock_producto` - OLD.cantidad
        WHERE `codigo_producto` = OLD.codigo_producto;

        -- Y le suma la cantidad nueva al producto nuevo (o lo crea si no existe)
        IF EXISTS (SELECT 1 FROM `inventario` WHERE `codigo_producto` = NEW.codigo_producto) THEN
            UPDATE `inventario`
            SET `stock_producto` = `stock_producto` + NEW.cantidad,
                `precio_compra` = NEW.precio_costo
            WHERE `codigo_producto` = NEW.codigo_producto;
        ELSE
            INSERT INTO `inventario` (`codigo_producto`, `descripcion_producto`, `marca_producto`, `precio_compra`, `precio_venta`, `stock_producto`)
            VALUES (
                NEW.codigo_producto,
                'Nuevo Producto (Registrado por Compra)',
                'Genérica',
                NEW.precio_costo,
                NEW.precio_costo * 1.30,
                NEW.cantidad
            );
        END IF;
    END IF;
END //

DELIMITER ;




-- 2. Trigger para Ventas (Resta stock automáticamente al realizar una venta)
DELIMITER //

CREATE TRIGGER `restar_stock_tras_venta`
AFTER INSERT ON `ventas`
FOR EACH ROW
BEGIN
    UPDATE `inventario`
    SET `stock_producto` = `stock_producto` - NEW.cantidad
    WHERE `codigo_producto` = NEW.codigo_producto;
END //

DELIMITER ;

COMMIT;