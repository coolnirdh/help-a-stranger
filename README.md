# Help A Stranger

## Performing a deployment:
This project is deployed to [Google Cloud's App Engine](https://cloud.google.com/appengine/docs/standard/php7) with PHP 7.3 Runtime.

#### Pre-requisites:
1. In order to deploy the application, ensure that you have JSON key in assets directory with you. This file is excluded from GitHub on purpose, because it includes sensitive backend access to our backend.
1. You would also have to download and install [Google Cloud SDK](https://cloud.google.com/sdk/docs) first. If you are a [Homebrew](https://brew.sh/) user, you could simply run:
    ```
    brew cask install google-cloud-sdk
    ```
1. You have logged in with helpastrangerindia@gmail.com email:
    ```
    gcloud auth login
    ```
1. You have set a `helpastranger` as your default project as follows:
    ```
    gcloud config set project helpastranger
    ```

#### Deploying to test environment:
Following command will create a new version for you from your local codebase. Run it from project root directory:
```
gcloud app deploy --no-promote 
```

#### Promoting to production:
In order to promote the same version that you tested, run the following command from project root after replacing version:
```
gcloud app versions migrate <version>
```

#### Cleaning up unused versions:
1. List existing versions:
    ```
    gcloud app versions list
    ```
1. Delete versions that aren't serving traffic:
    ```
    gcloud app versions delete <version1> <version2> <versionN>
    ```
