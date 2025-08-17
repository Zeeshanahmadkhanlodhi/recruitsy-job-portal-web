# Application Testing Guide

This guide explains how to test the complete job application flow in the Recruitsy Job Portal.

## What Has Been Implemented

### 1. **Application Controller** (`ApplicationForwardController`)
- ✅ Creates `Application` records in the database
- ✅ Validates application data
- ✅ Forwards applications to HR platform
- ✅ Handles success/failure responses

### 2. **Application Forwarding Service** (`ApplicationForwardService`)
- ✅ Sends applications to HR platform on port 8000
- ✅ Handles API authentication
- ✅ Updates application status based on HR platform response
- ✅ Logs all forwarding attempts

### 3. **Applications Dashboard** (`/applications`)
- ✅ Shows all user applications with pagination
- ✅ Displays application status (pending, success, failed)
- ✅ Shows HR platform responses
- ✅ Includes search and filtering functionality
- ✅ Shows detailed application information

### 4. **HR Platform Mock Server**
- ✅ Runs on port 8000
- ✅ Receives applications from Recruitsy
- ✅ Stores applications in memory
- ✅ Provides API endpoints for testing

## How to Test

### Step 1: Start the HR Platform Mock Server

```bash
# Navigate to the project directory
cd recruitsy-job-portal

# Install dependencies (if not already installed)
npm install

# Start the HR platform mock server
node hr-platform-mock.js
```

You should see:
```
🚀 HR Platform Mock Server running on port 8000
📊 Dashboard: http://localhost:8000
🔗 API Endpoint: http://localhost:8000/api/applications
💚 Health Check: http://localhost:8000/health
```

### Step 2: Start the Recruitsy Application

```bash
# In a new terminal, start the Laravel application on port 8001
php artisan serve --port=8001
```

### Step 3: Test the Application Flow

1. **Browse Jobs**: Go to `http://localhost:8001/jobs`
2. **Click Apply**: Click "Apply Now" on any job
3. **Fill Form**: Complete the application form
4. **Submit**: Click "Submit Application"

### Step 4: Check Results

1. **In Recruitsy**: Go to `http://localhost:8001/applications` to see your application
2. **In HR Platform**: Go to `http://localhost:8000/api/applications` to see received applications

## Expected Flow

### When User Clicks "Apply":

1. **Modal Opens**: Application form appears
2. **Form Submission**: Data is sent to `/api/jobs/{id}/apply`
3. **Application Created**: `Application` record is saved to database
4. **HR Platform Forwarding**: Application is sent to `http://localhost:8000/api/applications`
5. **Status Update**: Application status is updated based on HR platform response
6. **User Feedback**: Success/error message is shown to user

### Application Statuses:

- **`pending`**: Application created, forwarding in progress
- **`success`**: Successfully forwarded to HR platform
- **`failed`**: Failed to forward to HR platform

## Database Tables

### Applications Table
```sql
- id (primary key)
- job_id (foreign key to jobs)
- user_id (foreign key to users)
- candidate_name
- candidate_email
- candidate_phone
- resume_url
- cover_letter
- status (pending/success/failed)
- hr_response (JSON)
- error_message
- created_at
- updated_at
```

## API Endpoints

### Recruitsy → HR Platform
- **POST** `/api/applications` - Receive job applications
- **GET** `/api/applications` - List all received applications
- **GET** `/api/applications/{id}` - Get specific application

### HR Platform Mock Server
- **GET** `/` - Server status
- **GET** `/health` - Health check
- **GET** `/api/applications` - List applications
- **GET** `/api/applications/{id}` - Get application details

## Troubleshooting

### Common Issues:

1. **Port Conflicts**: Ensure ports 8000 and 8001 are available
2. **CORS Issues**: The mock server includes CORS headers
3. **Database Errors**: Check if the `applications` table exists
4. **Authentication**: Ensure user is logged in to submit applications

### Debug Steps:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console for JavaScript errors
3. Verify HR platform is running: `http://localhost:8000/health`
4. Check application status in database

## Next Steps

- [ ] Implement retry functionality for failed applications
- [ ] Add email notifications for application status changes
- [ ] Implement application tracking and analytics
- [ ] Add bulk application management for HR users
- [ ] Implement application templates and saved applications
