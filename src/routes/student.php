<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;




// View all the students in a particular class
$app->get('/api/student/class/{id}', function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM students WHERE class_id = $id";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->query($sql);
        $class = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        return json_encode($class);
    }catch(PDOException $e){

        return json_encode($e->getMessage());
    }
     
});

// Add a new student to a new class
$app->post('/api/student/class/add/{id}', function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $age = $request->getParam('age');
    
    $sql = "INSERT INTO students (first_name, last_name, age, class_id) VALUES(:first_name, :last_name, :age, :id)";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
       
        return '{"message":"Student added successful"}';
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
     
});
