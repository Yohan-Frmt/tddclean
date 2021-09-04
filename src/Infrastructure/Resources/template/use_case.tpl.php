<?php echo "<?php\n" ?>

namespace <?php echo $namespace; ?>;

use <?php echo str_replace(
    search: "UseCase",
    replace: "Request",
    subject: $namespace
); ?>\<?php echo $requestClassName ?>;
use <?php echo str_replace(
    search: "UseCase",
    replace: "Response",
    subject: $namespace
); ?>\<?php echo $responseClassName ?>;
use <?php echo str_replace(
    search: "UseCase",
    replace: "Presenter",
    subject: $namespace
); ?>\<?php echo $presenterInterfaceName ?>;


class <?php echo $className; ?><?php echo "\n" ?>
{
    /**
    * @param <?php echo $requestClassName ?> $request
    * @param <?php echo $presenterInterfaceName ?> $presenter
    */
    public function execute(<?php echo $requestClassName ?> $request, <?php echo $presenterInterfaceName ?> $presenter)
    {
        $presenter->present(new <?php echo $responseClassName ?>());
    }
}
