# Release Checklist

- [ ] Ensure the documentation is up to date, particularly the changelog
- [ ] Merge the `develop` branch into the `master` branch (`git checkout master; git merge develop`)
- [ ] Push the code changes to GitHub (`git push`)
- [ ] Check the [Travis CI results](https://travis-ci.org/squigg/azure-queue-laravel)
- [ ] Tag the release (`git tag 1.2.3`)
- [ ] Push the tag (`git push --tag`)
