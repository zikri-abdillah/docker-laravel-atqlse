<?php

/**
 * Filter For Login Authentication
 *
 */

//namespace CodeIgniter\Filters;
namespace Config;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Debug toolbar filter
 */
class Auth implements FilterInterface
{
    /**
     * We don't need to do anything here.
     *
     * @param array|null $arguments
     */
    public function before(RequestInterface $request, $arguments = null)
    {
    	qq($request);
    	// $security = Services::security();

        // try {
        //     $security->verify($request);
        // } catch (SecurityException $e) {
        //     if ($security->shouldRedirect() && ! $request->isAJAX()) {
        //         return redirect()->back()->with('error', $e->getMessage());
        //     }

        //     throw $e;
        // }
    }

    /**
     * If the debug flag is set (CI_DEBUG) then collect performance
     * and debug information and display it in a toolbar.
     *
     * @param array|null $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
