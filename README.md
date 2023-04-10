sbuerk/test-image-extend
========================

# Descriptions

This repository is a test/dummy(proof-of-concept) repository to build image based on another image.

In the end, this repository should showcase following two things:

* auto rebuild for `main` branch pushes, but only of versions are not available
  on docker hub. Otherwise, it should exit with a information how to increment
  the image version. This is only done if a test build is working.
* verify build for non `main` branches and on `pull requests
* scheduled check if base image has been changed, and create a commit with
  incrementend patchlevel version for changed image. This would trigger the
  autobuild on `main` branch feature.
* scheduled check if new `typo3/core-testing-phpXY` images are defined and
  regenerate them by using the `generate-images.php` along with templates
  in `./templates/Dockerfile-*`

This is used to test/evaluate auto base image changes and triggering
new image builds with new versions in another repository:

Based on images (`core-testing-phpXY`) from [TYPO3 Core Testing Images](https://git.typo3.org/typo3/CI/testing-infrastructure/-/tree/main/docker-images).

For demostration purpose, all images are extendend by installing `php-ext ssh2` as
additionally extension.

**NOTE:** This is only meant as evaluating/testing/proof-of-concept for some workflows,
before implementing it in real image build repositories.

# License

View [license information](http://php.net/license/) for the software contained in this image.

As with all Docker images, these likely also contain other software which may be under other
licenses (such as Bash, etc from the base distribution, along with any direct or indirect
dependencies of the primary software being contained).

As for any pre-built image usage, it is the image user's responsibility to ensure that any
use of this image complies with any relevant licenses for all software contained within.