# Test Application Flow

## Quick Test Steps

### 1. Start HR Platform Mock Server
```bash
cd recruitsy-job-portal
node hr-platform-mock.js
```

### 2. Start Recruitsy Application (in new terminal)
```bash
cd recruitsy-job-portal
php artisan serve --port=8001
```

### 3. Test the Flow

1. **Open Browser**: Go to `http://localhost:8001`
2. **Login/Signup**: Create an account or login
3. **Browse Jobs**: Go to `http://localhost:8001/jobs`
4. **Click Apply**: Click "Apply Now" on any job
5. **Fill Form**: 
   - Name: Test User
   - Email: test@example.com
   - Phone: 123-456-7890
   - Resume URL: https://example.com/resume.pdf
   - Cover Letter: This is a test application
6. **Submit**: Click "Submit Application"

### 4. Check Results

1. **In Recruitsy**: Go to `http://localhost:8001/applications`
2. **In HR Platform**: Go to `http://localhost:8000/api/applications`

## Expected Results

### Success Case:
- ✅ Application modal opens
- ✅ Form submits successfully
- ✅ Success message appears
- ✅ Application appears in `/applications` page
- ✅ Status shows "Successfully Forwarded"
- ✅ Application appears in HR platform

### Database Check:
```sql
SELECT * FROM applications ORDER BY created_at DESC LIMIT 1;
```

### HR Platform Check:
```bash
curl http://localhost:8000/api/applications
```

## Troubleshooting

### If Application Fails:
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Check HR platform is running: `curl http://localhost:8000/health`
3. Verify database connection
4. Check if user is authenticated

### Common Issues:
- Port 8000 or 8001 already in use
- Database migration not run
- User not logged in
- HR platform not running

## Reset for Testing

### Clear Applications:
```sql
DELETE FROM applications;
```

### Clear HR Platform:
```bash
# Stop HR platform (Ctrl+C)
# Restart: node hr-platform-mock.js
```

## Success Indicators

✅ **Application Created**: Record appears in database  
✅ **HR Platform Receives**: Application shows in `/api/applications`  
✅ **Status Updated**: Application status changes from 'pending' to 'success'  
✅ **User Sees Success**: Success message appears after submission  
✅ **Dashboard Shows**: Application appears in `/applications` page  
✅ **Logs Record**: Laravel logs show successful forwarding
