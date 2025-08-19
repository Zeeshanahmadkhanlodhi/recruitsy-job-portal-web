#!/bin/bash

echo "🧪 Testing Job Application Functionality"
echo "========================================"

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the Laravel project root directory"
    exit 1
fi

echo "📋 Running Feature Tests..."
echo "---------------------------"

# Run the feature tests
php artisan test tests/Feature/JobApplicationTest.php --verbose

echo ""
echo "🌐 Running Browser Tests..."
echo "---------------------------"

# Check if Dusk is available
if [ -f "tests/DuskTestCase.php" ]; then
    echo "✅ Dusk is available, running browser tests..."
    php artisan dusk tests/Browser/JobApplicationBrowserTest.php
else
    echo "⚠️  Dusk not available. To run browser tests, install Laravel Dusk:"
    echo "   composer require --dev laravel/dusk"
    echo "   php artisan dusk:install"
fi

echo ""
echo "🔍 Manual Testing Checklist"
echo "==========================="
echo "1. ✅ Visit /dashboard/jobs page"
echo "2. ✅ Verify 'Apply Now' button is visible for each job"
echo "3. ✅ Click 'Apply Now' button"
echo "4. ✅ Verify button shows 'Applying...' with spinner"
echo "5. ✅ Verify button is disabled during application"
echo "6. ✅ Check browser console for any JavaScript errors"
echo "7. ✅ Verify API call is made to /api/jobs/{id}/apply"
echo "8. ✅ Check network tab for request/response"
echo "9. ✅ Verify success/error handling works"

echo ""
echo "📝 Test Results Summary"
echo "======================="
echo "Feature Tests: Check output above"
echo "Browser Tests: Check output above"
echo "Manual Tests: Complete the checklist above"

echo ""
echo "🚀 To run all tests: php artisan test"
echo "🌐 To run browser tests: php artisan dusk"
echo "📊 To see test coverage: php artisan test --coverage"
