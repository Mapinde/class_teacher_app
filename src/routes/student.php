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
    
    $sql = "INSERT INTO students (first_name, last_name, age) VALUES(:first_name, :last_name, :age)";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':age', $age);
        $stmt->execute();
       
        return '{"message":"Student added successful"}';
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
     
});

// Edit a student’s details like Name and Age
$app->put('/api/student/edit/{id}', function (Request $request, Response $response, array $args) {
    $studentId = $request->getAttribute('id');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $age = $request->getParam('age');
    
    $sql = "UPDATE students SET first_name = :first_name, last_name = :last_name, age = :age WHERE id = $studentId";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':age', $age);
        $stmt->execute();
       
        return '{"message":"class edited successful"}';
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
     
});

// View the subjects that each student is taking, like Maths and English
$app->get('/api/student/subjects', function (Request $request, Response $response, array $args) {
    
    $sql ="select st. first_name, st.last_name, s.name  from students st "
    . "inner join student_Subject ss on st.id = ss.student_id "
    . "inner join subjects s on s.id = ss.subject_id";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->query($sql);
        $students = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        return json_encode($students);
    }catch(PDOException $e){

        return json_encode($e->getMessage());
    }
    
    
});

// Assign a subject to a student
$app->post('/api/student/subject/assign/{id}', function (Request $request, Response $response, array $args) {
    $studentId = $request->getAttribute('id');
    $subjectId = $request->getParam('subjectid');
   
    $sql = "INSERT INTO student_Subject (student_id, subject_id) VALUES(:studentId, :subjectId)";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':studentId', $studentId);
        $stmt->bindParam(':subjectId', $subjectId);

        $stmt->execute();
       
        return '{"message":"Student assign successful"}';
    }catch(PDOException $e){

        return json_encode($e->getMessage());
    }
     
});


