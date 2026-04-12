# syntax=docker/dockerfile:1
ARG NODE_VERSION=22.0.0 
# Note: Using a stable LTS version like 22 is usually safer than 25 unless you specifically need it

FROM node:${NODE_VERSION}-alpine

ENV NODE_ENV production
WORKDIR /usr/src/app

# 1. Install Docker CLI for Alpine
RUN apk add --no-cache docker-cli docker-cli-compose

# Download dependencies
RUN --mount=type=bind,source=package.json,target=package.json \
    --mount=type=bind,source=package-lock.json,target=package-lock.json \
    --mount=type=cache,target=/root/.npm \
    npm ci --omit=dev

# Copy the rest of the source files
COPY . .

# 2. CRITICAL CHANGE: 
# To access /var/run/docker.sock, the user usually needs root privileges.
# If you use "USER node", the "docker compose" command below will likely fail with "Permission Denied".
USER root 

EXPOSE 3000

# Run the application.
CMD ["npm", "start"]