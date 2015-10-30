#!/bin/bash

set -e

if [ $# -ne 1 ]; then
  echo "Usage: `basename $0` <tag>"
  exit 65
fi

TAG=$1

mkdir temp-repo
cp -r .git temp-repo/

#
# Tag & build master branch
#
git tag ${TAG}
./build.sh
cp vagma temp-repo/vagma.phar

#
# Copy executable file into GH pages
#
cd temp-repo
git checkout gh-pages

mkdir -p downloads
cp vagma downloads/vagma-${TAG}.phar
git add downloads/vagma-${TAG}.phar

SHA1=$(openssl sha1 vagma.phar)
IFS=', ' read -r -a sha <<< "$SHA1"

JSON='"name":"vagma.phar"'
JSON="${JSON},\"sha1\":\"${sha[1]}\""
JSON="${JSON},\"url\":\"https://andreklang.github.io/vagrant-manager/downloads/vagma-${TAG}.phar\""
JSON="${JSON},\"version\":\"${TAG}\""

#
# Update manifest
#
php ../updateManifest.php manifest.json '{'$JSON'}'
git add manifest.json

git commit -m "Publish version ${TAG}"

# push pages
git push origin gh-pages

# go back
cd ..

# push the tag
git push origin refs/tags/${TAG}

# pull the changes
git pull

# remove the temp-repo
rm -fr temp-tepo