Symfony ReactPHP Command example
==

Simple REST API example using ReactPHP

### Usage
Run the following command to run your ReactPHP server:
```console
$ php bin/console react:run
```

Your ReactPHP server will launch on port 8080, to run on a different host specify the host uri as an argument.
Examples:
```console
$ php bin/console react:run 80
$ php bin/console react:run 0.0.0.0:8080     # Example for Docker
```

Usage in your own project
==
There is currently no composer package available. 
To use the ReactPHP Symfony bridge in your own project simply copy
`src/Command/ReactCommand.php` to your own project.