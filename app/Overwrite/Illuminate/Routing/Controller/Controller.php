<?php

namespace Illuminate\Routing;

use BadMethodCallException;

abstract class Controller
{
    /**
     * The middleware registered on the controller.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Register middleware on the controller.
     *
     * @param  array|string|\Closure  $middleware
     * @param  array   $options
     * @return \Illuminate\Routing\ControllerMiddlewareOptions
     */
    public function middleware($middleware, array $options = [])
    {
        foreach ((array) $middleware as $m) {
            $this->middleware[] = [
                'middleware' => $m,
                'options' => &$options,
            ];
        }

        return new ControllerMiddlewareOptions($options);
    }

    /**
     * Get the middleware assigned to the controller.
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        if(method_exists($this,'before')) call_user_func_array([$this, 'before'], $parameters);
        $return = call_user_func_array([$this, $method], $parameters);
        if(method_exists($this,'after')) call_user_func_array([$this, 'after'], $parameters);
        return $return;
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
