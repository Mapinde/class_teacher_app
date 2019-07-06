<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

//Calculate the total score and average score for each class
$app->get('/api/class/score', function (Request $request, Response $response, array $args) {
    
    $sql ="SELECT class_id, c.name, sum(score) as total, avg(score) as average "
    ."FROM student_Subject ss INNER JOIN students st on st.id = ss.student_id "
    ."INNER JOIN classes c on c.id = st.class_id group by class_id";
    
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

        return '{"message":'.$e->getMessage(). '}';
    }
    
});

//Rank all my classes by total score and by average score

$app->get('/api/class/teacher/rank/{id}', function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id');
    $sql ="SELECT class_id, c.name, sum(score) as total, avg(score) as average "
    ."FROM student_Subject ss INNER JOIN students st on st.id = ss.student_id "
    ."INNER JOIN classTeacher ct ON ct.classid = st.class_id "
    ."INNER JOIN classes c on c.id = st.class_id WHERE ct.teacherId = $id group by class_id order by total, average asc";
    
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

        return '{"message":'.$e->getMessage(). '}';
    }
    
});
