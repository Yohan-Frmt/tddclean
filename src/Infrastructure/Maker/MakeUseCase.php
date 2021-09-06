<?php

namespace App\Infrastructure\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeUseCase extends AbstractMaker
{
    /**
     * @return string
     */
    public static function getCommandName(): string
    {
        return 'make:use-case';
    }

    /**
     * @param Command            $command
     * @param InputConfiguration $inputConfig
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription(description: 'Create a use case')
            ->addArgument(name: 'domain', mode: InputArgument::REQUIRED, description: 'Select domain')
            ->addArgument(name: 'name', mode: InputArgument::REQUIRED, description: 'Choose name');
    }

    /**
     * @param InputInterface $input
     * @param ConsoleStyle   $io
     * @param Generator      $generator
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $domainArg = Str::asCamelCase(str: $input->getArgument(name: 'domain'));
        $domain = __DIR__ . '../../../../Domain/src/' . $domainArg;
        $domainTest = __DIR__ . '../../../../Domain/tests/' . $domainArg;

        $useCaseClassName = $generator->createClassNameDetails(
            name: $input->getArgument(name: 'name'),
            namespacePrefix: 'Domain\\' . $domainArg . '\\UseCase'
        );

        $requestClassName = $generator->createClassNameDetails(
            name: $input->getArgument(name: 'name'),
            namespacePrefix: 'Domain\\' . $domainArg . '\\Request',
            suffix: 'Request'
        );

        $responseClassName = $generator->createClassNameDetails(
            name: $input->getArgument(name: 'name'),
            namespacePrefix: 'Domain\\' . $domainArg . '\\Response',
            suffix: 'Response'
        );

        $presenterInterfaceName = $generator->createClassNameDetails(
            name: $input->getArgument(name: 'name'),
            namespacePrefix: 'Domain\\' . $domainArg . '\\Presenter',
            suffix: 'Presenter'
        );

        $testClassName = $generator->createClassNameDetails(
            name: $input->getArgument(name: 'name'),
            namespacePrefix: 'Domain\\Tests',
            suffix: 'Test'
        );

        $generator->generateFile(
            targetPath: $domain . '/UseCase/' . $useCaseClassName->getShortName() . '.php',
            templateName: __DIR__ . '/../Resources/template/use_case.tpl.php',
            variables: [
                'namespace' => 'Domain\\' . $domainArg . '\\UseCase',
                'className' => $useCaseClassName->getShortName(),
                'requestClassName' => $requestClassName->getShortName(),
                'responseClassName' => $responseClassName->getShortName(),
                'presenterInterfaceName' => $presenterInterfaceName->getShortName(),
            ]
        );

        $generator->generateFile(
            targetPath: $domain . '/Request/' . $requestClassName->getShortName() . '.php',
            templateName: __DIR__ . '/../Resources/template/request.tpl.php',
            variables: [
                'namespace' => 'Domain\\' . $domainArg . '\\Request',
                'className' => $requestClassName->getShortName(),
            ]
        );

        $generator->generateFile(
            targetPath: $domain . '/Response/' . $responseClassName->getShortName() . '.php',
            templateName: __DIR__ . '/../Resources/template/response.tpl.php',
            variables: [
                'namespace' => 'Domain\\' . $domainArg . '\\Response',
                'className' => $responseClassName->getShortName(),
            ]
        );

        $generator->generateFile(
            targetPath: $domain . '/Presenter/' . $presenterInterfaceName->getShortName() . '.php',
            templateName: __DIR__ . '/../Resources/template/presenter.tpl.php',
            variables: [
                'namespace' => 'Domain\\' . $domainArg . '\\Presenter',
                'className' => $presenterInterfaceName->getShortName(),
                'responseClassName' => $responseClassName->getShortName(),
            ]
        );

        $generator->generateFile(
            targetPath: $domainTest . '/' . $testClassName->getShortName() . '.php',
            templateName: __DIR__ . '/../Resources/template/test.tpl.php',
            variables: [
                'namespace' => 'Domain\\Tests\\' . $domainArg,
                'className' => $testClassName->getShortName(),
                'useCaseClassName' => $useCaseClassName->getShortName(),
                'requestClassName' => $requestClassName->getShortName(),
                'responseClassName' => $responseClassName->getShortName(),
                'presenterInterfaceName' => $presenterInterfaceName->getShortName(),
                'useCaseNamespace' => str_replace(
                    search: 'App\\',
                    replace: '',
                    subject: $useCaseClassName->getFullName()
                ),
                'requestNamespace' => str_replace(
                    search: 'App\\',
                    replace: '',
                    subject: $requestClassName->getFullName()
                ),
                'responseNamespace' => str_replace(
                    search: 'App\\',
                    replace: '',
                    subject: $responseClassName->getFullName()
                ),
                'presenterNamespace' => str_replace(
                    search: 'App\\',
                    replace: '',
                    subject: $presenterInterfaceName->getFullName()
                ),
            ]
        );

        $generator->writeChanges();
        $this->writeSuccessMessage(io: $io);
    }

    /**
     * @param DependencyBuilder $dependencies
     */
    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
