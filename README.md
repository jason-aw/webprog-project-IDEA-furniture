# webprog-project-IDEA-furniture

In this project, we are required to build a simple e-commerce website called IDEA Furniture which sells furniture. For backend, we used version Laravel 7.* as our framework. For authentication, we used Laravel’s build in authentication utilizing the laravel/ui package (version 2.4).

Seeders and Migrations are available so it's easy to try out the website. When doing important actions (performing CRUD, checkout, etc) there is a confirmation dialog just in case any misclicks occur, to confirm the user’s actions. Proper feedback is also displayed (success and error messages) after doing certain actions to make sure the user is engaged with the website.

# Notes

*	The database name is “ideafurnituredb”
*	Please perform migration and seeding with php artisan migrate:fresh –seed
*	Please create storage link with php artisan storage:link
*	We have also included an image file, please copy the images to the storage/app/public/images before viewing the website, as performing CRUD operations will also change the storage system.
*	About the file system:
    *	All product type images are stored in thumbnail/ folder
    *	Product type images when stored are named in this format: timestamp_productTypeName.ext
    *	All product images are stored in a folder named after its product type 
    *	Product type images when stored are named in this format: timestamp_productName.ext
*	Since not specified in the question, all users who registers through the register page in the website will be automatically be registered as ‘member’ role. To add admin user(s), manual insert through SQL queries or from the UserSeeder is required.

# Members
* Jason Andersen Winfrey
* Pietter Haizel
