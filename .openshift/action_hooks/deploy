#!/bin/bash

# This deploy hook gets executed after dependencies are resolved and the
# build hook has been run but before the application has been started back
# up again.  This script gets executed directly, so it could be python, php,
# ruby, etc.

dest_dir=${OPENSHIFT_DATA_DIR}current

current_version=$(cat ${OPENSHIFT_BUILD_DEPENDENCIES_DIR}.current_version)
install_dir=${OPENSHIFT_BUILD_DEPENDENCIES_DIR}${current_version}

if [ ! -d "${dest_dir}" ]; then
  mkdir -p $dest_dir
  cp -rf ${install_dir}/* ${dest_dir}/
fi

if [ -d $OPENSHIFT_REPO_DIR/uploads ]; then
  rm -rf $OPENSHIFT_REPO_DIR/uploads
fi
if [ ! -d $OPENSHIFT_DATA_DIR/uploads ]; then
    mkdir $OPENSHIFT_DATA_DIR/uploads
fi
ln -sf ${dest_dir}uploads $OPENSHIFT_REPO_DIR/uploads
cp -rf $OPENSHIFT_REPO_DIR/uploads/* $OPENSHIFT_DATA_DIR/uploads/ 2>/dev/null
