# NOTE:
I use packagist API to get the package list of the searched repository. But I couldn't find NPM API for search repository.
For now It only works with package.json. But it can be implemented easily later on.I use dependency Injection and try 
to make it more solid. The main focus of this code base to show you that I can construct an application which is  easy
for extending. I could not write fully functional tests because of the limited time. But I can add more features later.

# How To Use?
### Use Docker (It could be buggy for now)
- `docker-compose up -d`
### Traditional Way
- Download the repository.
- `composer install`
- Create a database with named botolye15
- Fill .env DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD for your environment.
- `php artisan migrate`
- `php artisan serve`
- `sudo crontab -e`
- `* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1`

# API'S
- Packagist
- Github

# Components I Used
- Services:
    - GitProviderPackageService (GithubPackageService, GitlabPackageService)
    - PackageRegistryService (PackagistRegistry, NpmRegistry)
    - PackageReleaseService (ComposerOutdatedService, NpmOutdatedService)
    - UserNotificationService (NotifyUserEmailService)
- Repositories:
    - MailRepository
- Controller:
    - OutdatedPackageController
- Command:
    - NotifyUser
- Mail:
    - OutdatedRepositoryMail
- Job:
    - SendMailJob

# Asking Myself
### Why I Used Laravel?
Well, I had 2 days because of the work. And Laravel is kind of all-in-one thing. It would be faster like that.
### Could you use CLI app for this? 
Yes, but I wanted to make web app for this time.
### What things would you add if you had more time for this?
- More testing. 
- More debugging
- More error handling. 
- Trying to make composer comparator algorithm better. (Now it could be buggy)
- Add npm API (Somehow)
