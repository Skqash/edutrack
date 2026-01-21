#!/bin/bash
# EduTrack - Quick Start Guide

echo "=================================="
echo "EduTrack - Education Management"
echo "System Initialization"
echo "=================================="
echo ""

# Step 1: Navigate to project
echo "[1/5] Navigating to project directory..."
cd c:\laragon\www\edutrack
echo "✓ Done"
echo ""

# Step 2: Install dependencies
echo "[2/5] Installing dependencies..."
composer install
npm install
echo "✓ Done"
echo ""

# Step 3: Setup environment
echo "[3/5] Setting up environment..."
cp .env.example .env
php artisan key:generate
echo "✓ Done"
echo ""

# Step 4: Setup database
echo "[4/5] Setting up database..."
php artisan migrate:fresh --seed
echo "✓ Done"
echo ""

# Step 5: Build assets
echo "[5/5] Building frontend assets..."
npm run dev
echo "✓ Done"
echo ""

echo "=================================="
echo "✅ System Ready!"
echo "=================================="
echo ""
echo "📊 Test Credentials:"
echo "  Super Admin: superadmin@example.com"
echo "  Admin:       admin@example.com"
echo "  Teacher:     teacher1@example.com"
echo "  Student:     student1@example.com"
echo ""
echo "🔑 All passwords: password123"
echo ""
echo "🚀 Start server:"
echo "   php artisan serve"
echo ""
echo "📍 Access at: http://127.0.0.1:8000"
echo ""
echo "=================================="
