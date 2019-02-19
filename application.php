#!/usr/bin/env php
<?php
// application.php



require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

(new Application('echo', '1.0.0'))
  ->register('echo')
      ->addArgument('foo', InputArgument::OPTIONAL, 'The directory')
      ->addOption('bar', null, InputOption::VALUE_REQUIRED)
      ->setCode(function(InputInterface $input, OutputInterface $output) {
          // output arguments and options
		  $output->writeln([
        'User Creator',
        '============',
        '',
    ]);
      })
  ->getApplication()
  ->setDefaultCommand('echo', true) // Single command application
  ->run();
