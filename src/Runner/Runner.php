<?php

declare(strict_types = 1);

namespace TCB\FlysystemSync\Runner;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;
use TCB\FlysystemSync\Action\Contracts\Action;
use TCB\FlysystemSync\Action\Directory\CreateDirectory;
use TCB\FlysystemSync\Action\Directory\DeleteDirectory;
use TCB\FlysystemSync\Action\Directory\UpdateDirectory;
use TCB\FlysystemSync\Action\File\CreateFile;
use TCB\FlysystemSync\Action\File\DeleteFile;
use TCB\FlysystemSync\Action\File\UpdateFile;
use TCB\FlysystemSync\Filesystem\HelperFilesystem;
use TCB\FlysystemSync\Filesystem\ReaderFilesystem;

use function array_map;
use function ucfirst;

readonly class Runner
{
    public ReaderFilesystem $reader;

    public Filesystem $writer;

    public Sorter $bag;

    public function __construct(
        Filesystem|FilesystemAdapter $reader,
        Filesystem|FilesystemAdapter $writer,
        array $sources,
        array $targets
    ) {
        $this->reader = new ReaderFilesystem($reader);
        $this->writer = HelperFilesystem::prepareFilesystem($writer);

        // Run actions
        $sorter = new Sorter($sources, $targets);

        // ->execute() all the Actions
        $sorter->create_files = $this->runActions(CreateFile::class, $sorter->create_files);
        $sorter->update_files = $this->runActions(UpdateFile::class, $sorter->update_files);
        $sorter->delete_files = $this->runActions(DeleteFile::class, $sorter->delete_files);

        $sorter->create_directories = $this->runActions(CreateDirectory::class, $sorter->create_directories);
        $sorter->update_directories = $this->runActions(UpdateDirectory::class, $sorter->update_directories);
        $sorter->delete_directories = $this->runActions(DeleteDirectory::class, $sorter->delete_directories);

        // Verify the results
        // IMPORTANT: must be verified after all actions are finished running!
        // @todo - need this code

        $this->bag = $sorter;
    }

    protected function runActions(string $class_action, array $batch): array
    {
        return array_map(

            function (StorageAttributes $attributes) use ($class_action): array {
                // Build the Action
                /** @var Action $action */
                $action = new $class_action($this->reader, $this->writer, $attributes);

                // Get any errors.
                $errors = $this->getErrorsActionBefore($action);

                if ($errors === []) {
                    $action->execute();

                    $result = Result::make();
                }
                else {
                    $result = Result::withErrors($errors);
                }

                // Set Result, Differences
                return [
                    'result'      => $result,
                    'differences' => $action->getDifferences(),
                ];
            },

            $batch
        );
    }

    protected function getErrorsActionBefore(Action $action): array
    {
        $errors = [];
        $type   = ucfirst($action->type());

        if ($action->isReaderExistingValid() === false) {
            $errors[] = $action->isOnReader() ?
                $type . ' must not on the READER' :
                $type . ' cannot exist on the READER';
        }

        if ($action->isWriterExistingValid() === false) {
            $errors[] = $action->isOnWriter() ?
                $type . ' must not on the WRITER' :
                $type . ' cannot exist on the WRITER';
        }

        return $errors;
    }
}
