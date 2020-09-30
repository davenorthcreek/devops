<?php

namespace App\Auth\Passwords;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Contracts\Auth\PasswordBrokerFactory as FactoryContract;
use App\Auth\CreatePasswordBroker;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Auth\Passwords\PasswordBrokerManager;

/**
 * @mixin \Illuminate\Contracts\Auth\PasswordBroker
 */
class MyPasswordBrokerManager extends PasswordBrokerManager implements FactoryContract
{

    /**
     * Attempt to get the broker from the local cache.
     *
     * @param  string  $name
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null)
    {
        $creator = null;
        if ($name == 'creator') {
            $creator = true;
            $name = null;
        }
        $name = $name ?: $this->getDefaultDriver();

        return isset($this->brokers[$name])
                    ? $this->brokers[$name]
                    : $this->brokers[$name] = $this->resolve($name, $creator);
    }

    /**
     * Resolve the given broker.
     *
     * @param  string  $name
     * @param  mixed   $creator
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name, $creator = null)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Password resetter [{$name}] is not defined.");
        }

        if ($creator)
        {
            return new CreatePasswordBroker(
                $this->createTokenRepository($config),
                $this->app['auth']->createUserProvider($config['provider'] ?? null)
            );
        }
        // The password broker uses a token repository to validate tokens and send user
        // password e-mails, as well as validating that password reset process as an
        // aggregate service of sorts providing a convenient interface for resets.
        return new PasswordBroker(
            $this->createTokenRepository($config),
            $this->app['auth']->createUserProvider($config['provider'] ?? null)
        );
    }

}
