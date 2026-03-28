# Deployment Guide: Zaytoon VDB on Render Free

This project now deploys with Docker so Render does not depend on the platform runtime type.

## 1) Push latest code

```bash
git push
```

## 2) Create service from Blueprint

1. Open Render dashboard.
2. If you already created a failing Web Service, delete it first.
3. Click New +, then Blueprint.
4. Select your repository: zaytoon-vdb.
5. Render will read render.yaml and create a Docker Web Service.

## 3) Set required environment values

In the created service, open Environment and set:

APP_KEY=your-base64-key
APP_URL=https://your-service-name.onrender.com

Other env values are already provided in render.yaml.

## 4) Deploy

1. Trigger deploy (or it starts automatically after Blueprint creation).
2. Wait for build and start to complete.
3. Open the Render URL.

## Notes

- This setup uses SQLite for free deployment simplicity.
- SQLite data on free web instances is ephemeral. Data can reset on redeploy or restart.
- For persistent data, move to Render PostgreSQL and update DB env vars.
