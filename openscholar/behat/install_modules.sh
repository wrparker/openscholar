#!/usr/bin/env bash

if [ "${TEST_SUITE}" == "harvard" ]; then
  drush en harvard_courses -y && drush cc all
fi
