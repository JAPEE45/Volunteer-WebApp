## SMS ENDPOINT UPDATE GUIDE
**Issue:** ngrok tunnel offline - SMS not working
**Date:** December 9, 2025

### Current Status:
- ❌ Old URL: https://prorestoration-enrico-worrisome.ngrok-free.dev/send_sms
- ❌ Status: Offline (ngrok tunnel expired)
- ✅ SMS function: Working
- ✅ cURL: Enabled

### Quick Fix Steps:

#### Step 1: Get Your Active ngrok URL
```bash
# In your SMS backend terminal, look for the ngrok URL
# It should look like:
# Forwarding: https://[random-name].ngrok-free.dev -> http://localhost:[port]
```

#### Step 2: Update sendSms.php
Open: `utility/sendSms.php`

Replace line 3:
```php
// OLD (expired):
$url = "https://prorestoration-enrico-worrisome.ngrok-free.dev/send_sms";

// NEW (your current ngrok URL):
$url = "https://YOUR-NEW-NGROK-URL.ngrok-free.dev/send_sms";
```

#### Step 3: Test Again
Visit: http://localhost/Volunteer-WebApp/utility/test_sms.php

You should see: ✓ SUCCESS - SMS sent!

### Files That Use SMS:
1. `utility/sendSms.php` - Main SMS sender (UPDATE THIS)
2. `utility/updateVolunteerStatus.php` - Account approval SMS
3. `utility/deployVolunteer.php` - Deployment notification SMS
4. `utility/recallVolunteers.php` - Recall/thank you SMS

### Alternative: Use Static ngrok Domain (Recommended)
If you have a paid ngrok account, you can use a static domain that doesn't change:

```bash
ngrok http --domain=your-static-domain.ngrok-free.app [port]
```

Then update sendSms.php with the static URL - it will never expire!

### Test Commands:

**Test via Browser:**
```
http://localhost/Volunteer-WebApp/utility/test_sms.php
```

**Test via PowerShell:**
```powershell
Invoke-WebRequest -Uri "http://localhost/Volunteer-WebApp/utility/test_sms.php" -UseBasicParsing | Select-Object -ExpandProperty Content
```

### Need Help?
- ✓ SMS function code: Working perfectly
- ✓ Database columns: Updated to mobile_number
- ✓ All integrations: Updated
- ❌ Only issue: ngrok URL needs updating

**Just update the URL and everything will work! 🎉**
