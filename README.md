# Evergreen Estate (Real Estate Management Web App)

Evergreen Estate is a **PHP + MySQL** CRUD web app for a small real-estate workflow: managing **agents**, **clients**, **properties** (with images), and **appointments** between them.

## Why this project (portfolio framing)

This project demonstrates:

- Building a **multi-entity CRUD** system with relationships (agent ↔ client ↔ property ↔ appointment)
- Handling **file uploads** (property images)
- Creating **data-driven pages** with create + list + update + delete flows
- Practical UI using Bootstrap to keep the focus on functionality and data modeling

## Key contributions (what I built)

- Implemented end-to-end **CRUD pages** for properties, agents, clients, and appointments
- Designed and integrated a **relational data model** (agents/clients/properties/appointments)
- Added **image upload** and safe cleanup on delete (property image removed when property is deleted)
- Added basic **data integrity guardrails** (prevent deleting an agent referenced by clients/properties)

## Features

- **Agents**
  - Create, view, update, delete agents
  - Deletion is **blocked** when the agent is referenced by clients or properties
- **Clients**
  - Create, view, update, delete clients
  - Assign each client to an agent
- **Properties**
  - Create, view, update, delete properties
  - Upload and display a property image
- **Appointments**
  - Create, view, update, delete appointments
  - Link appointment to a client + property + agent
  - Track a simple client decision state: **U / Y / N**

## Tech Stack

- **Backend**: PHP (mysqli)
- **Database**: MySQL
- **Frontend**: HTML/CSS + Bootstrap (CDN)

## Pages / Entry Points

This project is page-driven (no `index.html` in this repo snapshot). Start at:

- `php/property.php` — properties (create + list + update + delete)
- `php/agent.php` — agents (create + list + update + delete)
- `php/client.php` — clients (create + list + update + delete)
- `php/appointment.php` — appointments (create + list + update + delete)

## Data Model (tables + columns)

These table/column names come directly from the PHP code.

### `agenttable`

- `agentID` (PK)
- `agency`
- `contactnumber`
- `email`
- `agentfirstname`
- `agentsurname`

### `client`

- `clientID` (PK)
- `clientfirstname`
- `clientsurname`
- `contactnumber`
- `email`
- `agentID` (FK → `agenttable.agentID`)

### `property`

- `propertyID` (PK)
- `agentID` (FK → `agenttable.agentID`)
- `propertyname`
- `price`
- `bedrooms`
- `bathrooms`
- `size`
- `description`
- `propertyowner`
- `propertyimage` (stored as a relative path like `images/<filename>`)

### `showings` (appointments)

- `appointmentID` (PK)
- `appointmentdate`
- `appointmenttime`
- `clientdecision` (`U`, `Y`, `N`)
- `clientID` (FK → `client.clientID`)
- `propertyID` (FK → `property.propertyID`)
- `agentID` (FK → `agenttable.agentID`)

## Local Setup (Windows / XAMPP or WAMP)

### 1) Put the project under your web root

- XAMPP: `C:\xampp\htdocs\evergreenEstate`
- WAMP: `C:\wamp64\www\evergreenEstate`

### 2) Create the database

Create a MySQL database named: **`realestate`**

Then create the tables listed above.

This repo includes a starter schema:

- `schema.sql` — creates the tables + basic foreign keys used by the app

### 3) Database credentials

The PHP scripts currently connect with:

- host: `localhost`
- user: `root`
- password: (empty)
- database: `realestate`

If yours differ, update the connection lines at the top of the pages/handlers in `php/`.

### 4) Run

Start **Apache** + **MySQL**, then open:

- `http://localhost/evergreenEstate/php/property.php`

## Demo Script (quick walkthrough)

If you’re demoing this live (portfolio / interview), a clean flow is:

1. **Create an agent** (`php/agent.php`)
2. **Create a client** and assign them to that agent (`php/client.php`)
3. **Create a property** and upload an image (`php/property.php`)
4. **Create an appointment** linking the client + property + agent (`php/appointment.php`)
5. Update the appointment decision to **Yes/No/Undecided**
6. Attempt to delete the agent and show that deletion is blocked when referenced (data integrity)

## Image Upload Notes

- Images are uploaded into `images/`
- The database stores `propertyimage` as a relative path like `images/<filename>`
- Deleting a property also deletes its image file (when present)

## Folder Structure

- `php/` — pages + CRUD handlers
- `images/` — static images + uploaded property images
- `uploads/` — additional uploaded images (present in repo)
- `updateproperty_files/` — saved build/artifact files (not required to run the PHP app)

## Limitations / Improvements (portfolio honesty)

- **Centralize DB configuration**: connection settings are duplicated across scripts
- **Security**: add CSRF protection, stronger upload validation, and stricter server-side input validation
- **Auth & roles**: admin vs agent, protected actions, audit logs
- **Better UX**: search/filter properties, pagination, and richer property details pages

## Screenshots

Add screenshots to: `docs/screenshots/`

- `docs/screenshots/property.png`
- `docs/screenshots/appointments.png`
- `docs/screenshots/agents.png`
