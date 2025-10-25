# Deployment Guide for Vicentech Parish Management System

## Overview
This guide will help you deploy the Vicentech Parish Management System using:
- **Render** for the Laravel backend API
- **Netlify** for the React frontend
- **GitHub** for version control and deployment triggers

## Prerequisites
- GitHub account
- Render account (free tier available)
- Netlify account (free tier available)
- Domain name (optional, for custom domains)

## Step 1: Prepare GitHub Repository

### 1.1 Initialize Git Repository
```bash
# In your project directory
git init
git add .
git commit -m "Initial commit - Vicentech Parish Management System"
```

### 1.2 Connect to GitHub Repository
```bash
# Add the remote repository
git remote add origin https://github.com/cocomartneil/VICENTECH-DEPLOYMENT.git

# Push to GitHub
git push -u origin main
```

## Step 2: Deploy Backend to Render

### 2.1 Create Render Account
1. Go to [render.com](https://render.com)
2. Sign up with your GitHub account
3. Connect your GitHub repository

### 2.2 Create Web Service
1. Click "New" → "Web Service"
2. Connect your GitHub repository: `cocomartneil/VICENTECH-DEPLOYMENT`
3. Configure the service:
   - **Name**: `vicentech-backend`
   - **Environment**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Plan**: `Starter` (free tier)

### 2.3 Create Database Services
1. **PostgreSQL Database**:
   - Click "New" → "PostgreSQL"
   - Name: `vicentech-postgres`
   - Plan: `Starter` (free)

2. **Redis Database**:
   - Click "New" → "Redis"
   - Name: `vicentech-redis`
   - Plan: `Starter` (free)

### 2.4 Configure Environment Variables
In your Render web service, add these environment variables:

```bash
# App Configuration
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-service-name.onrender.com

# Database (use values from your PostgreSQL service)
DB_CONNECTION=pgsql
DB_HOST=your-postgres-host
DB_PORT=5432
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Redis (use values from your Redis service)
REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-redis-password
REDIS_PORT=6379

# Cache Configuration
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail Configuration (update with your SMTP settings)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Vicentech Parish Management"

# OpenAI (if using AI features)
OPENAI_API_KEY=your-openai-api-key

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=your-frontend-domain.netlify.app
```

### 2.5 Deploy Backend
1. Click "Create Web Service"
2. Render will automatically build the Docker container and deploy your backend
3. The Dockerfile will handle:
   - Installing PHP 8.2 with required extensions
   - Installing Composer and Node.js
   - Installing dependencies and building assets
   - Setting proper permissions
4. Note the URL provided (e.g., `https://vicentech-backend.onrender.com`)

## Step 3: Deploy Frontend to Netlify

### 3.1 Create Netlify Account
1. Go to [netlify.com](https://netlify.com)
2. Sign up with your GitHub account
3. Connect your GitHub repository

### 3.2 Configure Build Settings
1. Click "New site from Git"
2. Choose GitHub and select your repository
3. Configure build settings:
   - **Build command**: `npm run build`
   - **Publish directory**: `public`
   - **Node version**: `18`

### 3.3 Update Netlify Configuration
Update the `netlify.toml` file with your actual backend URL:

```toml
# Replace with your actual Render backend URL
[[redirects]]
  from = "/api/*"
  to = "https://vicentech-backend.onrender.com/api/:splat"
  status = 200
  force = true

[[redirects]]
  from = "/sanctum/*"
  to = "https://vicentech-backend.onrender.com/sanctum/:splat"
  status = 200
  force = true
```

### 3.4 Deploy Frontend
1. Click "Deploy site"
2. Netlify will build and deploy your frontend
3. Note the URL provided (e.g., `https://amazing-name-123456.netlify.app`)

## Step 4: Update Configuration

### 4.1 Update Backend Environment Variables
In your Render service, update the `SANCTUM_STATEFUL_DOMAINS` with your Netlify URL:
```bash
SANCTUM_STATEFUL_DOMAINS=your-netlify-url.netlify.app
```

### 4.2 Update Frontend API Configuration
Update your frontend code to use the Render backend URL. Look for API base URLs in your React components and update them.

## Step 5: Database Migration

### 5.1 Run Migrations
The Dockerfile includes migration commands, but you can also run them manually:

```bash
# Through Render's shell or add to Dockerfile
php artisan migrate --force
php artisan db:seed --force
```

### 5.2 Docker Configuration
The Dockerfile includes:
- PHP 8.2 with PostgreSQL, Redis, and other required extensions
- Composer for PHP dependencies
- Node.js and npm for frontend assets
- Proper file permissions for Laravel

## Step 6: Custom Domain (Optional)

### 6.1 Backend Custom Domain
1. In Render, go to your service settings
2. Add your custom domain
3. Update DNS records as instructed

### 6.2 Frontend Custom Domain
1. In Netlify, go to Domain settings
2. Add your custom domain
3. Update DNS records as instructed

## Step 7: SSL and Security

Both Render and Netlify provide free SSL certificates automatically. Ensure your environment variables are properly configured for production.

## Troubleshooting

### Common Issues:
1. **Build Failures**: Check the build logs in Render/Netlify
2. **Database Connection**: Verify database credentials and connection strings
3. **CORS Issues**: Update `SANCTUM_STATEFUL_DOMAINS` with correct frontend URL
4. **File Uploads**: Ensure proper file storage configuration

### Monitoring:
- Use Render's built-in monitoring
- Set up error tracking (Sentry, Bugsnag)
- Monitor database performance

## Maintenance

### Regular Tasks:
1. Monitor application logs
2. Update dependencies regularly
3. Backup database regularly
4. Monitor resource usage

### Updates:
1. Push changes to GitHub
2. Render and Netlify will automatically redeploy
3. Test thoroughly after each deployment

## Support

For issues specific to:
- **Render**: Check Render documentation and support
- **Netlify**: Check Netlify documentation and support
- **Laravel**: Check Laravel documentation
- **React**: Check React documentation

---

**Note**: This deployment uses free tiers of both Render and Netlify. For production applications with high traffic, consider upgrading to paid plans for better performance and reliability.
