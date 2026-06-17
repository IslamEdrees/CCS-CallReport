# CCS-CallReport

CCS Call Report Call Center

---

## Overview

CCS-CallReport is a lightweight reporting solution designed for:

* Asterisk
* GOIP Gateways
* PHP 5.3
* MySQL

The system captures outbound dialing attempts directly from the Asterisk dialplan and stores them in MySQL for reporting and analytics.

---

## Features

* Agent Statistics
* GOIP Statistics
* Dial Status Summary
* CSV Export
* Web Dashboard
* Daily Reporting
* Prefix 98 Support
* Prefix 30 Support
* Prefix 97 Support

---

## Directory Structure

```text
CCS-CallReport
├── docs
├── sql
├── web
├── dialplan
├── install
└── backup
```

---

## Prerequisites

* CentOS 6
* Apache
* PHP 5.3
* MySQL
* Asterisk

---

## Installation

### 1. Create Database Objects

```bash
mysql -u root -p password < sql/create_database.sql
```

### 2. Create Views

```bash
mysql -u root -p Password < sql/views.sql
```

### 3. Copy Web Files

```bash
mkdir -p /var/www/html/callreport

cp web/config.php /var/www/html/callreport/
cp web/index.php /var/www/html/callreport/
cp web/export.php /var/www/html/callreport/

chown -R apache:apache /var/www/html/callreport
chmod -R 755 /var/www/html/callreport
```

### 4. Update Dialplan

Copy required dialplan sections from:

```text
dialplan/extensions.conf
```

into:

```text
/etc/asterisk/extensions.conf
```

---

### 5. Reload Dialplan

```bash
asterisk -rx "dialplan reload"
```

---

### 6. Test Calls

Generate test calls using:

```text
98XXXXXXX
30XXXXXXX
97XXXXXXX
```

Verify records are inserted into MySQL.

---

## Validation

### Verify Data Collection

```sql
SELECT COUNT(*) FROM call_attempts;
```

### Verify Status Summary

```sql
SELECT * FROM vw_status_summary_today;
```

### Verify Agent Statistics

```sql
SELECT * FROM vw_agent_summary_today;
```

### Verify GOIP Statistics

```sql
SELECT * FROM vw_goip_summary_today;
```

---

## Database Objects

### Table

```text
call_attempts
```

### Views

```text
vw_call_attempts
vw_agent_summary_today
vw_goip_summary_today
vw_status_summary_today
```

---

## Supported Dial Prefixes

| Prefix | Description |
| ------ | ----------- |
| 98     | GOIP Pool A |
| 30     | GOIP Pool B |
| 97     | GOIP16      |

---

## Version

Current Release:

```text
v1.0 Production
```

---

## Author

Islam Edrees

VoIP Engineer
Asterisk / GOIP Solutions

