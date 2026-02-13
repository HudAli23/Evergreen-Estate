# Evergreen Estate — Software Design Document

## 1. Overview

**Evergreen Estate** is a PHP + MySQL CRUD web application for real estate workflow management. It manages **agents**, **clients**, **properties**, and **appointments** (showings) with relational integrity, file uploads for property images, and data-driven create/list/update/delete flows.

| Attribute | Value |
|-----------|--------|
| **Project type** | Web application (server-rendered) |
| **Backend** | PHP (mysqli) |
| **Database** | MySQL (`realestate`) |
| **Frontend** | HTML/CSS + Bootstrap (CDN) |
| **Entry points** | Page-driven: `property.php`, `agent.php`, `client.php`, `appointment.php` |

---

## 2. Goals and Scope

- **Primary goal**: Provide a small real-estate office with a working CRUD system for core entities and their relationships.
- **Scope**: Agent and client management, property listings with images, and appointment (showing) scheduling with a simple client decision state (U/Y/N).
- **Out of scope**: Authentication, roles, search/pagination, API layer, mobile app.

---

## 3. System Context

- **Users**: Office staff (implicit single-role).
- **External systems**: None; standalone LAMP-style stack.
- **Deployment**: Typically XAMPP/WAMP on Windows; PHP under Apache, MySQL for persistence.

---

## 4. Architecture

### 4.1 High-Level Architecture

- **Presentation**: PHP scripts that output HTML; each main entity has one "hub" page (e.g. `property.php`) and dedicated create/read/update/delete scripts (e.g. `propertycreate.php`, `propertyread.php`, `propertyupdate.php`, `propertydelete.php`).
- **Business logic**: Inline in PHP (validation, redirects, file handling); no separate service layer.
- **Data access**: Direct `mysqli` calls in each script; no ORM or shared repository abstraction.
- **Persistence**: MySQL database `realestate`; file system for uploaded property images (stored under `images/`).

### 4.2 Page and Script Layout

| Entity | Hub page | Create | Read (list/detail) | Update | Delete |
|--------|----------|--------|--------------------|--------|--------|
| Agent | `agent.php` | `agentcreate.php` | `agentread.php` | `agentupdate.php` | `agentdelete.php` |
| Client | (via `client.php`) | `clientcreate.php` | `clientread.php` | `clientupdate.php` | `clientdelete.php` |
| Property | `property.php` | `propertycreate.php` | `propertyread.php` | `propertyupdate.php` | `propertydelete.php` |
| Appointment | `appointment.php` | `appointmentcreate.php` | `appointmentread.php` | `appointmentupdate.php` | `appointmentdelete.php` |

Shared assets: `lad.css` for styling; Bootstrap via CDN.

### 4.3 Data Model (Conceptual)

- **Agent**: Independent entity; identified by `agentID`. Referenced by clients, properties, and appointments.
- **Client**: Belongs to one agent (`agentID`). Referenced by appointments.
- **Property**: Belongs to one agent (`agentID`); optional image path. Referenced by appointments.
- **Appointment (Showings)**: Links one client, one property, one agent; has date, time, and `clientdecision` (U/Y/N).

Constraints (enforced by DB):

- Cannot delete an agent if any client or property references them (RESTRICT).
- Cannot delete a client/property/agent if referenced by an appointment (RESTRICT).
- Application logic may additionally block deletes and show user-friendly messages.

---

## 5. Key Design Decisions

| Decision | Rationale |
|----------|------------|
| Page-per-action (create/read/update/delete) | Simple, clear URLs and form actions; easy to follow for a small team. |
| Direct mysqli in each script | No framework; minimal dependencies; full control over SQL. |
| Property image as file path in DB | Simple storage model; delete property implies delete file when implemented. |
| Foreign keys with RESTRICT | Protects referential integrity; prevents orphaned clients/properties/appointments. |
| Bootstrap for UI | Fast, consistent layout and forms without custom CSS framework. |

---

## 6. Data Dictionary (Summary)

- **agenttable**: `agentID` (PK), `agency`, `contactnumber`, `email`, `agentfirstname`, `agentsurname`.
- **client**: `clientID` (PK), `clientfirstname`, `clientsurname`, `contactnumber`, `email`, `agentID` (FK).
- **property**: `propertyID` (PK), `agentID` (FK), `propertyname`, `price`, `bedrooms`, `bathrooms`, `size`, `description`, `propertyowner`, `propertyimage`.
- **showings**: `appointmentID` (PK), `appointmentdate`, `appointmenttime`, `clientdecision` (U/Y/N), `clientID`, `propertyID`, `agentID` (FKs).

---

## 7. Security and Operations (Considerations)

- **Credentials**: DB connection (host, user, password, database) are duplicated across scripts; centralizing in a single config is recommended.
- **Input**: Server-side validation and prepared statements should be used for all user input; upload validation (type, size) for images.
- **CSRF**: Not implemented; adding tokens for state-changing operations is recommended for production.

---

## 8. Diagram Reference

- **How the project works**: See `Evergreen-Estate-ER.puml` (PlantUML). The diagram is an **activity diagram** showing user flow: open hub page → choose action (Create / Read / Update / Delete) → PHP script runs (MySQL + optional file upload) → redirect or display result. Use a PlantUML renderer to generate the image.
