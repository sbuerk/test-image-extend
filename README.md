sbuerk/test-image-extend
========================

# Descriptions

This repository is a test/dummy repository to build image based on another image.

In the end, this repository should showcase following two things:

* auto rebuild for `main` branch pushes, but only of versions are not available
  on docker hub. Otherwise, it should exit with a information how to increment
  the image version. This is only done if a test build is working.
* scheduled check if base image has been changed, and create a commit with
  incrementend patchlevel version for changed image. This would trigger the
  autobuild on `main` branch feature.

This is used to test/evaluate auto base image changes and triggering
new image builds with new versions in another repository:

Base image in https://github.com/sbuerk/test-image-base

**NOTE:** This is only meant as evaluationg/testing some workflows, before
implementing it in real image build repositories. 