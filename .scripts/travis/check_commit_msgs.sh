#!/bin/bash

# Checks the commits msgs in the range of commits travis is testing.
# Based heavily on
# https://github.com/JensRantil/angular.js/blob/ffe93bb368037049820ac05ef62f8cc7ed379d98/test-commit-msgs.sh

# Travis's docs are misleading.
# Check for either a commit or a range (which apparently isn't always a range) and fix as needed.
if [ "$TRAVIS_COMMIT_RANGE" != "" ]; then
	RANGE=$TRAVIS_COMMIT_RANGE
elif [ "$TRAVIS_COMMIT" != "" ]; then
	RANGE=$TRAVIS_COMMIT
fi

# Travis sends the ranges with 3 dots. Git only wants one.
if [[ "$RANGE" == *...* ]]; then
	RANGE=`echo $TRAVIS_COMMIT_RANGE | sed 's/\.\.\./../'`
elif [[ "$RANGE" != *..* ]]; then
	RANGE="$RANGE~..$RANGE"
fi

EXIT=0
for sha in `git log --format=oneline "$RANGE" | cut '-d ' -f1`
do
    echo -n "Checking commit message for $sha ..."
	git log --format=%B -n 1 $sha | php ./.scripts/validate_commit_msg.php

	VALUE=$?

	if [ "$VALUE" -gt 0 ]; then
		EXIT=$VALUE
	fi
done

exit $EXIT
