# Install
1. Install virtualbox and vagrant: `sudo apt-get install virtualbox vagrant`
1. You will find all the necessary programms to run the scripts in vagrant.
1. Use `vagrant up` to start vagrant
1. Use `vagrant ssh` to login to your virtual machine.
1. Use `composer install` in the command line to get the required packages.
1. On Facebook create a test app and set the constants `APP_ID` and `APP_SECRET` to the values you got from Facebook.
 
# Execute
 1. On the command line `cd` into the `src` folder of this repository.
 1. Get a temporary access token from [the official page](https://developers.facebook.com/tools/accesstoken/).
 1. Set the constant `ACCESS_TOKEN` in Crawler.php to the access token.
 1. Paste a file named `input.csv` into the `src` folder containing the relevant page ids comma separated.
 1. Run `php -f Crawler.php`.
 1. A new file in `src` has been created named `output.csv`.