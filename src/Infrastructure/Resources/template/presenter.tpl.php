<?php echo "<?php\n" ?>

namespace <?php echo $namespace; ?>;

use <?php echo str_replace("Presenter", "Response", $namespace); ?>\<?php echo $responseClassName ?>;

interface <?php echo $className; ?><?php echo "\n" ?>
{
    /**
    * @param <?php echo $responseClassName ?> $response
    */
    public function present(<?php echo $responseClassName ?> $response): void;
}
