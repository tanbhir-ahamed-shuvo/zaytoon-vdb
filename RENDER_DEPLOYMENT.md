# Deployment Guide: Zaytoon VDB to Render (Free)

## Step 1: Push to GitHub

1. Go to [GitHub.com](https://github.com) and sign in (create account if needed)
2. Click **New** to create a new repository
3. Name it: `zaytoon-vdb`
4. Choose **Public** (required for free tier)
5. Click **Create repository**
6. Copy the repository URL (should look like: `https://github.com/YOUR_USERNAME/zaytoon-vdb.git`)

Then run in terminal:
```bash
cd d:\Zaytoon(new)\zaytoon-vdb\zaytoon-vdb
git remote add origin https://github.com/YOUR_USERNAME/zaytoon-vdb.git
git branch -M main
git push -u origin main
```

## Step 2: Deploy on Render

1. Go to [Render.com](https://render.com) and sign up with GitHub
2. Click **New +** → **Web Service**
3. Select **Connect a repository** → Find your `zaytoon-vdb` repo
4. Configure:
   - **Name**: `zaytoon-vdb`
   - **Environment**: `PHP`
   - **Build Command**: 
     ```
     composer install --no-dev && npm install && npm run build && php artisan storage:link
     ```
   - **Start Command**: 
     ```
     php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
     ```
   - **Plan**: `Free`

5. Click **Create Web Service**

## Step 3: Add Environment Variables

In Render dashboard:
1. Go to your service → **Environment**
2. Add these variables:

```
APP_DEBUG=false
APP_ENV=production
LOG_CHANNEL=stderr
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
SESSION_DRIVER=cookie
```

**Note**: Get APP_KEY by running locally:
```bash
php artisan key:generate --show
```

## Step 4: Deploy

1. Push any changes: `git push`
2. Render auto-deploys when you push to GitHub
3. Check deployment status in Render dashboard
4. Your app will be available at: `https://zaytoon-vdb.onrender.com` (or similar)

## Troubleshooting

- **Build fails**: Check logs in Render dashboard
- **Database not working**: SQLite is stored in container (data resets on redeploy)
  - Solution: Use PostgreSQL (free tier available on Render)
- **Static files missing**: Ensure migrations ran successfully

## Database Migration (Optional: Use PostgreSQL for persistence)

If you want persistent database:
1. Create PostgreSQL database on Render (free tier)
2. Update `.env` variables for PostgreSQL instead of SQLite
3. Redeploy

## Questions?

Check: [Render PHP Docs](https://render.com/docs/deploy-php)
