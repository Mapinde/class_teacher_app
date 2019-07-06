<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Get All class that  i'm teacher of
$app->get('/api/teacher/class/{id}', function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
  
    $sql = "SELECT c.id, c.code FROM classTeacher ct INNER JOIN classes c on ct.classId = c.id WHERE ct.teacherId = $id";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->query($sql);
        $classes = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        return json_encode($classes);
    }catch(PDOException $e){

        return json_encode($e->getMessage());
    }
     
});

// Add a new class to my list of classes
$app->post('/api/teacher/class/add/{id}', function (Request $request, Response $response, array $args) {
    $teacherId = $request->getAttribute('id');
    $classId = $request->getParam('classid');
    
    $sql = "INSERT INTO classTeacher (teacherId, classId) VALUES (:teacherId, :classId)";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':teacherId', $teacherId);
        $stmt->bindParam(':classId', $classId);

        $stmt->execute();
       
        return '{"message":"class Added successful"}';
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
     
});
