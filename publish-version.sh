#!/bin/bash

set -e

if [ $# -ne 1 ]; then
  echo "Usage: `basename $0` <tag>"
  exit 65
fi

TAG=$1

#
# Tag & build master branch
#
git checkout master
git tag ${TAG}
./build.sh

#
# Copy executable file into GH pages
#
git checkout gh-pages

mkdir -p downloads
cp vagma downloads/vagma-${TAG}
git add downloads/vagma-${TAG}

SHA1=$(openssl sha1 vagma)

JSON='"name":"vagma"'
JSON="${JSON},\"sha1\":\"${SHA1}\""
JSON="${JSON},\"url\":\"https://github.com/AndreKlang/vagrant-manager/downloads/vagma-${TAG}\""
JSON="${JSON},\"version\":\"${TAG}\""

#
# Update manifest
#
php updateManifest.php manifest.json '{'$JSON'}'
git add manifest.json

git commit -m "Publish version ${TAG}"

#
# Go back to master
#
git checkout master

echo "New version created. Now you should run:"
echo "git push origin gh-pages"
echo "git push ${TAG}"