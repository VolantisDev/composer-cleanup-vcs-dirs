<?php

namespace TopFloor\ComposerCleanupVcsDirs;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Handler {
  /**
   * @var Composer
   */
  protected $composer;

  /**
   * @var IOInterface
   */
  protected $io;

  /**
   * @var Filesystem
   */
  protected $fs;

  /**
   * @var string
   */
  protected $deletingTemplate = 'Deleting %s directory from %s';

  /**
   * Handler constructor.
   * @param \Composer\Composer $composer
   * @param \Composer\IO\IOInterface $io
   */
  public function __construct(Composer $composer, IOInterface $io) {
    $this->composer = $composer;
    $this->io = $io;
    $this->fs = new Filesystem();
  }

  /**
   * @param $parentDir
   * @param bool $excludeRoot
   * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
   */
  public function getVcsDirs($parentDir, $excludeRoot = false) {
    $finder = new Finder();

    $iterator = $finder
      ->directories()
      ->in($parentDir)
      ->ignoreVCS(false)
      ->ignoreDotFiles(false)
      ->exclude(['node_modules', '.git/*'])
      ->name('.git');

    if ($excludeRoot) {
      $iterator = $iterator->depth('> 0');
    }

    return $iterator;
  }

  /**
   * @param $parentDir
   * @param bool $excludeRoot
   */
  public function cleanupVcsDirs($parentDir, $excludeRoot = false) {
    $dirs = [];

    foreach ($this->getVcsDirs($parentDir, $excludeRoot) as $file) {
      $this->io->write(sprintf($this->deletingTemplate, $file->getBasename(), $file->getRelativePath()));

      $dirs[] = $file;
    }

    $this->deleteVcsDirs($dirs);
  }

  /**
   * @param array $dirs
   */
  public function deleteVcsDirs(array $dirs) {
    /** @var SplFileInfo $dir */
    foreach ($dirs as $dir) {
      $this->fs->removeDirectory($dir->getRealPath());
    }
  }

  /**
   * @param \Composer\Package\PackageInterface $package
   */
  public function onPostPackageEvent(PackageInterface $package) {
    $this->cleanupVcsDirs($this->composer->getInstallationManager()->getInstallPath($package));
  }
}
