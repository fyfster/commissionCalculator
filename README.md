# Commission Fee Calculator

This Symfony console application reads a CSV file containing financial operations and calculates commission fees according to defined business rules.

## 🛠 Features

- Deposit fees for all users (0.03%)
- Business user withdraw fees (0.5%)
- Private user withdraw rules:
  - First 1,000 EUR per week is free for up to 3 operations
  - Multi-currency support with live exchange rates

## 📦 Requirements

- PHP 8.1+
- Composer
- Symfony CLI (optional)
- Internet access (for exchange rate API)

## 🚀 Setup

```bash
composer install
```

## 📂 CSV Input Format

Each line in the input CSV must follow this structure:

```csv
date,user_id,user_type,operation_type,amount,currency
```

## ▶️ Usage
From the root project folder run
```bash
php bin/console app:calculate-commission data/input.csv
```

To have different results add new information in data/input.csv

## ▶️ Usage

```bash
vendor/bin/phpunit
```

