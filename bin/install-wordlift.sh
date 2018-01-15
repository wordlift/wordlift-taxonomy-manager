#!/usr/bin/env bash

WP_CORE_DIR=${WP_CORE_DIR-/tmp/wordpress/}

# see http://stackoverflow.com/a/4774063/565110
pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd`
popd > /dev/null

unzip -o -d ${WP_CORE_DIR}wp-content/plugins ${SCRIPTPATH}/packages/wordlift.3.17.0.zip
