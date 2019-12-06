<?php
namespace App\Command;

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server;
use App\Kernel;
use React\Socket\Server as ReactServer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

/**
 * Class ReactCommand
 * @package App\Command
 */
final class ReactCommand extends Command
{
    protected static $defaultName = 'react:run';

    protected function configure(): void
    {
        $this->setDescription('Runs Symfony using ReactPHP');
        $this->addArgument(
            'listen',
            InputArgument::OPTIONAL,
            "Tells ReactPHP on which uri to run. Ex.: 0.0.0.0:8080, 80.\nIf no host is given, 127.0.0.1 is used.",
            8080
        );
        $this->setHelp('Runs a ReactPHP HTTP server on localhost:8080 by default.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // The check is to ensure we don't use .env in production
        if (!isset($_SERVER['APP_ENV'])) {
            if (!class_exists(Dotenv::class)) {
                throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
            }
            (new Dotenv())->load(__DIR__ . '/../../.env');
        }

        $env = $_SERVER['APP_ENV'] ?? 'prod';
        $debug = (bool)($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));
        if ($debug) {
            umask(0000);
            Debug::enable();
        }

        $callback = $this->createRequestCallback(
            new Kernel($env, $debug),
            new HttpFoundationFactory(),
            new DiactorosFactory()
        );

        $server = new Server($callback);
        $port = $input->getArgument('listen') ?? 8080;
        if (strpos($port, ':') === false) {
            $port = '127.0.0.1:' . $port;
        }

        $loop = Factory::create();
        $socket = new ReactServer($port, $loop);
        $server->listen($socket);
        $output->writeln("ReactPHP listening on $port");
        $loop->run();
    }

    /**
     * @param Kernel $kernel
     * @param HttpFoundationFactory $httpFoundationFactory
     * @param DiactorosFactory $psr7Factory
     * @return callable
     */
    private function createRequestCallback(
        Kernel $kernel,
        HttpFoundationFactory $httpFoundationFactory,
        DiactorosFactory $psr7Factory
    ): callable {
        return function (ServerRequestInterface $request) use ($kernel, $httpFoundationFactory, $psr7Factory) {
            try {
                $symfonyRequest = $httpFoundationFactory->createRequest($request);
                $response = $kernel->handle($symfonyRequest);
            } catch (\Throwable $e) {
                return new Response(500, ['Content-Type' => 'text/plain'], $e->getMessage());
            }
            return $psr7Factory->createResponse($response);
        };
    }
}
