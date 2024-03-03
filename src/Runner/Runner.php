<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use TCB\FlysystemSync\Action\Contracts\Action;

use function ucfirst;

/**
 * Runs all the Actions
 */
class Runner
{
    public readonly Bag $bag;

    public bool $has_ran = false;

    public function __construct(
        Filesystem|FilesystemAdapter $reader,
        Filesystem|FilesystemAdapter $writer,
        array $sources,
        array $targets
    ) {
        // Hold 8 arrays for create/update/delete/nothing of each type file/directory
        // All actions on those 6 different arrays. Just data for the "nothings".
        $this->bag = new Bag($reader, $writer, $sources, $targets);
    }

    public function execute(): void
    {
        if ($this->has_ran === true) {
            throw new \Exception('->execute() has already ran.');
        }

        // BEFORE actions
        // Gather
        $this->bag->map(function (Action $action): array {
            // Set Result, Differences
            return [
                'action'      => $action,
                'errors'      => $this->getErrorsBeforeAction($action),
                'differences' => [
                    'before' => $action->getDifferences(),
                ],
            ];
        });

        // Execute all Actions
        $this->bag->map(function (array $result): array {
            $result['has_ran']    = false;
            $result['has_errors'] = $result['errors'] !== [];

            // No errors?
            // ->execute() the Action
            if ($result['has_errors'] === false) {
                $result['action']->execute();
                $result['has_ran'] = true;
            }

            // Get the differences after the Action
            $result['differences']['after'] = $result['action']->getDifferences();

            // Set Result, Differences
            return $result;
        });

        // AFTER actions

        // Get differences after ALL ACTIONS HAVE RAN.
        $this->bag->map(function (array $current): array {
            $current['differences']['finished'] = $current['action']->getDifferences();

            return $current;
        });

        // Set has_ran flag so we can't ->execute() again.
        $this->has_ran = true;
    }

    protected function getErrorsBeforeAction(Action $action): array
    {
        $errors = [];
        $type   = ucfirst($action->type());

        if ($action->isReaderExistingValid() === false) {
            $errors[] = $action->isOnReader() ?
                "{$type} must exist on the READER. It does not" :
                "{$type} cannot exist on the READER";
        }

        if ($action->isWriterExistingValid() === false) {
            $errors[] = $action->isOnWriter() ?
                "{$type} must exist on the WRITER. It does not" :
                "{$type} cannot exist on the WRITER";
        }

        return $errors;
    }
}
