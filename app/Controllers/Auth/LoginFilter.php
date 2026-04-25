<?php

namespace App\Controllers\Auth;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $request = \Config\Services::request();
        $agent = $request->getUserAgent();

        $routeHandlers = service('router')->getMatchedRoute();
        $routeHandler = $routeHandlers[1];
        $routeArray = explode('\\',$routeHandler);

        if(str_contains(uri_string(), $agent->getReferrer()))
            session()->set('sess_prev_url',session('sess_home_url'));
        else
            session()->set('sess_prev_url',$agent->getReferrer());

        if(session('sess_loggedIn') != 'Y'){
            return redirect()->to(base_url());
        }

        if(in_array('internal', array_map('strtolower', $routeArray)) ){
            if(session('sess_usertype') != '2'){
                session()->destroy();
                return redirect()->to(base_url());
            }
        };
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}