import express from 'express';
import cors from 'cors';

const app = express();
const PORT = 8000;

// Middleware
app.use(cors());
app.use(express.json());

// Store received applications in memory
let applications = [];

// Routes
app.get('/', (req, res) => {
    res.json({
        message: 'HR Platform Mock Server',
        status: 'running',
        port: PORT,
        applications_received: applications.length
    });
});

// Endpoint to receive applications from Recruitsy (matches API documentation)
app.post('/api/portal/jobs/:jobId/apply', (req, res) => {
    try {
        const { jobId } = req.params;
        const application = {
            id: Date.now(),
            job_id: jobId,
            received_at: new Date().toISOString(),
            ...req.body
        };
        
        applications.push(application);
        
        console.log('📝 Application received:', {
            job_id: jobId,
            candidate_name: application.name,
            candidate_email: application.email,
            candidate_phone: application.phone,
            resume_url: application.resume_url,
            cover_letter: application.cover_letter
        });
        
        res.status(201).json({
            success: true,
            message: 'Application received successfully',
            application_id: application.id,
            hr_platform_id: `HR-${application.id}`,
            status: 'under_review'
        });
        
    } catch (error) {
        console.error('❌ Error processing application:', error);
        res.status(500).json({
            success: false,
            message: 'Failed to process application',
            error: error.message
        });
    }
});

// Endpoint to view all received applications
app.get('/api/applications', (req, res) => {
    res.json({
        success: true,
        count: applications.length,
        applications: applications
    });
});

// Endpoint to view a specific application
app.get('/api/applications/:id', (req, res) => {
    const application = applications.find(app => app.id == req.params.id);
    
    if (!application) {
        return res.status(404).json({
            success: false,
            message: 'Application not found'
        });
    }
    
    res.json({
        success: true,
        application: application
    });
});

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({
        status: 'healthy',
        timestamp: new Date().toISOString(),
        uptime: process.uptime()
    });
});

// Start server
app.listen(PORT, () => {
    console.log(`🚀 HR Platform Mock Server running on port ${PORT}`);
    console.log(`📊 Dashboard: http://localhost:${PORT}`);
    console.log(`🔗 API Endpoint: http://localhost:${PORT}/api/portal/jobs/{jobId}/apply`);
    console.log(`💚 Health Check: http://localhost:${PORT}/health`);
    console.log('\nWaiting for applications from Recruitsy Job Portal...\n');
});

// Graceful shutdown
process.on('SIGINT', () => {
    console.log('\n🛑 Shutting down HR Platform Mock Server...');
    process.exit(0);
});
