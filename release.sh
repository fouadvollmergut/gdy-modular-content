#!/bin/zsh

if [ $# -eq 0 ]; then
    echo "Please supply the version number as an argument."
fi

read -p "Releaseing Version $1. Did you push/pull/merge all necessary changes to main? (y/n) " remember

if [ $remember != 'y' ]; then 
    echo "\nCome back when you are ready! Aborting Release …"
    exit 1
fi

# Set release version
git checkout -b release/$1 origin/main

sed -i '' -E "s/\"version\": \".*\"/\"version\": \"$1\"/" package.json
sed -i '' -E "s/Version: .*/Version: $1/" gdy-modular-content.php
sed -i '' -E "s/'GDYMC_PLUGIN_VERSION', '.*'/'GDYMC_PLUGIN_VERSION', '$1'/" gdy-modular-content.php
sed -i '' -E "s/Stable tag: .*/Stable tag: $1/" readme.txt

git add package.json gdy-modular-content.php readme.txt
git commit -m "Release $1"
git tag -a $1 -m "Release Version $1"
git push origin --tags
git push origin release/$1

git checkout main