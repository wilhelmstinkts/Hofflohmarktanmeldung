CREATE DATABASE IF NOT EXISTS `d03ce714` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `d03ce714`;

CREATE TABLE IF NOT EXISTS termine (
    id INTEGER NOT NULL AUTO_INCREMENT,
    datum DATE NOT NULL,
    anmeldeschluss DATE,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS orte (
    id INTEGER NOT NULL AUTO_INCREMENT,
    strasse mediumtext NOT NULL,
    hausnummer tinytext NOT NULL,
    koordinaten point,    
    PRIMARY KEY (id),
    UNIQUE INDEX idx_orte_strasse_hausnummer (strasse(50), hausnummer(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS teilnahmen (
    id INTEGER NOT NULL AUTO_INCREMENT,
    termin_id INTEGER NOT NULL,
    ort_id INTEGER NOT NULL,
    email text NOT NULL,
    absagecode text NOT NULL,
    angemeldet_am DATE NOT NULL,
    abgemeldet_am DATE,
    PRIMARY KEY (id),
    INDEX idx_teilnahmen_termin_id (termin_id),
    FOREIGN KEY (termin_id)
        REFERENCES termine(id)
        ON DELETE CASCADE,
    INDEX idx_teilnahmen_ort_id (ort_id),
    FOREIGN KEY (ort_id)
        REFERENCES orte(id)
        ON DELETE CASCADE,
    UNIQUE INDEX idx_teilnahmen_absagecode (absagecode(50))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;

INSERT INTO termine (datum, anmeldeschluss) VALUES ('2023-06-04', '2023-05-21');