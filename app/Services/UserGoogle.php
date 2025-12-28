<?php

namespace App\Services;

use Google\Client;
use Google\Service\Oauth2;
use Illuminate\Support\Facades\Log;

class UserGoogle
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        
        // Set credentials dari environment variables
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        
        // Set redirect URI dari config (menggunakan config yang sudah didefinisikan di services.php)
        // Fallback ke url() helper jika config tidak tersedia
        $redirectUri = config('services.google.redirect') ?: url('/auth-google-callback');
        $this->client->setRedirectUri($redirectUri);
        
        // Add scopes
        $this->client->addScope('email');
        $this->client->addScope('profile');
        
        // Set prompt untuk select account
        $this->client->setPrompt('select_account');
        
        // Set access type
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function getProfile($code)
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                Log::error('Google OAuth token error', ['error' => $token['error']]);
                return null;
            }
            
            $this->client->setAccessToken($token);
            $oauth2 = new Oauth2($this->client);
            
            $userInfo = $oauth2->userinfo->get();
            
            Log::info('Google OAuth profile retrieved', [
                'email' => $userInfo->getEmail(),
                'name' => $userInfo->getName(),
                'id' => $userInfo->getId(),
                'picture' => $userInfo->getPicture(),
            ]);
            
            return $userInfo;
        } catch (\Exception $e) {
            Log::error('Google OAuth getProfile error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
}

