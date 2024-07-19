<?php

/**
 * Note:
 * While the code can be used as shown in this example, 
 * it is recommended to use Next\Rest\Service notation attributes.
 * By doing so, you can call this repository directly from your service layer, 
 * e.g., App\Service\ExampleService, making the implementation cleaner and more maintainable.
 */


/**
 * This line loads the Composer autoloader, which allows you to use classes from the installed packages.
 */
require_once __DIR__ . '/vendor/autoload.php';

use NextPHP\App\Repository\UserRepository;

// Create an instance of UserRepository
$repository = new UserRepository();

// Fetch all data and print it in JSON format
$data = $repository->findAll();
echo json_encode($data);

// Fetch data by a specific ID and print it in JSON format
$id = 1;
$dataById = $repository->findById($id);
echo json_encode($dataById);

// Add new data and print the created data in JSON format
$newData = [
    'name' => 'New Item',
    'email' => 'This@item',
    'password' => 'This is a new item'
];
$createdData = $repository->save($newData);
echo json_encode($createdData);

// Update existing data and print the updated data in JSON format
$updateData = [
    'id' => 6,
    'name' => 'Updated Item',
    'email' => 'updated@mail.com',
    'password' => 'This item has been updated',
];
$id = $updateData['id'];  // Define $id here
$repository->update($id, $updateData);
echo json_encode($updateData);

// Delete data by a specific ID and print the ID of the deleted data in JSON format
$id = 24;
$deleted = $repository->delete($id);
echo json_encode(['deleted' => $id]);