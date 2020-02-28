#!/bin/bash
set -e

# Obtain the component repository and log in
docker pull quay.io/keboola/developer-portal-cli-v2:latest
export REPOSITORY=`docker run --rm  \
    -e KBC_DEVELOPERPORTAL_USERNAME \
    -e KBC_DEVELOPERPORTAL_PASSWORD \
    quay.io/keboola/developer-portal-cli-v2:latest \
    ecr:get-repository ${KBC_DEVELOPERPORTAL_VENDOR} ${KBC_DEVELOPERPORTAL_APP}`
eval $(docker run --rm \
    -e KBC_DEVELOPERPORTAL_USERNAME \
    -e KBC_DEVELOPERPORTAL_PASSWORD \
    quay.io/keboola/developer-portal-cli-v2:latest \
    ecr:get-login ${KBC_DEVELOPERPORTAL_VENDOR} ${KBC_DEVELOPERPORTAL_APP})

# Push to the repository
if [[ ${TRAVIS_TAG} != "" ]]
then
    docker tag ${APP_IMAGE}:latest ${REPOSITORY}:${TRAVIS_TAG}
    docker push ${REPOSITORY}:${TRAVIS_TAG}
elif [[ ${TRAVIS_PULL_REQUEST} == "false" && ${TRAVIS_BRANCH} != "" ]]
then
    docker tag ${APP_IMAGE}:latest ${REPOSITORY}:${TRAVIS_BRANCH}
    docker push ${REPOSITORY}:${TRAVIS_BRANCH}
fi
