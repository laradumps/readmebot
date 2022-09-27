<img src="./misc/banner.png">

<div align="center">
  <p align="center">
    <a href="https://github.com/laradumps/readmebot/actions">
        <img alt="Tests" src="https://github.com/laradumps/readmebot/workflows/ci/badge.svg" />
    </a>
  </p>
</div>

# Welcome

## About

The **README Bot** is in charge of keeping repositories up to date, ensuring that all download links lead to the most recent version of LaraDumps.

LaraDumsp repositories:

- 🛻 [LaraDumps Laravel Package](https://github.com/laradumps/laradumps)
- 🖥️ [LaraDumps Desktop APP](https://github.com/laradumps/app)
- 📚 [LaraDumps Docs](https://github.com/laradumps/laradumps-docs)

## Get Started

### Key

` 🔐 ` A password key is required to prevent accidental activation of this software and must be provided via file or ENV var.

You must create a `.key` file containing the application key:

```plain
READMEBOT_KEY=12345
```

Composer scripts are configured to load this file automatically for development purposes.

### Prepare the files

The Bot scans files, searching for these specific HTML comment tags:

```plain
<!--LaraDumpsVersion-->
<!--EndOfLaraDumpsVersion-->
```

You cam see a full example [here](/misc/README.md.example).

### Running the bot

` 1️⃣ ` You must pass the latest [LaraDumps](https://github.com/laradumps/app/) SemVer version via the argument `--new-version`. If running tests,pass `--fake-version` for a fake version.

` 2️⃣ `  You must pass the `--github-credential` argument containing the GitHub LaraDumps Bot credentials in JSON Format.

` ❗ ` If you don't want to commit changes, pass the `--no-commit` argument.

` ❗ `  You may  exclude files from replacing by passing the `--exclude--files=<FILES>` with a comma-separated file list.

Example:

```shell
# Update the latest version from Repository
php .github/readmebot update-version --new-version='1.2.4' --github-credential='{"username":"foo","email":"foo@bar.com"}' --exclude-files='README.md,doc/example/example.md'

# For testing
php .github/readmebot update-version --fake-version --github-credential='{"username":"foo","email":"foo@bar.com"}' --exclude-files='README.md,doc/example/example.md'
```

### Configuring GitHub Actions

#### Target repository

Copy the [readmebot workflow](/.github/workflows/example/readmebot.yml.example)  file into the `.github/workflows` folder.

#### LaraDumps App Repository

Add the target repository to the [wake_up_bots.yml](https://github.com/laradumps/app/.github/workflows/wake_up_bots.yml.example) GitHub Action in the LaraDumps Desktop APP repository.

The App repository must have the [secrets variable](https://github.com/laradumps/app/settings/secrets/actions) `READMEBOT_TOKEN` configured and the token owner must have admin access in the repository.

## Credits

Developed by [@dansysanalyst](https://github.com/dansysanalyst)

Illustration by [storyset](https://www.freepik.com/author/stories)
