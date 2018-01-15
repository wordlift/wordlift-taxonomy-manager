#!/usr/bin/env bash

DB_NAME=$1
DB_USER=$2
DB_PASS=$3
DB_HOST=${4-localhost}
WP_VERSION=${5-latest}
SKIP_DB_CREATE=${6-false}

# see http://stackoverflow.com/a/4774063/565110
pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd`
popd > /dev/null

# Drop the test database.
mysqladmin -u ${DB_USER} -p${DB_PASS} drop ${DB_NAME} -f

${SCRIPTPATH}/install-wp-tests.sh ${DB_NAME} ${DB_USER} ${DB_PASS} ${DB_HOST} ${WP_VERSION} ${SKIP_DB_CREATE}
${SCRIPTPATH}/install-wordlift.sh
