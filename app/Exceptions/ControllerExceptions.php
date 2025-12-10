<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class ControllerException extends Exception
{
    // Base controller exception
}

class BackWithErrorsException extends ControllerException
{
    protected $errors;
    protected $withInput;

    public function __construct($errors = [], $withInput = false)
    {
        parent::__construct();
        $this->errors = $errors;
        $this->withInput = $withInput;
    }

    public function render($request)
    {
        $resp = redirect()->back();
        if ($this->withInput) {
            $resp = $resp->withInput();
        }
        return $resp->withErrors($this->errors);
    }
}

class RouteRedirectException extends ControllerException
{
    protected $route;
    protected $params;
    protected $flash; // associative array
    protected $withInput = false;

    public function __construct(string $route, array $flash = [], array $params = [], bool $withInput = false)
    {
        parent::__construct();
        $this->route = $route;
        $this->params = $params;
        $this->flash = $flash;
        $this->withInput = $withInput;
    }

    public function render($request)
    {
        $resp = redirect()->route($this->route, $this->params);
        if ($this->withInput) {
            $resp = $resp->withInput();
        }
        foreach ($this->flash as $key => $value) {
            $resp = $resp->with($key, $value);
        }
        return $resp;
    }
}

class JsonResponseException extends ControllerException
{
    protected $payload;
    protected $status;

    public function __construct(array $payload = [], int $status = 400)
    {
        parent::__construct();
        $this->payload = $payload;
        $this->status = $status;
    }

    public function render($request)
    {
        return response()->json($this->payload, $this->status);
    }
}
