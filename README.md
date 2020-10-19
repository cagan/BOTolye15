# NOTE:
I tried to make this project in 2 days and Packagist, NPM API's didn't help me well.
Because of this I tried to make things more manually. I have some code mistakes but they can be fixed in time. I just 
wanted to show you how I can make structure and improve it. I use dependency Injection and try to make it more solid. It can be better in time I believe it.
By the way I couldn't make NPM comparation because I couldn't find anything that shows me inside of the package.json content.
It only works with composer.json for now. 

# How To Use?
### Use Docker (It could be buggy for now)
- `docker-compose up -d`
### Traditional Way
- Download the repository.
- `composer install`
- Create a database with named botolye15
- Fill .env DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD for your environment.
- `php artisan migrate`
- php artisan serve

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
