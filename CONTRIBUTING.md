# Setup

    make setup

# Usage

    make help

# Releases

Every merge to `master` releases through [semantic-release](https://github.com/semantic-release/semantic-release).
The PR title follows [Conventional Commits](https://conventionalcommits.org) and becomes the changelog entry:

- `fix: ...` releases a patch
- `feat: ...` releases a minor version
- `feat!: ...` or a `BREAKING CHANGE:` footer releases a major version
- other types such as `docs:`, `refactor:` or `chore:` release nothing
