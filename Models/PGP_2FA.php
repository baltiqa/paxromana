<?php

namespace App\Models;
use GNUPG;

/*

Title: 2FA-PGP

Description: 2-Factor-Authentication for the web with PGP

Copyright: 2015 hardest1

*/

// Generate a random secret passphrase with a default length of 15


// PGP-2FA Class

class PGP_2FA {
    
	// Unencrypted secret
	
    private $secret;
    
    // Generate safe hash of unencrypted secret, push it to the session and save unencrypted secret locally
    

    
	
    public function generateSecret(){
        
        // Generate unencrypted secret
        

		
        $secret = $this->generateSecretKey();
        
        
        // Hash the secret with bcrypt
        



        $secret_hash = password_hash($secret, PASSWORD_BCRYPT);
        
		
		// Save within the session
		
        // $_SESSION['pgp-secret-hash'] = $secret_hash;

        session()->put('pgp-secret-hash', $secret_hash);

		
		// Save the unencrypted secret locally for safety
		
        $this->secret = $secret;
    }

    public function generateSecretKey($length = 15){
        $secret = str_random(15);;
        
        
        return $secret;
    }
    
	// Encrypt secret with PGP public key
	
    public function encryptSecret($public_key){
        
		// Set GnuPG homedir to /tmp
	        putenv("GNUPGHOME=/tmp");
	        
		// Create new GnuPG instance
		
        $gpg = new gnupg();
        
		// Import given public key
		
        $key = $gpg->import($public_key);

        
		// Add imported key for encryption
		
        $gpg->addencryptkey($key['fingerprint']);

        
		// Encrypt the secret to a PGP message
		
        
        $enc = $gpg->encrypt($this->secret);


        $gpg->clearencryptkeys();
        		
        return $enc;
    }
    
	// Compare user input with saved secret.
	
    public function compare($user_input,$secrethash){
		
		
        if(password_verify($user_input,$secrethash)){
			return true;
        
		} else {
            
			return false;
        
		}
	
    }


    public function sign($message) {
        $gpg = new gnupg();

              putenv("GNUPGHOME=/tmp");
        
        $key = $gpg->import("-----BEGIN PGP PRIVATE KEY BLOCK-----
        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
        =J1Fz
        -----END PGP PRIVATE KEY BLOCK-----
        ");


        $gpg->addsignkey($key['fingerprint'],"");
        $signed = $gpg->sign($message);

        return $signed;

    }

    
}

