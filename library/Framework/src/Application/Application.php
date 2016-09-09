<?php
namespace Concrete\Core\Application;


use Concrete\Core\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Core;




class Application extends Container
{
    protected $installed = null;
    protected $environment = null;









/**
     * Returns true if concrete5 is installed, false if it has not yet been.
     */
    public function isInstalled()
    {
        if ($this->installed === null) {
            if (!$this->isShared('config')) {
                throw new \Exception('Attempting to check install status before application initialization.');
            }
            $this->installed = $this->make('config')->get('concrete.installed');
        }
        return $this->installed;
    }

