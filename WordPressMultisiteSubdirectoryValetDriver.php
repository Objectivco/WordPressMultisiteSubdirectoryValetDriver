<?php
class WordPressMultisiteSubdirectoryValetDriver extends BasicValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
    	// Look for MULTISITE in wp-config.php. It should be there for multisite installs.
    	return file_exists($sitePath . '/wp-config.php') && strpos( file_get_contents($sitePath . '/wp-config.php'), 'MULTISITE') !== false; 
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        $_SERVER['PHP_SELF']    = $uri;
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        // If URI contains one of the main WordPress directories, and it's not a request for the Network Admin,
        // drop the subdirectory segment before routing the request
        if ( ( stripos($uri, 'wp-admin') !== false || stripos($uri, 'wp-content') !== false || stripos($uri, 'wp-includes') !== false ) && stripos($uri, 'wp-admin/network') === false ) {
			$uri = substr($uri, stripos($uri, '/wp-') );
       	}

        return parent::frontControllerPath(
            $sitePath, $siteName, $this->forceTrailingSlash($uri)
        );
    }

    public function isStaticFile($sitePath, $siteName, $uri)
    {
    	// If the URI contains one of the main WordPress directories and it doesn't end with a slash,
    	// drop the subdirectory from the URI and check if the file exists. If it does, return the new uri.
        if ( stripos($uri, 'wp-admin') !== false || stripos($uri, 'wp-content') !== false || stripos($uri, 'wp-includes') !== false ) {
        	if ( substr($uri, -1, 1) == "/" ) return false;

       		$new_uri = substr($uri, stripos($uri, '/wp-') );

       		if ( file_exists( $sitePath . $new_uri ) ) {
       			return $sitePath . $new_uri;
       		}
       	}

        return parent::isStaticFile( $sitePath, $siteName, $uri );
    }

    /**
     * Redirect to uri with trailing slash.
     *
     * @param  string $uri
     * @return string
     */
    private function forceTrailingSlash($uri)
    {
        if (substr($uri, -1 * strlen('/wp-admin')) == '/wp-admin') {
            header('Location: '.$uri.'/'); die;
        }
        return $uri;
    }
}
