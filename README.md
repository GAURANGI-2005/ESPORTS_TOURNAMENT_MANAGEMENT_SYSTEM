# 🎮 Esports Tournament Management System

A full-stack web-based Database Management System project built for managing
esports tournaments, players, teams, coaches, matches, statistics, viewers,
and ticket bookings.

Built with **PHP**, **MySQL**, **HTML**, and **CSS** as a DBMS Mini Project.

---

## 📌 Project Overview

The Esports Tournament Management System is a web application that allows
administrators to manage all aspects of an esports tournament — from player
and team registration to match statistics, venue tracking, sponsor management,
prize pool analytics, and ticket revenue reporting.

The system demonstrates core DBMS concepts including:
- Normalized relational database design (1NF, 2NF, 3NF)
- SQL operations — SELECT, INSERT, JOIN, GROUP BY, ORDER BY, subqueries, SUM
- Transaction Management with COMMIT and ROLLBACK
- ACID Properties applied to real esports scenarios
- MongoDB implementation as a NoSQL addon

---

## 🗂️ Project Structure

```

esports-tournament-management/
│
├── index.html # Main dashboard with module cards
│
├── players_menu.html # Players module navigation
├── add_player.php # Add new player form
├── view_players.php # View all players (SELECT *)
├── view_players_full.php # Full player details with role badges
├── players_team.php # Players with team (INNER JOIN)
├── experienced_players.php # Players above average age (Subquery)
├── leaderboard.php # Kill leaderboard (SUM + GROUP BY + ORDER BY)
│
├── teams_menu.html # Teams module navigation
├── add_team.html # Add new team form
├── view_teams.php # View all teams
├── view_teams_full.php # Full team details with ranking badges
├── team_size.php # Players per team (COUNT + GROUP BY)
│
├── view_coaches.php # View all coaches
│
├── match_statistics.php # Match stats (JOIN player + matchstats)
│
├── analytics_menu.html # Analytics module navigation
├── tournament_prize.php # Tournament prize pool (JOIN + SUM)
├── matches_per_tournament.php # Matches per tournament (COUNT + GROUP BY)
├── games_in_tournament.php # Games used in tournaments (JOIN)
├── venue_matches.php # Venue hosting matches (COUNT + ORDER BY DESC)
├── tournament_sponsors.php # Tournament sponsors (JOIN)
├── total_prize_pool.php # Total prize money (SUM aggregate)
│
└── README.md

```

---

## 🗄️ Database Schema

**Database Name:** `esports_db`
**Connection:** `127.0.0.1` | **Port:** `3307` | **User:** `root`

### Tables

| Table | Primary Key | Description |
|---|---|---|
| player | player_id | Stores player details with gamertag, role, nationality |
| team | team_id | Stores team details with region, ranking, founded year |
| coach | coach_id | Stores coach details with experience and nationality |
| match_table | match_id | Links matches to tournaments and venues |
| matchstats | stats_id | Stores per-player kills, assists, damage per match |
| tournament | tournament_id | Tournament details with game and sponsor references |
| venue | venue_id | Venue details with city |
| viewer | viewer_id | Viewer details for ticket management |
| ticket | ticket_id | Ticket records with seat number and price |

### Relationships

```

player ──→ team (team_id FK)
team ──→ coach (coach_id FK)
match_table ──→ tournament (tournament_id FK)
match_table ──→ venue (venue_id FK)
matchstats ──→ player (player_id FK)
matchstats ──→ match_table (match_id FK)
ticket ──→ viewer (viewer_id FK)
ticket ──→ match_table (match_id FK)
tournament ──→ game (game_id FK)
tournament ──→ sponsor (sponsor_id FK)
```

---

## ⚙️ Setup and Installation

### Prerequisites

- XAMPP (Apache + MySQL + PHP)
- PHP 8.0 or above
- MySQL 5.7 or above
- Web browser (Chrome recommended)

### Steps to Run

**1. Clone or download the project**
```bash
git clone https://github.com/yourusername/esports-tournament-management.git
```

**2. Move project to XAMPP htdocs folder**
```bash
# Windows
C:/xampp/htdocs/esports/

# Linux / Mac
/opt/lampp/htdocs/esports/
```

**3. Start XAMPP**
- Open XAMPP Control Panel
- Start **Apache**
- Start **MySQL**

**4. Create the database**
- Open phpMyAdmin at `http://localhost/phpmyadmin`
- Create a new database named `esports_db`
- Import the SQL schema file `esports_db.sql`

**5. Verify database connection**

All PHP files connect using:
```php
$conn = mysqli_connect("127.0.0.1", "root", "", "esports_db", 3307);
```

If your MySQL runs on port 3306 (default), change `3307` to `3306`.

**6. Open the project**
```
http://localhost/esports/index.html
```

---

## 🧩 Modules and Features

### 👤 Player Management
| Page | SQL Used | Description |
|---|---|---|
| add_player.php | INSERT | Add new player with team assignment |
| view_players.php | SELECT * | View all players |
| view_players_full.php | SELECT | Full details with colour-coded role badges |
| players_team.php | INNER JOIN | Players with their team names |
| experienced_players.php | Subquery | Players older than average age |
| leaderboard.php | SUM + GROUP BY + ORDER BY | Kill leaderboard ranked by total kills |

