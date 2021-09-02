<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use <?= str_replace(search: "UseCase", replace: "Request", subject: $namespace); ?>\<?= $requestClassName ?>;
use <?= str_replace(search: "UseCase", replace: "Response", subject: $namespace); ?>\<?= $responseClassName ?>;
use <?= str_replace(search: "UseCase", replace: "Presenter", subject: $namespace); ?>\<?= $presenterInterfaceName ?>;


class <?= $className; ?><?= "\n" ?>
{
    /**
    * @param <?= $requestClassName ?> $request
    * @param <?= $presenterInterfaceName ?> $presenter
    */
    public function execute(<?= $requestClassName ?> $request, <?= $presenterInterfaceName ?> $presenter)
    {
        $presenter->present(new <?= $responseClassName ?>());
    }
}