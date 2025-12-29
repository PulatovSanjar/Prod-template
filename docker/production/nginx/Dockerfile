# Stage 1: Build assets (stable Node image)
FROM node:20-alpine AS builder

WORKDIR /var/www

# Copy only package files first for better caching
COPY package.json package-lock.json* ./
RUN npm ci || npm install

# Copy the rest of the app (needed for Vite build)
COPY . /var/www

# Build Vite assets
RUN npm run build


# Stage 2: Nginx production image
FROM nginx:1.27-alpine

# Custom Nginx config
COPY ./docker/production/nginx/nginx.conf /etc/nginx/nginx.conf

# Copy public folder including built assets
COPY --from=builder /var/www/public /var/www/public

WORKDIR /var/www/public

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