### 🛡️ Team Management
| Page | SQL Used | Description |
|---|---|---|
| add_team.html | INSERT | Register new team |
| view_teams.php | SELECT * | View all teams |
| view_teams_full.php | SELECT | Full team details with ranking badges |
| team_size.php | COUNT + GROUP BY | Number of players per team |

### 🎯 Coach Management
| Page | SQL Used | Description |
|---|---|---|
| view_coaches.php | SELECT * | View all coaches with experience and nationality |

### ⚔️ Match Statistics
| Page | SQL Used | Description |
|---|---|---|
| match_statistics.php | JOIN | Per-match player performance with damage bars |

### 📊 Analytics Module
| Page | SQL Used | Description |
|---|---|---|
| tournament_prize.php | JOIN + SUM | Tournament prize amounts with total |
| matches_per_tournament.php | COUNT + GROUP BY | Matches per tournament |
| games_in_tournament.php | JOIN | Games used in each tournament |
| venue_matches.php | COUNT + GROUP BY + ORDER BY | Venue rankings by matches hosted |
| tournament_sponsors.php | JOIN | Tournament and sponsor pairings |
| total_prize_pool.php | SUM | Grand total prize money across all tournaments |

---

## 🗃️ SQL Concepts Demonstrated

| Concept | Example In Project |
|---|---|
| SELECT | View all players, teams, coaches |
| INSERT | Add player and team via web form |
| JOIN | Players with team names, match stats with player names |
| GROUP BY | Count players per team, matches per tournament |
| ORDER BY | Leaderboard sorted by total kills descending |
| Subquery | Players older than average age |
| SUM | Total kills leaderboard, total prize pool, ticket revenue |
| COUNT | Team size, venue match count, matches per tournament |
| AVG | Average player age (used inside subquery) |

---

## 🔄 Normalisation

The database is normalized up to **Third Normal Form (3NF)**.

| Normal Form | Applied To | Problem Removed |
|---|---|---|
| 1NF | Player, Team | Atomic values — no comma-separated roles or teams in one cell |
| 2NF | MatchStats | player_name removed — depends only on player_id, not full composite key |
| 3NF | Team, Coach | coach_name moved to separate Coach table — no transitive dependency |

---

## 💳 Transaction Management

Transactions are used in five critical operations:

| Transaction | Tables | Purpose |
|---|---|---|
| Ticket Booking | ticket + match_table | Seat deduction and ticket creation must happen atomically |
| Match Stats Entry | matchstats + match_table | Stats and match status must update together |
| Team Registration | coach + team | Coach and team must be created together |
| Tournament Creation | tournament + prizepool | Prize pool must be linked at creation time |
| Player Assignment | player + team | Player and team count must update together |

```sql
-- Example: Ticket Booking Transaction
START TRANSACTION;
  INSERT INTO ticket (viewer_id, match_id, seat_no, price)
    VALUES (1, 1, 'A12', 500);
  UPDATE match_table
    SET seats_available = seats_available - 1
    WHERE match_id = 1;
COMMIT;
```

---

## 🍃 MongoDB Implementation (NoSQL Addon)

The project includes a MongoDB implementation as an addon to the SQL database.

### Collections
```
players, teams, coaches, tournaments, matches,
matchstats, viewers, tickets, venues
```

### Queries Covered
| # | Operation | Type |
|---|---|---|
| 1 | insertOne — add new player | CRUD Create |
| 2 | find all teams | CRUD Read |
| 3 | find with filter — Indian players | CRUD Read |
| 4 | find with projection — name and gamertag | CRUD Read |
| 5 | updateOne — change player role | CRUD Update |
| 6 | deleteOne — remove player | CRUD Delete |
| 7 | aggregate — leaderboard SUM kills | Aggregation |
| 8 | aggregate — count players per team | Aggregation |
| 9 | $lookup — join players with teams | JOIN Equivalent |
| 10 | createIndex + text search | Indexing |

---

## 🎨 Design System

All pages follow a consistent dark cyberpunk theme:

| Element | Value |
|---|---|
| Background | #04040A (deep black) |
| Surface | #0C0C18 |
| Primary Accent | #5CCFFF (cyan) — Players module |
| Secondary Accent | #F0C040 (gold) — Analytics module |
| Danger Accent | #FF4466 (red) — Matches module |
| Success Accent | #30E8A0 (green) — Teams module |
| Special Accent | #A060FF (purple) — Games module |
| Heading Font | Orbitron (Google Fonts) |
| Body Font | Barlow + Barlow Condensed (Google Fonts) |

---

## 👥 Team

| Name | Roll No |
|---|---|
| Khushi Yuwanathe | 124B1B098 |
| Gaurangi Madrewar | 124B1B072 |

---

## 🏫 Institute

**PCET's Pimpri Chinchwad College of Engineering**
Department of Computer Engineering
Sector No. 26, Pradhikaran, Nigdi, Pimpri-Chinchwad, Pune 411044

Subject: Database Management System
Academic Year: 2025-26

---

## 📄 License

This project was developed as a DBMS Mini Project for academic purposes.
Not intended for commercial use.
```

---


