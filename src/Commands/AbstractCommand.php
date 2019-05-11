<?php

declare(strict_types = 1);

namespace TCB\Flysystem\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends \Illuminate\Console\Command
{
    /**
     * Run the console command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        try {
            $result = parent::run($input, $output);
            $this->line('');

            return $result;
        }
        catch (\Exception $exception) {

            $this->line('');
            $this->error('Error');
            $this->line('');
            $this->error($exception->getMessage());

            $this->line('');
            $this->line($exception->getTraceAsString());
        }

        return 1;
    }
}
