# Docker Deployment Guide for Vicentech Parish Management System

## Overview
This guide covers deploying the Vicentech Parish Management System using Docker containers on Render for the backend and Netlify for the frontend.

## Prerequisites
- GitHub account
- Render account (free tier available)
- Netlify account (free tier available)
- Docker knowledge (basic)

## Docker Configuration

### Dockerfile Features
- **Base Image**: PHP 8.2-FPM
- **Extensions**: PostgreSQL, Redis, GD, BCmath, and more
- **Dependencies**: Composer, Node.js, npm
- **Assets**: Automatic frontend build
- **Security**: Proper file permissions

### Docker Compose (Local Development)
Use `docker-compose.yml` for local development with:
- Laravel app container
- PostgreSQL database
- Redis cache
- Volume mounting for development

## Deployment Steps

### 1. Backend Deployment (Render + Docker)

1. **Create Render Account**
   - Go to [render.com](https://render.com)
   - Sign up with GitHub

2. **Create Web Service**
   - Click "New" → "Web Service"
   - Repository: `cocomartneil/VICENTECH-DEPLOYMENT`
   - **Environment**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Plan**: `Starter` (free)

3. **Create Database Services**
   - PostgreSQL: `vicentech-postgres`
   - Redis: `vicentech-redis`

4. **Environment Variables**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   DB_CONNECTION=pgsql
   # ... (see env.production.example)
   ```

### 2. Frontend Deployment (Netlify)

1. **Create Netlify Account**
   - Go to [netlify.com](https://netlify.com)
   - Connect GitHub repository

2. **Build Settings**
   - Build Command: `npm run build`
   - Publish Directory: `public`
   - Node Version: `18`

3. **Update API URLs**
   - Modify `netlify.toml` with your Render backend URL

## Docker Benefits

### Advantages of Docker Deployment:
- **Consistency**: Same environment across development and production
- **Isolation**: Containerized application with dependencies
- **Scalability**: Easy to scale horizontally
- **Portability**: Runs anywhere Docker is supported
- **Version Control**: Dockerfile tracks environment changes

### Dockerfile Optimizations:
- Multi-stage builds (if needed)
- Cached layers for faster builds
- Security hardening
- Production-ready configuration

## Local Development

### Using Docker Compose:
```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f app

# Run migrations
docker-compose exec app php artisan migrate

# Stop services
docker-compose down
```

### Manual Docker Commands:
```bash
# Build image
docker build -t vicentech-app .

# Run container
docker run -p 8000:8000 vicentech-app

# Run with environment file
docker run --env-file .env -p 8000:8000 vicentech-app
```

## Troubleshooting

### Common Docker Issues:
1. **Build Failures**: Check Dockerfile syntax and dependencies
2. **Permission Issues**: Ensure proper file ownership
3. **Port Conflicts**: Verify port mappings
4. **Environment Variables**: Check variable names and values

### Render-Specific Issues:
1. **Build Timeouts**: Optimize Dockerfile layers
2. **Memory Limits**: Monitor resource usage
3. **Database Connections**: Verify connection strings

## Security Considerations

### Docker Security:
- Use official base images
- Run as non-root user (configured in Dockerfile)
- Keep images updated
- Scan for vulnerabilities

### Production Security:
- Environment variable management
- Database connection security
- SSL/TLS configuration
- CORS settings

## Monitoring and Maintenance

### Health Checks:
- Application health endpoints
- Database connectivity
- Redis connectivity
- File system permissions

### Logging:
- Application logs
- Docker container logs
- Render service logs
- Netlify build logs

## Performance Optimization

### Docker Optimizations:
- Multi-stage builds
- Layer caching
- Image size reduction
- Build context optimization

### Application Optimizations:
- PHP OPcache
- Redis caching
- Database indexing
- Asset optimization

---

**Note**: Docker deployment provides better consistency and control over your application environment. The Dockerfile handles all dependencies and configuration automatically.
