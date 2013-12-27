laravel-testing-repo-generator
==============================

generates files for repository pattern

put generator.php in root of laravel project

sample command:
php generator.php post

this will create PostController.php, Post.php, PostControllerTest.php and the following structure:
app/
  lib/
    saquib/
      Storage/
        Post/
          PostRepositoryInterface.php
          EloquentPostRepository.php
        StorageServiceProvider.php
