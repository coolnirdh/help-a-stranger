# Help A Stranger

This project started as an inspiration from the success of [Home Talkies' Help KFI Workers](http://www.hometalkies.com/help-kfi-workers/) initiative, leveraging ideas and source code [shared](http://www.astustudios.com/help-a-stranger-app/) by [Surya](https://www.instagram.com/suryavasishta/).

We've made several changes to the repository since then, specifically to achieve the following:
1. Support both UPI and Traditional Bank Account Details.
1. Change data gathering process such that we minimise risk of fraud by directly working with trusted NGOs.
1. Add metrics for deriving numbers around success of our efforts, like which beneficiary is shown how often, and how many times do people actually copy details, and an optional prompt to ask how much someone has donated.
1. Eliminate bugs that could have caused leak of mobile numbers of beneficiaries to the visitors of this site.
1. Changes that allow us to deploy onto Google Cloud.
1. Other performance improvements.

We understand it won't be straightforward to take the source code and start using it, but feel free to give us a nudge and we'd be happy to help you set it all up!

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
