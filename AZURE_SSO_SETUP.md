# Azure SSO Setup Guide

This guide will help you configure Microsoft Azure AD (Entra ID) Single Sign-On (SSO) for the PGoS Clinic Management System.

## Prerequisites

- An Azure AD (Entra ID) tenant
- Admin access to Azure Portal
- The application is already configured with Laravel Socialite and Microsoft Azure provider

## Step 1: Register Application in Azure AD

1. Go to [Azure Portal](https://portal.azure.com)
2. Navigate to **Azure Active Directory** > **App registrations**
3. Click **New registration**
4. Fill in the details:
   - **Name**: `PGoS Clinic Management System`
   - **Supported account types**: Choose based on your needs (Single tenant, Multi-tenant, etc.)
   - **Redirect URI**: 
     - Type: `Web`
     - URI: `https://yourdomain.com/clinic/login/azure/callback` (for clinic panel)
     - Add another: `https://yourdomain.com/parent/login/azure/callback` (for parent panel)
     - Add another: `https://yourdomain.com/login/azure/callback` (for general auth)
5. Click **Register**

## Step 2: Configure Redirect URIs

**CRITICAL**: This step is essential to avoid the "AADSTS900561" error.

1. After registration, go to **Authentication** in the left menu
2. Under **Platform configurations**, click **Add a platform** (if not already added)
3. Select **Web**
4. Add **ALL** of the following redirect URIs (one at a time if needed):
   - `https://yourdomain.com/clinic/login/azure/callback` (recommended)
   - `https://yourdomain.com/clinic/login/auth/azure/callback` (alternative, also supported)
   - `https://yourdomain.com/parent/login/azure/callback` (recommended)
   - `https://yourdomain.com/parent/login/auth/azure/callback` (alternative, also supported)
   - `https://yourdomain.com/login/azure/callback` (for general auth)
5. For local development, also add:
   - `http://localhost:8000/clinic/login/azure/callback` (recommended)
   - `http://localhost:8000/clinic/login/auth/azure/callback` (alternative)
   - `http://localhost:8000/parent/login/azure/callback` (recommended)
   - `http://localhost:8000/parent/login/auth/azure/callback` (alternative)
   - `http://localhost:8000/login/azure/callback`
6. Click **Save** after adding each URI
7. **Important**: 
   - Ensure there are **NO trailing slashes** 
   - The URLs must match **EXACTLY** (including protocol http/https)
   - The domain must match exactly (including www vs non-www)

## Step 3: Configure Application

1. After registration, note down:
   - **Application (client) ID** - This is your `AZURE_AD_CLIENT_ID`
   - **Directory (tenant) ID** - This is your `AZURE_AD_TENANT_ID`

2. Go to **Certificates & secrets**
3. Click **New client secret**
4. Add a description and set expiration
5. Click **Add** and **copy the secret value immediately** (you won't see it again)
   - This is your `AZURE_AD_CLIENT_SECRET`

## Step 4: Configure API Permissions

1. Go to **API permissions**
2. Click **Add a permission**
3. Select **Microsoft Graph**
4. Select **Delegated permissions**
5. Add the following permissions:
   - `openid`
   - `profile`
   - `email`
   - `User.Read`
6. Click **Add permissions**
7. Click **Grant admin consent** (if you're an admin)

## Step 5: Configure Environment Variables

Add the following to your `.env` file:

```env
AZURE_AD_CLIENT_ID=your-client-id-here
AZURE_AD_CLIENT_SECRET=your-client-secret-here
AZURE_AD_TENANT_ID=your-tenant-id-here
AZURE_AD_REDIRECT_URI=https://yourdomain.com/clinic/login/azure/callback
```

**Important Notes**:
- The redirect URI in `.env` should match **exactly** one of the redirect URIs you configured in Azure AD
- Use your **actual tenant ID** (not 'common' unless you specifically need multi-tenant support)
- For production, always use `https://` protocol
- Ensure there are **no trailing slashes** in the redirect URI
- The domain must match exactly (including www vs non-www)

**Example for local development**:
```env
AZURE_AD_CLIENT_ID=12345678-1234-1234-1234-123456789012
AZURE_AD_CLIENT_SECRET=your-secret-value-here
AZURE_AD_TENANT_ID=87654321-4321-4321-4321-210987654321
AZURE_AD_REDIRECT_URI=http://localhost:8000/clinic/login/azure/callback
```

**Example for production**:
```env
AZURE_AD_CLIENT_ID=12345678-1234-1234-1234-123456789012
AZURE_AD_CLIENT_SECRET=your-secret-value-here
AZURE_AD_TENANT_ID=87654321-4321-4321-4321-210987654321
AZURE_AD_REDIRECT_URI=https://clinic.yourschool.com/clinic/login/azure/callback
```

## Step 6: Test the Configuration

1. Clear your application cache:
   ```bash
   php artisan optimize:clear
   ```

2. Visit your login page (e.g., `/clinic/login` or `/parent/login`)
3. You should see a "Sign in with Microsoft" button
4. Click it to test the Azure SSO flow

## Troubleshooting

### Common Issues

1. **"AADSTS900561: The endpoint only accepts POST requests. Received a GET request"**
   - **Most Common Cause**: Redirect URI mismatch between Azure AD and your application
   - **Solution**: 
     - Verify the redirect URI in Azure AD **exactly** matches your callback URL
     - For clinic panel: `https://yourdomain.com/clinic/login/azure/callback`
     - For parent panel: `https://yourdomain.com/parent/login/azure/callback`
     - Ensure there are **no trailing slashes**
     - Check that the protocol (http/https) matches exactly
     - Verify the domain matches exactly (no www vs non-www mismatch)
   - **Additional Checks**:
     - Ensure your `.env` file has the correct `AZURE_AD_REDIRECT_URI` set
     - Clear your application cache: `php artisan optimize:clear`
     - Verify the tenant ID is correct (should be your Azure AD tenant ID, not 'common' unless using multi-tenant)

2. **"Invalid redirect URI" error**
   - Ensure the redirect URI in Azure AD matches exactly with your application URL
   - Check that the protocol (http/https) matches
   - Verify there are no trailing slashes
   - Make sure you've added ALL callback URLs to Azure AD (clinic, parent, and general)

3. **"AADSTS70011: The provided value for the input parameter 'scope' is not valid"**
   - Ensure API permissions are correctly configured in Azure AD
   - Make sure admin consent is granted
   - Verify the scopes requested match what's configured in Azure AD

4. **"User not found" after login**
   - The system will automatically create a user account on first Azure login
   - Check that the email from Azure AD matches your user database
   - Verify the email attribute is being returned correctly from Azure AD

5. **Redirects to wrong panel**
   - The system automatically detects the panel based on the login URL
   - Ensure users have the correct roles assigned in your system

6. **Callback route not found**
   - Verify routes are registered: `php artisan route:list --name=azure`
   - Clear route cache: `php artisan route:clear`
   - Ensure panel providers are properly registered in `bootstrap/providers.php`

## Security Considerations

1. **Client Secret**: Keep your `AZURE_AD_CLIENT_SECRET` secure and never commit it to version control
2. **HTTPS**: Always use HTTPS in production for redirect URIs
3. **Token Expiration**: Configure appropriate token expiration in Azure AD
4. **User Roles**: Ensure users are assigned appropriate roles after Azure login

## Additional Configuration

### Custom User Attributes

If you need to map additional Azure AD attributes to your user model, edit:
- `app/Http/Controllers/Auth/AzureController.php`

### Role Assignment

After Azure login, users are created but may need roles assigned. You can:
1. Manually assign roles in the admin panel
2. Automate role assignment based on Azure AD group membership (requires additional configuration)

## Support

For issues specific to:
- **Azure AD**: Check [Microsoft Azure AD Documentation](https://docs.microsoft.com/en-us/azure/active-directory/)
- **Laravel Socialite**: Check [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- **Application**: Contact your system administrator
