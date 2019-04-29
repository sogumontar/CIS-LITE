#!/bin/bash

git checkout master
git merge development
git checkout cis-development
git merge master
git checkout cis-master
git merge cis-development
git checkout development

