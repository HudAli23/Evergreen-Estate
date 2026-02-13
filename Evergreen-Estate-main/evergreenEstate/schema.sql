-- Evergreen Estate starter schema
-- Database: realestate
-- Note: types/constraints are conservative defaults inferred from PHP usage.

CREATE DATABASE IF NOT EXISTS realestate;
USE realestate;

-- Agents
CREATE TABLE IF NOT EXISTS agenttable (
  agentID INT NOT NULL AUTO_INCREMENT,
  agency VARCHAR(255) NOT NULL,
  contactnumber VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL,
  agentfirstname VARCHAR(100) NOT NULL,
  agentsurname VARCHAR(100) NOT NULL,
  PRIMARY KEY (agentID)
) ENGINE=InnoDB;

-- Clients
CREATE TABLE IF NOT EXISTS client (
  clientID INT NOT NULL AUTO_INCREMENT,
  clientfirstname VARCHAR(100) NOT NULL,
  clientsurname VARCHAR(100) NOT NULL,
  contactnumber VARCHAR(50) NOT NULL,
  email VARCHAR(255) NOT NULL,
  agentID INT NOT NULL,
  PRIMARY KEY (clientID),
  KEY idx_client_agentID (agentID),
  CONSTRAINT fk_client_agent
    FOREIGN KEY (agentID) REFERENCES agenttable(agentID)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Properties
CREATE TABLE IF NOT EXISTS property (
  propertyID INT NOT NULL AUTO_INCREMENT,
  agentID INT NOT NULL,
  propertyname VARCHAR(255) NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  bedrooms INT NOT NULL,
  bathrooms INT NOT NULL,
  size VARCHAR(50) NOT NULL,
  description TEXT NOT NULL,
  propertyowner VARCHAR(255) NOT NULL,
  propertyimage VARCHAR(512) NULL,
  PRIMARY KEY (propertyID),
  KEY idx_property_agentID (agentID),
  CONSTRAINT fk_property_agent
    FOREIGN KEY (agentID) REFERENCES agenttable(agentID)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Appointments / showings
CREATE TABLE IF NOT EXISTS showings (
  appointmentID INT NOT NULL AUTO_INCREMENT,
  appointmentdate DATE NOT NULL,
  appointmenttime TIME NOT NULL,
  clientdecision CHAR(1) NOT NULL DEFAULT 'U',
  clientID INT NOT NULL,
  propertyID INT NOT NULL,
  agentID INT NOT NULL,
  PRIMARY KEY (appointmentID),
  KEY idx_showings_clientID (clientID),
  KEY idx_showings_propertyID (propertyID),
  KEY idx_showings_agentID (agentID),
  CONSTRAINT fk_showings_client
    FOREIGN KEY (clientID) REFERENCES client(clientID)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_showings_property
    FOREIGN KEY (propertyID) REFERENCES property(propertyID)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_showings_agent
    FOREIGN KEY (agentID) REFERENCES agenttable(agentID)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT chk_clientdecision
    CHECK (clientdecision IN ('U','Y','N'))
) ENGINE=InnoDB;