#!/bin/bash

# Square Payment Integration Test Script
# This script runs the Square payment integration test

echo "=========================================="
echo "Square Payment Integration Test"
echo "=========================================="
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

# Run the test command
php artisan square:test "$@"

# Exit with the same code as the artisan command
exit $?

