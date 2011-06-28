Important notice
================

This bundle was created to facilitate simple need I had.

It has come to my attention that there are multiple alternatives
implementing the same functionality but with much more care and vision:

* https://github.com/loicfrering/LosoBundle
* https://github.com/joshiausdemwald/AutowiringBundle
* https://github.com/schmittjoh/DiExtraBundle

So take this bundle as a simple example and not something production-ready.

Although I myself am still using my own bundle, it's only because none of the above
(AFAICS) support service_name::somemethod syntax for injections (and configurable overrides)
which I happen to find very useful and convenient.


Introduction
============

FunkitonInjectorBundle provides means for injecting services to controllers by using simple annotations.

Installation
------------

  1. Add this bundle to your project as Git submodules:

        $ git submodule add git://github.com/Funkiton/InjectorBundle.git vendor/bundles/Funkiton/InjectorBundle

  2. Add the Funkiton namespace to your autoloader:

        // app/autoload.php
        $loader->registerNamespaces(array(
            'Funkiton' => __DIR__.'/../vendor/bundles',
            // your other namespaces
        ));

  3. Add this bundle to your application's kernel:

        // application/ApplicationKernel.php
        public function registerBundles()
        {
            return array(
                // ...
                new Funkiton\InjectorBundle\FunkitonInjectorBundle(),
                // ...
            );
        }

  4. Configure the `funkiton_injector` service in your config:

        # app/config/config.yml
        funktion_injector: ~


Injecting services
------------------------

Add needed (depends on which type of injection is needed) use stataments to your controller php file:

    // ...
    use Funkiton\InjectorBundle\Annotation\Inject;
    use Funkiton\InjectorBundle\Annotation\TryInject;
    // ...

And annotations to your controller class docblock:

    /**
     * @Inject("security.context", name="security");
     * @Inject("request::getSession", name="session");
     * @TryInject("user");
     */
    class MyController
    {
        // ...

Optionally define the visibility level of properties as usual:

     * ...
     */
    class MyController 
    {
        private $user;
        protected $session;
    }

Or you can add the annotation right before property definition:

     * ...
     */
    class MyController 
    {
	/** @TryInject("user") */
        private $user;

	/** @Inject("request::getSession"); */
        protected $session;
    }

Now you should be able to access injected services by ```$this->property_name```.


Annotation types
----------------

  1. ```@Inject``` - injects service, doesn't check for exceptions that might happen
  2. ```@TryInject``` - injects service, if DI-related exception is thrown injects null instead.


Annotation arguments
--------------------
Annotations take service name as their default argument. Additionally ```service_name::method```
form is accepted. In this case not service is injected but the return value of specified method.

It's worth to mention that if no name is given in annotation the service name is used as property
name replacing all consecutive non-alphanumeric characters with underscore:

    security.context::getToken -> security_context_getToken

So use ```name``` argument to specify more friendly name for injected property (or see next chapter):

    @Inject("security.context::getToken", name="token")


Additional configuration
------------------------

You can configure service aliases to use in annotations.

For example add this to your config:

    # app/config/config.yml
    funkiton_injector:
        definitions:
            security: security.context
            user:     funkiton_injector.helper::getUser
            session:  request::getSession
            em:       doctrine::getEntityManager
