# 🚀 KWIKSTER CMS - SERVER DEPLOYMENT GUIDE

## Step 1: Upload Files
Upload all files to your server's public_html directory

## Step 2: Set File Permissions
```bash
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache  
chmod -R 775 public/uploads
```

## Step 3: Run Server Setup
Visit: `https://yourdomain.com/cms/server_setup.php`

This will:
- ✅ Create required directories
- ✅ Check PHP requirements  
- ✅ Test database connection
- ✅ Verify lead operations
- ✅ Clear Laravel cache
- ✅ Generate health report

## Step 4: Fix Any Issues
If setup script shows errors, fix them and re-run

## Step 5: Test Application
1. Login to admin panel
2. Try uploading leads
3. Test lead status changes
4. Check if everything works

## Step 6: Production Settings
1. Copy `.env.production` to `.env`
2. Set `APP_DEBUG=false`
3. Delete `server_setup.php`

## Common Issues & Fixes:

### Database Connection Failed
- Check database credentials
- Try `localhost` instead of `127.0.0.1`
- Verify database exists

### Permission Denied
```bash
chown -R www-data:www-data storage bootstrap/cache public/uploads
```

### AJAX Network Errors
- Ensure mod_rewrite is enabled
- Check .htaccess file exists
- Verify CSRF token in forms

### File Upload Issues
- Check upload directory permissions
- Verify PHP upload settings
- Ensure disk space available

## Support
If issues persist after following this guide, check:
1. Server error logs
2. Laravel logs in `storage/logs/`
3. Browser console for JavaScript errors

---
**IMPORTANT**: Delete this file and `server_setup.php` after successful deployment!