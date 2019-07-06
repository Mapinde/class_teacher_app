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
    $name = $request->getParam('name');
    $age = $request->getParam('age');
    
    $sql = "INSERT INTO students (name, age) VALUES(:name, :age)";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $name);
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
    $name = $request->getParam('name');
    $age = $request->getParam('age');
    
    $sql = "UPDATE students SET name = :name, age = :age WHERE id = $studentId";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->execute();
       
        return '{"message":"class edited successful"}';
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
     
});

// View the subjects that each student is taking, like Maths and English
$app->get('/api/student/subjects', function (Request $request, Response $response, array $args) {
    
    $sql ="SELECT st.name, s.name FROM students st "
    . "INNER JOIN student_Subject ss on st.id = ss.student_id "
    . "INNER JOIN subjects s on s.id = ss.subject_id";
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

        return '{"message":'.$e->getMessage(). '}';
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

        return '{"message":'.$e->getMessage(). '}';
    }
     
});

// Filter the list of students by the subjects they are taking
$app->get('/api/students/subject/{id}', function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $sql ="SELECT st.name, s.name FROM students st "
    . "INNER JOIN student_Subject ss on st.id = ss.student_id "
    . "INNER JOIN subjects s on s.id = ss.subject_id WHERE s.id = $id";
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

        return '{"message":'.$e->getMessage(). '}';
    }
    
});

//Search for a particular student by Name or Age
$app->get('/api/student/name/{name}', function (Request $request, Response $response, array $args) {
    $name = $request->getAttribute('name');
    $sql ="SELECT * FROM students WHERE name like '%$name%'";
    
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->query($sql);
        $student = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        return json_encode($student);
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
    
});

//Search for a particular student by Name or Age
$app->get('/api/student/age/{age}', function (Request $request, Response $response, array $args) {
    $age = $request->getAttribute('age');
    $sql ="SELECT * FROM students WHERE age = $age";
    
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->query($sql);
        $student = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        return json_encode($student);
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
    
});

//Set the score/marks that each student obtained for each subject
$app->put('/api/student/score/add/{id}', function (Request $request, Response $response, array $args) {
    $studentId = $request->getAttribute('id');
    $score = $request->getParam('score');
    $subjectId = $request->getParam('subjectid');
    
    $sql = "UPDATE student_Subject SET score = :score WHERE student_id = $studentId AND subject_id = $subjectId ";
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':score', $score);
        $stmt->execute();
       
        return '{"message":"Score added successful"}';
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
     
});

//Calculate the total score and average score for each student
$app->get('/api/student/score', function (Request $request, Response $response, array $args) {
    
    $sql ="SELECT student_id, name,  sum(score) as total, avg(score) as average FROM student_Subject ss "
    ."INNER JOIN students st on st.id = ss.student_id group by student_id, name";
    
    try{
        //Get Db Object
        $db = new dbConnetion();
        //Connect
        $db = $db->connect();
        
        $stmt = $db->query($sql);
        $student = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        
        return json_encode($student);
    }catch(PDOException $e){

        return '{"message":'.$e->getMessage(). '}';
    }
    
});





