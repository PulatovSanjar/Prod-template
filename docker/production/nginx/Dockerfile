# syntax=docker/dockerfile:1.4

# ============================================
# NODE BUILDER STAGE - Build Vite assets
# ============================================
FROM node:22-alpine AS node-builder

WORKDIR /build

# Copy package files for caching
COPY package*.json ./

# Install dependencies
RUN npm ci --only=production

# Copy source files needed for build
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

# Build assets
RUN npm run build

# ============================================
# NGINX STAGE - Serve application
# ============================================
FROM nginx:1.27-alpine

# Copy custom nginx configuration
COPY docker/production/nginx/nginx.conf /etc/nginx/nginx.conf

# Create application directory
RUN mkdir -p /var/www/html/public

# Copy Laravel public directory (static assets, index.php)
COPY public/ /var/www/html/public/

# Copy built Vite assets from node builder
COPY --from=node-builder /build/public/build /var/www/html/public/build

# Set proper permissions
RUN chown -R nginx:nginx /var/www/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
