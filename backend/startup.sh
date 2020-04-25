#! /usr/bin/env bash
# Spin up and provision development version of project
#
# This script is intended to be a one-command method for bringing up the complete
# development environment for this project
#


# Set project root
# Note: we're `export`ing so that it's available to all child scripts
if [ "$ROOT_PATH" == '' ]; then
	export ROOT_PATH
	ROOT_PATH="$(git rev-parse --show-toplevel)"
fi

# Provision
echo "Provisioning..."

echo "Installing Composer Packages..."
cd "${ROOT_PATH}/backend/src" || exit
if ! composer install; then
  echo "FAIL: Composer: dependency installation failed, check your environment and try again"
  exit 10
fi

echo "Provisioning Docker..."
if ! docker-compose up --build -d; then # build docker image and start container
	echo "Provisioning failed."
	exit 10
else
	echo "Provisioned successfully"
fi