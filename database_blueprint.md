# Database Blueprint — Milestone 2

## 1. Overview
This document outlines the planned SQL database structure for Milestone 3. Milestone 2 does not require any SQL queries or backend code. This blueprint defines the tables, relationships, and CRUD operations our future backend will use.

## 2. Planned Database Tables

### 2.1 users Table
| Column Name   | Type      | Notes                               |
|---------------|-----------|--------------------------------------|
| user_id       | INT       | Primary Key (auto-increment)         |
| username      | VARCHAR   | Unique — used for login              |
| password_hash | VARCHAR   | Hashed password                      |
| created_at    | DATETIME  | Timestamp of account creation        |

### 2.2 player_stats Table
| Column Name | Type   | Notes                                   |
|-------------|--------|------------------------------------------|
| stat_id     | INT    | Primary Key (auto-increment)             |
| user_id     | INT    | Foreign Key → users.user_id              |
| wins        | INT    | Total wins                               |
| losses      | INT    | Total losses                             |
| draws       | INT    | Total draws                              |
| win_rate    | FLOAT  | Calculated (wins / total games)          |

## 3. CRUD Mapping (Front-End → Future SQL)
| Front-End Action | Page             | Future SQL Operation | Description                                   |
|------------------|------------------|----------------------|-----------------------------------------------|
| Register Button  | register.html    | INSERT users         | Creates a new user account                    |
| Login Button     | login.html       | SELECT users         | Validates username + password_hash            |
| Game Completion  | play.html        | UPDATE player_stats  | Updates wins/losses/draws + recalculates rate |
| Leaderboard View | leaderboard.html | SELECT player_stats  | Retrieves sorted leaderboard data             |

## 4. Form Field Audit
login.html fields:
- name="username"
- name="password"

register.html fields:
- name="username"
- name="password"
- name="confirm_password"

confirm_password is only for front-end validation and will not be stored in the database.

## 5. Summary
This blueprint defines the two relational tables (users and player_stats), their columns and relationships, the CRUD operations each page will eventually trigger, and confirmation that front-end form fields align with backend naming conventions.
