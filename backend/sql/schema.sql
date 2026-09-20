-- ============================================================
-- MT - Esquema inicial de base de datos
-- Motor: MariaDB
-- Asume que la base de datos 'mt' ya existe y está seleccionada.
-- ============================================================

-- ------------------------------------------------------------
-- Tabla: currencies
-- Catálogo de monedas soportadas.
-- El campo 'symbol' es referencia interna; en la UI se usan SVGs.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS currencies (
  code      CHAR(5)      NOT NULL,
  name      VARCHAR(50)  NOT NULL,
  symbol    VARCHAR(10)  NOT NULL,
  decimals  TINYINT      NOT NULL DEFAULT 2,
  PRIMARY KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed inicial de monedas
INSERT INTO currencies (code, name, symbol, decimals) VALUES
  ('VES',  'Bolívar',         'Bs',   2),
  ('USD',  'Dólar',           '$',    2),
  ('EUR',  'Euro',            '€',    2),
  ('GBP',  'Libra esterlina', '£',    2),
  ('USDT', 'Tether',          'USDT', 8),
  ('BTC',  'Bitcoin',         'BTC',  8);

-- ------------------------------------------------------------
-- Tabla: users
-- Usuarios de la aplicación.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id             INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  username       VARCHAR(50)    NOT NULL,
  email          VARCHAR(120)   NOT NULL,
  passwordHash   VARCHAR(255)   NOT NULL,
  baseCurrency   CHAR(5)        NOT NULL,
  role           VARCHAR(20)    NOT NULL DEFAULT 'client',
  status         BOOLEAN        NOT NULL DEFAULT TRUE,
  lastLoginAt    DATETIME       NULL,
  createdAt      DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updatedAt      DATETIME       NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username),
  UNIQUE KEY uq_users_email (email),
  KEY idx_users_baseCurrency (baseCurrency),
  CONSTRAINT fk_users_baseCurrency
    FOREIGN KEY (baseCurrency) REFERENCES currencies (code)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: activityLog
-- Registro de acciones de usuario para auditoría.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS activityLog (
  id          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  userId      INT UNSIGNED     NULL,
  action      VARCHAR(50)      NOT NULL,
  ip          VARCHAR(45)      NULL,
  userAgent   VARCHAR(255)     NULL,
  detail      JSON             NULL,
  createdAt   DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_activityLog_userId (userId),
  KEY idx_activityLog_action (action),
  KEY idx_activityLog_createdAt (createdAt),
  CONSTRAINT fk_activityLog_userId
    FOREIGN KEY (userId) REFERENCES users (id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
