<?php

namespace App\Command;

use App\Services\BusinessServices\CountryBS;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-countries',
    description: 'Import all countries in the json file (/root) in the database',
)]
class AppImportCountriesCommand extends Command
{
    public function __construct(
        private readonly CountryBS $countryBS
    )
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try{
            $aData = $this->countryBS->readJsonFile();

            $iCount = 0;
            foreach ($aData['countries'] as $country){
                $this->countryBS->createCountry($country);
                $iCount++;
            }

            $io->success($iCount.' countries imported into the database.');
        }catch(\Exception $e){
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}